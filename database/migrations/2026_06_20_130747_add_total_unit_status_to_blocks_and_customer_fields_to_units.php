<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom ke tabel blocks (hanya yang belum ada)
        Schema::table('blocks', function (Blueprint $table) {
            if (!Schema::hasColumn('blocks', 'total_unit')) {
                $table->integer('total_unit')->default(0)->after('nama_blok');
            }
            if (!Schema::hasColumn('blocks', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('total_unit');
            }
        });

        // Tambah kolom ke tabel units (hanya yang belum ada)
        Schema::table('units', function (Blueprint $table) {
            if (!Schema::hasColumn('units', 'customer_name')) {
                $table->string('customer_name', 255)->nullable()->after('unit_number');
            }
            if (!Schema::hasColumn('units', 'customer_phone')) {
                $table->string('customer_phone', 20)->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('units', 'luas_tanah')) {
                $table->decimal('luas_tanah', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('units', 'luas_bangunan')) {
                $table->decimal('luas_bangunan', 10, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn(['total_unit', 'status']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'customer_phone', 'harga_unit', 'luas_tanah', 'luas_bangunan']);
        });
    }
};