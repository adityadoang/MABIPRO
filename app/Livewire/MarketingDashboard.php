<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Block;
use App\Models\Unit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;

/**
 * Komponen Livewire untuk Dashboard Marketing.
 * Komponen ini mengatur tampilan dan logika interaksi pengguna (Marketing/Admin)
 * di halaman Dashboard Marketing tanpa perlu me-reload halaman.
 */
#[Layout('layouts.app')] // Menentukan layout Blade yang digunakan oleh komponen ini
class MarketingDashboard extends Component
{
    // Menggunakan trait WithFileUploads agar komponen bisa menangani upload file (misal bukti pembayaran)
    use WithFileUploads;

    // ─────────────────────────────────────────────────────────────
    // STATE / PROPERTI
    // Properti di bawah ini berfungsi seperti variabel di Javascript.
    // Jika nilainya berubah, tampilan di frontend akan otomatis menyesuaikan.
    // ─────────────────────────────────────────────────────────────

    // State untuk UI Utama
    public $selectedBlockId = null; // Menyimpan ID blok yang sedang dipilih
    public $searchUnit      = '';   // Menyimpan teks pencarian unit

    // State untuk Modal Pembayaran
    public $isPaymentModalOpen = false; // Status apakah modal terbuka (true) atau tertutup (false)
    public $selectedUnitId;             // ID unit yang sedang diklik untuk dilihat pembayarannya

    // State untuk Data Pembayaran
    public $paymentMethod;              // Metode: 'Cash' atau 'KPR'
    public $amountPaid;                 // Nominal yang sudah dibayar
    public $paymentProof;               // Menyimpan file bukti pembayaran yang diupload

    // State Khusus KPR
    public $hargaUnit;
    public $kprType = 'non_subsidi';    // Default jenis KPR: 'subsidi' atau 'non_subsidi'
    public $bankName;
    public $akadDate;
    public $dpAmount;                   // Nominal DP (Down Payment)
    public $dpPercentage;               // Persentase DP dari harga unit
    public $kprDurationMonths;          // Lama cicilan (Tenor) dalam bulan

    // State untuk Hasil Kalkulasi (Hanya dibaca / Read-only)
    public $pokokKredit        = 0;     // Sisa yang harus dicicil (Harga - DP)
    public $monthlyInstallment = 0;     // Estimasi cicilan per bulan
    public $sisaTagihan        = 0;     // Sisa tagihan (untuk Cash)

    // ─────────────────────────────────────────────────────────────
    // LIFECYCLE HOOKS
    // ─────────────────────────────────────────────────────────────

    /**
     * Method mount() berjalan satu kali saat komponen pertama kali dimuat.
     * Mirip seperti fungsi constructor() di kelas standar atau ngOnInit di Angular.
     */
    public function mount()
    {
        // Secara default, pilih blok pertama yang ada di database agar tabel tidak kosong
        $firstBlock = Block::first();
        if ($firstBlock) {
            $this->selectedBlockId = $firstBlock->id;
        }
    }

    // ─────────────────────────────────────────────────────────────
    // FUNGSI INTERAKSI PENGGUNA (ACTION)
    // ─────────────────────────────────────────────────────────────

    /**
     * Mengubah blok perumahan yang dipilih oleh pengguna.
     * Dipanggil lewat "wire:click='selectBlock(ID)'" di frontend.
     *
     * @param int $blockId
     */
    public function selectBlock($blockId)
    {
        $this->selectedBlockId = $blockId;
        // Reset pencarian tiap kali pindah blok
        $this->searchUnit = '';
    }

    /**
     * Memperbarui status penjualan suatu unit (Misal: Belum Terjual -> Sudah DP).
     *
     * @param int $unitId ID dari unit yang diubah
     * @param string $newStatus Status yang baru dipilih
     */
    public function updateStatus($unitId, $newStatus)
    {
        $validStatuses = ['Belum Terjual', 'Sudah DP', 'Terjual'];

        if (in_array($newStatus, $validStatuses)) {
            $unit = Unit::findOrFail($unitId);

            if ($newStatus === 'Belum Terjual') {
                // Jika status diubah kembali menjadi "Belum Terjual", bersihkan semua data pembayarannya
                $this->resetPaymentData($unit);
            } else {
                // Jika statusnya DP atau Terjual, cukup update statusnya saja
                $unit->update(['status_penjualan' => $newStatus]);
            }

            // Memunculkan pesan sukses sementara (flash message)
            session()->flash('message', "Status unit {$unit->unit_number} berhasil diperbarui menjadi {$newStatus}.");
        }
    }

