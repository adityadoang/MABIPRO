<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('generated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('file_path');
            $table->string('report_type')->default('progres_pembangunan');
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['unit_id', 'generated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};