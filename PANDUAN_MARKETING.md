# Panduan Teknis & Arsitektur Modul Marketing (MABIPRO)
*Dokumen ini dibuat sebagai panduan komprehensif (Masterclass) untuk memahami teknis, alur, dan kode di balik fitur Marketing. Sangat berguna untuk bahan presentasi atau sidang project.*

---

## RANGKUMAN CARA KERJA SISTEM (Alur Logika)
Jika ditanya bagaimana **ALUR SISTEM DARI AWAL SAMPAI AKHIR**, berikut adalah langkah-langkah di belakang layar:

1. **(Load Data):** Saat halaman Dashboard dibuka, sistem Laravel menarik seluruh relasi data tabel `blocks` beserta anak-anak rumahnya (`units`) dari Database.
2. **(Interaksi UI):** Marketing melihat rumah masih "Belum Terjual". Marketing memilih status "Sudah DP" pada *dropdown*. Event `wire:change` secara *real-time* langsung men-update kolom status ke database.
3. **(Input Pembayaran):** Marketing menekan tombol "Detail Pembayaran". Tombol ini menggunakan `wire:click` yang memicu Livewire untuk membuka *Modal Pop-Up* (Variabel `$isPaymentModalOpen` diubah menjadi `true`).
4. **(Kalkulasi Pintar):** Marketing memasukkan form KPR (Harga, DP, Bunga, Tenor) menggunakan atribut `wire:model.live`. Setiap detak pengetikan, sistem menjalankan fungsi `recalculate()` di Backend PHP. PHP mengeksekusi rumus "Amortisasi Perbankan (Anuitas)" dan "Bunga Flat", lalu mengirim dan menampilkan hasil *Cicilan Per Bulan* secara instan di layar tanpa reload halaman.
5. **(Penyimpanan Bukti):** Marketing meng-upload file bukti transfer. Trait `WithFileUploads` dari Livewire memproses file gambar/PDF tersebut dan menyimpannya secara fisik di *storage server*.
6. **(Update Database):** Saat menekan tombol Simpan, fungsi `savePaymentDetails()` menjalankan Validasi Keamanan (memastikan input angka dan tipe file benar), lalu mengeksekusi *Mass Assignment Query Update* ke tabel `units` di Database. Kotak pop-up tertutup, dan notifikasi sukses muncul.

---

## BEDAH KODE: 5 PILAR UTAMA

Dalam arsitektur aplikasi TALL Stack (Tailwind, Alpine, Laravel, Livewire) ini, Modul Marketing terdiri dari 5 pilar utama.

### PILAR 1: Routing (Pintu Masuk URL)
*Lokasi File: `routes/web.php`*

Ini adalah "peta jalan" aplikasi. Aplikasi ini mendefinisikan halaman utama untuk bagian marketing:
```php
Route::get('/', function () {
    return redirect()->route('marketing.dashboard');
});

Route::get('/dashboard', \App\Livewire\MarketingDashboard::class)->name('marketing.dashboard');
Route::get('/laporan-pembayaran', \App\Livewire\PaymentReport::class)->name('payment.report');
```
Saat web dibuka, otomatis dialihkan ke `/dashboard`. URL ini tidak dikendalikan oleh Controller biasa, melainkan langsung oleh kelas **Livewire**.

---

### PILAR 2: Struktur Database (Master Data)
*Lokasi File: `database/migrations/`*

Data perumahan bertumpu pada tabel `blocks` dan `units`. Tabel `units` menampung detail transaksi dan didesain secara *Denormalisasi* (semua info pembayaran menempel di tabel unit agar performa query Dashboard lebih cepat).

Kolom teknis pada tabel `units`:
- **Pembayaran Umum:** `payment_method` (Cash/KPR), `amount_paid` (DP/Nominal masuk), `payment_proof_path` (Lokasi file struk).
- **Kalkulasi KPR:** `harga_unit`, `kpr_type`, `bank_name`, `akad_date`, `dp_amount` (Rp), `dp_percentage` (%), `pokok_kredit` (Hutang Murni), `interest_rate` (Bunga), `interest_type` (Flat/Anuitas), `monthly_installment` (Cicilan bulanan).
*Jika status unit 'Belum Terjual', semua kolom ini otomatis di-set Null.*