    /**
     * Membuka modal pop-up untuk mengedit detail pembayaran unit tertentu.
     *
     * @param int $unitId
     */
    public function openPaymentModal($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        
        // Isi state di komponen ini dengan data dari database
        $this->selectedUnitId    = $unit->id;
        $this->paymentMethod     = $unit->payment_method;
        $this->amountPaid        = $unit->amount_paid;
        $this->paymentProof      = null; // Selalu mulai dengan kosong untuk upload baru

        $this->hargaUnit         = $unit->harga_unit;
        $this->kprType           = $unit->kpr_type ?? 'non_subsidi';
        $this->bankName          = $unit->bank_name;
        $this->akadDate          = $unit->akad_date;
        $this->dpAmount          = $unit->dp_amount;
        $this->dpPercentage      = $unit->dp_percentage;
        $this->kprDurationMonths = $unit->kpr_duration_months;

        // Hitung ulang estimasi KPR berdasarkan data yang ditarik
        $this->recalculate();
        
        // Tampilkan modal (ubah dari false ke true)
        $this->isPaymentModalOpen = true;
    }

    /**
     * Menutup modal dan mengosongkan form (reset) agar tidak ada data nyangkut.
     */
    public function closePaymentModal()
    {
        $this->isPaymentModalOpen = false;
        
        // Reset properti kembali ke nilai bawaannya
        $this->reset([
            'selectedUnitId', 'paymentMethod', 'amountPaid', 'paymentProof',
            'hargaUnit', 'kprType', 'bankName', 'akadDate',
            'dpAmount', 'dpPercentage',
            'kprDurationMonths', 'monthlyInstallment',
            'pokokKredit', 'sisaTagihan'
        ]);
        $this->kprType = 'non_subsidi'; // Set ulang nilai default
        
        // Hilangkan error validasi yang mungkin masih tampil di modal
        $this->resetValidation();
    }

    // ─────────────────────────────────────────────────────────────
    // LIVE WATCHERS (Fungsi yang berjalan otomatis saat properti berubah)
    // ─────────────────────────────────────────────────────────────

    // Fungsi 'updated' adalah fungsi bawaan Livewire yang akan otomatis dipanggil
    // setiap kali ada input (wire:model) yang diubah oleh user di tampilan.
    public function updated($propertyName, $value)
    {
        // 1. Jika user mengubah harga unit, hitung nominal DP secara otomatis
        if ($propertyName === 'hargaUnit' && $this->dpPercentage > 0 && $value > 0) {
            $this->dpAmount = round(($this->dpPercentage / 100) * $value, 0);
        }

        // 2. Jika user mengetik nominal DP manual, hitung persentase DP-nya
        if ($propertyName === 'dpAmount' && $this->hargaUnit > 0 && $value >= 0) {
            $this->dpPercentage = round(($value / $this->hargaUnit) * 100, 2);
        }

        // 3. Jika user mengetik persen DP manual, hitung nominal DP-nya
        if ($propertyName === 'dpPercentage' && $this->hargaUnit > 0 && $value >= 0) {
            $this->dpAmount = round(($value / 100) * $this->hargaUnit, 0);
        }

        // Apapun yang diubah oleh user, selalu panggil fungsi recalculate 
        // untuk menghitung ulang estimasi sisa tagihan dan cicilan.
        $this->recalculate();
    }

    /**
     * Melakukan kalkulasi matematika sederhana untuk menentukan sisa tagihan,
     * pokok kredit, dan estimasi cicilan bulanan.
     */
    public function recalculate()
    {
        $harga = (float) ($this->hargaUnit ?? 0);
        $dp    = (float) ($this->dpAmount  ?? 0);
        $paid  = (float) ($this->amountPaid ?? 0);
        $n     = (int)   ($this->kprDurationMonths ?? 0); // Tenor

        if ($this->paymentMethod === 'Cash') {
            $this->sisaTagihan = max(0, $harga - $paid);
        }

        // Pokok kredit adalah Harga Unit dikurangi DP
        $pokok = max(0, $harga - $dp);
        $this->pokokKredit = $pokok;

        // Cicilan per bulan = Pokok dibagi Lama Cicilan (Tenor)
        $this->monthlyInstallment = ($pokok > 0 && $n > 0) ? (int) round($pokok / $n) : 0;
    }

