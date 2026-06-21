<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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