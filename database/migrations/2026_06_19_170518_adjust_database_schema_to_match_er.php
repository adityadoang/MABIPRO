<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * NOTE: Semua perubahan di migration ini sudah diterapkan di migration-migration
     * sebelumnya (create_users_table, create_blocks_table, create_units_table,
     * create_mabipro_legal_tables). Migration ini dijadikan no-op untuk kompatibilitas.
     */
    public function up(): void
    {
        // 1. Rename blocks.name -> nama_blok jika kolom lama masih ada
        if (Schema::hasColumn('blocks', 'name') && !Schema::hasColumn('blocks', 'nama_blok')) {
            Schema::table('blocks', function (Blueprint $table) {
                $table->renameColumn('name', 'nama_blok');
            });
        }

        // 2. Rename units.sales_status -> status_penjualan jika kolom lama masih ada
        if (Schema::hasColumn('units', 'sales_status') && !Schema::hasColumn('units', 'status_penjualan')) {
            Schema::table('units', function (Blueprint $table) {
                $table->renameColumn('sales_status', 'status_penjualan');
            });
        }

        // 3. Tambah progres_pembangunan & status_legalitas ke units jika belum ada
        Schema::table('units', function (Blueprint $table) {
            if (!Schema::hasColumn('units', 'progres_pembangunan')) {
                $table->integer('progres_pembangunan')->default(0)->after('status_penjualan');
            }
            if (!Schema::hasColumn('units', 'status_legalitas')) {
                $table->string('status_legalitas')->default('Belum Lengkap')->after('progres_pembangunan');
            }
        });

        // 4. Tambah role ke users jika belum ada
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('marketing')->after('password');
            });
        }

        // 5. Buat legal_documents jika belum ada
        if (!Schema::hasTable('legal_documents')) {
            Schema::create('legal_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
                $table->string('document_name');
                $table->string('file_path');
                $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            });
        }

        // 6. Buat progress_photos jika belum ada
        if (!Schema::hasTable('progress_photos')) {
            Schema::create('progress_photos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
                $table->string('file_path');
                $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: tabel-tabel ini dikelola oleh migration-migration lain
    }
};