    // ─────────────────────────────────────────────────────────────
    // SIMPAN DATA KE DATABASE
    // ─────────────────────────────────────────────────────────────

    /**
     * Menyimpan data dari form modal pembayaran ke dalam database.
     * Dipanggil lewat "wire:submit.prevent='savePaymentDetails'" pada form di frontend.
     */
    public function savePaymentDetails()
    {
        $isAdmin = auth()->check() && auth()->user()->isAdmin();

        // 1. Validasi Input form
        $this->validatePaymentData($isAdmin);

        // 2. Cari unit berdasarkan ID. Kalau tidak ketemu, otomatis lempar error 404
        $unit = Unit::findOrFail($this->selectedUnitId);
        
        // Cuma Admin yang boleh merubah harga lewat form ini
        if (!$isAdmin) {
            $this->hargaUnit = $unit->harga_unit;
        }

        // 3. Simpan file bukti pembayaran jika ada upload baru
        $path = $unit->payment_proof_path; // Simpan path lama sebagai cadangan
        if ($this->paymentProof) {
            // "store()" akan mengupload file ke dalam direktori storage/app/public/payment_proofs
            $path = $this->paymentProof->store('payment_proofs', 'public');
        }

        $this->recalculate();

        // 4. Update data berdasarkan metode pembayarannya
        if ($this->paymentMethod === 'KPR') {
            $this->saveKprData($unit, $path);
        } else {
            $this->saveCashData($unit, $path);
        }

        // 5. Tutup modal dan tampilkan alert hijau notifikasi
        $this->closePaymentModal();
        session()->flash('message', "Detail pembayaran unit {$unit->unit_number} berhasil disimpan.");
    }

    // ─────────────────────────────────────────────────────────────
    // FUNGSI PEMBANTU (HELPERS) - Ekstraksi kode agar lebih rapi
    // ─────────────────────────────────────────────────────────────

