<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Unit Rumah
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('block_name'); 
            $table->string('unit_number'); 
            $table->string('status_legalitas')->default('Belum Lengkap');
            $table->timestamps();
        });

        // Tabel Dokumen Legalitas (FR-010)
        Schema::create('legal_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->string('document_name'); 
            $table->string('file_path'); 
            // Dibuat nullable dulu agar tidak error saat testing tanpa login
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_documents');
        Schema::dropIfExists('units');
    }
};