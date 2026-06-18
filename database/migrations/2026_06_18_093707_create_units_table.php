<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained()->cascadeOnDelete();
            $table->string('unit_number'); // Contoh: A-01, B-12
            $table->enum('sales_status', ['Belum Terjual', 'Sudah DP', 'Terjual'])->default('Belum Terjual');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('units');
    }
};