<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('construction_progress', function (Blueprint $table) {
            // Kategori pekerjaan: null = tahapan utama rumah,
            // selain itu = produk sampingan (Jalan, Masjid, Taman, Fasilitas Umum, atau kategori manual)
            if (! Schema::hasColumn('construction_progress', 'kategori')) {
                $table->string('kategori')->nullable()->after('tahap');
            }

            // Perluas enum tahap dengan 'Lainnya' untuk progres produk sampingan/kategori manual
            $table->enum('tahap', [
                'Persiapan Lahan',
                'Pondasi',
                'Struktur & Dinding',
                'Pengecatan',
                'Finishing',
                'Serah Terima',
                'Lainnya',
            ])->default('Persiapan Lahan')->change();
        });
    }

    public function down(): void
    {
        Schema::table('construction_progress', function (Blueprint $table) {
            if (Schema::hasColumn('construction_progress', 'kategori')) {
                $table->dropColumn('kategori');
            }

            $table->enum('tahap', [
                'Persiapan Lahan',
                'Pondasi',
                'Struktur & Dinding',
                'Pengecatan',
                'Finishing',
                'Serah Terima',
            ])->default('Persiapan Lahan')->change();
        });
    }
};
