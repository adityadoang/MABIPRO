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
            if (!Schema::hasColumn('units', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('units', 'kpr_duration_months')) {
                $table->integer('kpr_duration_months')->nullable();
            }
            if (!Schema::hasColumn('units', 'amount_paid')) {
                $table->decimal('amount_paid', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('units', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable();
            }
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
