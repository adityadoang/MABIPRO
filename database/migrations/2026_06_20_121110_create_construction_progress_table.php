<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('construction_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->enum('tahap', [
                'Persiapan Lahan',
                'Pondasi',
                'Struktur & Dinding',
                'Pengecatan',
                'Finishing',
                'Serah Terima'
            ]);
            $table->integer('persentase')->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['unit_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('construction_progress');
    }
};