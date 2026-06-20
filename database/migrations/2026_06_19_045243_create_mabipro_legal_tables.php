<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Blok
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_blok');
            $table->timestamps();
        });

        // Tabel Unit Rumah
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained('blocks')->onDelete('cascade');
            $table->string('unit_number'); 
            $table->enum('status_penjualan', ['Belum Terjual', 'Sudah DP', 'Terjual'])->default('Belum Terjual');
            $table->integer('progres_pembangunan')->default(0);
            $table->string('status_legalitas')->default('Belum Lengkap');
            $table->string('payment_method')->nullable();
            $table->integer('kpr_duration_months')->nullable();
            $table->decimal('amount_paid', 15, 2)->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->decimal('harga_unit', 15, 2)->nullable();
            $table->string('kpr_type')->nullable();
            $table->string('bank_name')->nullable();
            $table->date('akad_date')->nullable();
            $table->decimal('dp_amount', 15, 2)->nullable();
            $table->decimal('dp_percentage', 5, 2)->nullable();
            $table->decimal('pokok_kredit', 15, 2)->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->string('interest_type')->nullable();
            $table->decimal('monthly_installment', 15, 2)->nullable();
            $table->timestamps();
        });

        // Tabel Dokumen Legalitas (FR-010)
        Schema::create('legal_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->string('document_name'); 
            $table->string('file_path'); 
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Tabel Progress Photos
        Schema::create('progress_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->string('file_path');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_photos');
        Schema::dropIfExists('legal_documents');
        Schema::dropIfExists('units');
        Schema::dropIfExists('blocks');
    }
};