    /**
     * Menyediakan data ringkasan statis (Total, Terjual, dll.) untuk ditampilkan di kotak statistik teratas.
     */
    public function getStatsProperty(): array
    {
        // Menggunakan fitur hitung (count) langsung dari Database. 
        // Ini lebih ringan dan mudah dipahami (mirip konsep SELECT COUNT(*) di MySQL).
        $total    = Unit::count();
        $terjual  = Unit::where('status_penjualan', 'Terjual')->count();
        $sudahDp  = Unit::where('status_penjualan', 'Sudah DP')->count();
        $belum    = Unit::where('status_penjualan', 'Belum Terjual')->count();

        return [
            'total'       => $total,
            'terjual'     => $terjual,
            'sudah_dp'    => $sudahDp,
            'belum'       => $belum,
            'pct_terjual' => $total > 0 ? round(($terjual / $total) * 100, 1) : 0,
            'pct_dp'      => $total > 0 ? round(($sudahDp / $total) * 100, 1) : 0,
            'pct_belum'   => $total > 0 ? round(($belum / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Memvalidasi form. Jika ada input yang salah, maka proses berhenti dan memunculkan $message error di tampilan.
     */
    private function validatePaymentData(bool $isAdmin)
    {
        $rules = [
            'paymentMethod' => 'required|in:Cash,KPR',
            'paymentProof'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];

        if ($isAdmin) {
            $rules['hargaUnit'] = 'required|numeric|min:1';
        }

        // Tambah aturan wajib isi kalau pembayarannya pilih KPR
        if ($this->paymentMethod === 'KPR') {
            $rules = array_merge($rules, [
                'kprType'           => 'required|in:subsidi,non_subsidi',
                'bankName'          => 'required|string|max:100',
                'akadDate'          => 'nullable|date',
                'dpAmount'          => 'required|numeric|min:0',
                'dpPercentage'      => 'required|numeric|min:0|max:100',
                'kprDurationMonths' => 'required|integer|min:12|max:360',
            ]);
        }

        // Fungsi bawaan Livewire untuk validasi.
        $this->validate($rules);
    }

    /**
     * Menyimpan data KPR ke tabel database lewat model Unit.
     */
    private function saveKprData(Unit $unit, ?string $path)
    {
        $unit->update([
            'payment_method'      => 'KPR',
            'amount_paid'         => $this->dpAmount, // Nominal yang sudah dibayar awal adalah seharga DP
            'payment_proof_path'  => $path,
            'harga_unit'          => $this->hargaUnit,
            'kpr_type'            => $this->kprType,
            'bank_name'           => $this->bankName,
            'akad_date'           => $this->akadDate ?: null,
            'dp_amount'           => $this->dpAmount,
            'dp_percentage'       => $this->dpPercentage,
            'pokok_kredit'        => $this->pokokKredit,
            'kpr_duration_months' => $this->kprDurationMonths,
            'monthly_installment' => $this->monthlyInstallment,
            'interest_rate'       => null,
            'interest_type'       => null,
        ]);
    }

    /**
     * Menyimpan data Pembayaran Tunai (Cash).
     * Semua field yang berkaitan dengan KPR akan dikosongkan secara otomatis.
     */
    private function saveCashData(Unit $unit, ?string $path)
    {
        $unit->update([
            'payment_method'      => 'Cash',
            'amount_paid'         => $this->hargaUnit, // Bayar tunai biasanya lunas seharga unit
            'payment_proof_path'  => $path,
            'harga_unit'          => $this->hargaUnit,
            // Hapus isi data KPR di database karena ini tunai
            'kpr_type'            => null,
            'bank_name'           => null,
            'akad_date'           => null,
            'dp_amount'           => null,
            'dp_percentage'       => null,
            'pokok_kredit'        => null,
            'kpr_duration_months' => null,
            'interest_rate'       => null,
            'interest_type'       => null,
            'monthly_installment' => null,
        ]);
    }

    /**
     * Mereset data pembayaran ketika status rumah dikembalikan ke "Belum Terjual".
     */
    private function resetPaymentData(Unit $unit)
    {
        $unit->update([
            'status_penjualan'    => 'Belum Terjual',
            'payment_method'      => null,
            'kpr_duration_months' => null,
            'amount_paid'         => null,
            'payment_proof_path'  => null,
            'harga_unit'          => null,
            'kpr_type'            => null,
            'bank_name'           => null,
            'akad_date'           => null,
            'dp_amount'           => null,
            'dp_percentage'       => null,
            'pokok_kredit'        => null,
            'interest_rate'       => null,
            'interest_type'       => null,
            'monthly_installment' => null,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // FUNGSI RENDER (MENGHUBUNGKAN BACKEND DENGAN FRONTEND VIEW)
    // ─────────────────────────────────────────────────────────────

    /**
     * Fungsi render() akan dijalankan otomatis tiap kali ada perubahan State.
     * Mengembalikan tampilan Blade beserta variabel yang diperlukan ($blocks, $filteredUnits, dsb).
     */
    public function render()
    {
        // Tarik data seluruh Blok yang punya relasi ke tabel Units
        $blocks = Block::with('units')->get();

        $selectedBlock = null;
        $filteredUnits = collect(); // Koleksi kosong

        // Cek jika user sudah mengeklik salah satu blok di menu samping
        if ($this->selectedBlockId) {
            // Cari data Blok berdasarkan ID
            $selectedBlock = Block::find($this->selectedBlockId);
            
            if ($selectedBlock) {
                // Pencarian data menggunakan Query Database biasa (seperti bahasa SQL)
                // Ini lebih mudah diajarkan ke pemula daripada menggunakan fungsi Filter/Closure tingkat lanjut
                $query = Unit::where('block_id', $this->selectedBlockId);

                // Jika kotak pencarian tidak kosong, tambahkan syarat pencarian
                if ($this->searchUnit != '') {
                    $kataKunci = '%' . strtolower($this->searchUnit) . '%';
                    
                    $query->where(function($q) use ($kataKunci) {
                        $q->where('unit_number', 'like', $kataKunci)
                          ->orWhere('tipe_unit', 'like', $kataKunci)
                          ->orWhere('facing', 'like', $kataKunci);
                    });
                }

                // Ambil hasil pencariannya
                $filteredUnits = $query->get();
            }
        }

        // Tampilkan file resources/views/livewire/marketing-dashboard.blade.php 
        // dan berikan data yang disiapkan di atas lewat bentuk Array Asosiatif
        return view('livewire.marketing-dashboard', [
            'blocks'        => $blocks,
            'selectedBlock' => $selectedBlock,
            'filteredUnits' => $filteredUnits,
            'stats'         => $this->getStatsProperty(),
        ]);
    }
}