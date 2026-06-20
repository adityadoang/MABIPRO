<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            // Info Unit
            $table->decimal('harga_unit', 15, 2)->nullable()->after('payment_proof_path');
            $table->string('kpr_type')->nullable()->after('harga_unit');         // subsidi / non_subsidi
            $table->string('bank_name')->nullable()->after('kpr_type');
            $table->date('akad_date')->nullable()->after('bank_name');

            // Down Payment
            $table->decimal('dp_amount', 15, 2)->nullable()->after('akad_date');
            $table->decimal('dp_percentage', 5, 2)->nullable()->after('dp_amount');

            // Kredit
            $table->decimal('pokok_kredit', 15, 2)->nullable()->after('dp_percentage');
            $table->decimal('interest_rate', 5, 2)->nullable()->after('pokok_kredit');  // % per tahun
            $table->string('interest_type')->nullable()->after('interest_rate');         // anuitas / flat

            // Hasil Kalkulasi
            $table->decimal('monthly_installment', 15, 2)->nullable()->after('interest_type');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn([
                'harga_unit', 'kpr_type', 'bank_name', 'akad_date',
                'dp_amount', 'dp_percentage',
                'pokok_kredit', 'interest_rate', 'interest_type',
                'monthly_installment',
            ]);
        });
    }
};