---

### PILAR 3: Model Eloquent (Penghubung Database)
*Lokasi File: `app/Models/Unit.php`*

```php
class Unit extends Model {
    protected $guarded = []; // Mengizinkan proses Mass-Assignment (Update data sekaligus)

    // Relasi One-to-Many (Inverse): Satu Unit dimiliki oleh satu Blok
    public function block(): BelongsTo {
        return $this->belongsTo(Block::class); 
    }
}
```

---

### PILAR 4: Logika Bisnis & Kalkulator KPR (Livewire)
*Lokasi File: `app/Livewire/MarketingDashboard.php`*

Ini adalah "Otak" aplikasi. Karena menggunakan **Livewire**, file PHP ini berjalan secara reaktif seperti JavaScript.

**A. Proses "Live Watchers" (Reaktivitas)**
Sistem mendeteksi saat user mengetik sesuatu menggunakan *Lifecycle Hook* `updated[NamaProperti]`.
```php
public function updatedInterestRate() { 
    $this->recalculate(); 
}
```
Begitu nilai suku bunga diubah, PHP langsung menjalankan kalkulasi cicilan secara instan.

**B. Membedah Fungsi `recalculate()` (Matematika Keuangan)**
Ini adalah rumus kalkulator di balik layar.
```php
public function recalculate()
{
    $harga = (float) ($this->hargaUnit ?? 0);
    $dp    = (float) ($this->dpAmount ?? 0);
    $n     = (int)   ($this->kprDurationMonths ?? 0); // Tenor bulan
    $rTahunan = (float) ($this->interestRate ?? 0); // Bunga tahunan

    // Menentukan Pokok Kredit
    $pokok = max(0, $harga - $dp);
    $this->pokokKredit = $pokok;

    // RUMUS ANUITAS (Standar Bank)
    if ($this->interestType === 'anuitas') {
        $r = $rTahunan / 100 / 12; // Bunga bulanan
        // Rumus Amortisasi Bank: P x [ r(1+r)^n / ((1+r)^n - 1) ]
        $cicilan = $pokok * ($r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1);
    } 
    // RUMUS FLAT (Leasing/KKB)
    else {
        $bungaBulanan  = ($rTahunan / 100 / 12) * $pokok; 
        $pokokBulanan  = $pokok / $n; 
        $cicilan       = $pokokBulanan + $bungaBulanan;
    }

    // Menyimpan hasil untuk ditampilkan di layar
    $this->monthlyInstallment = round($cicilan, 0);
    $this->totalPayment       = round($cicilan * $n, 0);
    $this->totalInterest      = round(($cicilan * $n) - $pokok, 0);
}
```

**C. Fitur Validasi Status**
Terdapat fitur *Reset Otomatis*. Jika status rumah dibatalkan (diubah dari "Terjual" kembali ke "Belum Terjual"), sistem akan mem-bypass nilai `null` pada data KPR untuk menjaga *Data Integrity* (mencegah data cicilan tersisa pada rumah kosong).

---

### PILAR 5: Tampilan Antarmuka (Blade Views)
*Lokasi File: `resources/views/livewire/marketing-dashboard.blade.php`*

Tampilan ini disuntik dengan *Directives* Livewire yang menjembatani HTML dan PHP.

**Data Binding (`wire:model.live`)**
```html
<input wire:model.live="dpAmount" type="number" class="form-input">
```
*Kata `.live` sangat krusial.* Setiap kali jari *user* melepas tuts keyboard (Keyup), HTML secara asinkron (AJAX) mengirim nominal tersebut ke PHP. PHP menghitung angka cicilan, dan langsung mengganti teks `{{ number_format($monthlyInstallment) }}` di layar tanpa proses "Loading Page". Hal ini menciptakan pengalaman Single Page Application (SPA).

---

*Disusun dengan panduan AI. Semoga sukses untuk presentasi dan pengujian aplikasinya!*
