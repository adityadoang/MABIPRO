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
        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->date('installment_month');          // Tanggal awal bulan, e.g. 2026-01-01
            $table->boolean('is_paid')->default(false);
            $table->decimal('amount_paid', 15, 2)->nullable();  // Nominal aktual yang masuk
            $table->text('note')->nullable();           // Catatan opsional
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('paid_at')->nullable();   // Kapan dicatat lunas
            $table->timestamps();

            $table->unique(['unit_id', 'installment_month']); // Satu record per bulan per unit
            $table->index(['unit_id', 'installment_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_payments');
    }
};
