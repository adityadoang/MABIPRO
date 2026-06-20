<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update blocks table
        Schema::table('blocks', function (Blueprint $table) {
            $table->renameColumn('name', 'nama_blok');
        });

        // 2. Update units table
        Schema::table('units', function (Blueprint $table) {
            $table->renameColumn('sales_status', 'status_penjualan');
        });
        
        Schema::table('units', function (Blueprint $table) {
            $table->integer('progres_pembangunan')->default(0)->after('status_penjualan');
            $table->string('status_legalitas')->default('Belum Lengkap')->after('progres_pembangunan');
        });

        // 3. Update users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('marketing')->after('password');
        });

        // 4. Create legal_documents table
        Schema::create('legal_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->string('document_name');
            $table->string('file_path');
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // 5. Create progress_photos table
        Schema::create('progress_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->string('file_path');
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_photos');
        Schema::dropIfExists('legal_documents');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['progres_pembangunan', 'status_legalitas']);
            $table->renameColumn('status_penjualan', 'sales_status');
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->renameColumn('nama_blok', 'name');
        });
    }
};
