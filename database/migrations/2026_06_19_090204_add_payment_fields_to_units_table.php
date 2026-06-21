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
        Schema::table('units', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
            $table->integer('kpr_duration_months')->nullable();
            $table->decimal('amount_paid', 15, 2)->nullable();
            $table->string('payment_proof_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'kpr_duration_months', 'amount_paid', 'payment_proof_path']);
        });
    }
};
