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
            if (!Schema::hasColumn('units', 'harga_unit')) {
                $table->decimal('harga_unit', 15, 2)->nullable()->after('payment_proof_path');
            }
            if (!Schema::hasColumn('units', 'kpr_type')) {
                $table->string('kpr_type')->nullable()->after('harga_unit');
            }
            if (!Schema::hasColumn('units', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('kpr_type');
            }
            if (!Schema::hasColumn('units', 'akad_date')) {
                $table->date('akad_date')->nullable()->after('bank_name');
            }

            // Down Payment
            if (!Schema::hasColumn('units', 'dp_amount')) {
                $table->decimal('dp_amount', 15, 2)->nullable()->after('akad_date');
            }
            if (!Schema::hasColumn('units', 'dp_percentage')) {
                $table->decimal('dp_percentage', 5, 2)->nullable()->after('dp_amount');
            }

            // Kredit
            if (!Schema::hasColumn('units', 'pokok_kredit')) {
                $table->decimal('pokok_kredit', 15, 2)->nullable()->after('dp_percentage');
            }
            if (!Schema::hasColumn('units', 'interest_rate')) {
                $table->decimal('interest_rate', 5, 2)->nullable()->after('pokok_kredit');
            }
            if (!Schema::hasColumn('units', 'interest_type')) {
                $table->string('interest_type')->nullable()->after('interest_rate');
            }

            // Hasil Kalkulasi
            if (!Schema::hasColumn('units', 'monthly_installment')) {
                $table->decimal('monthly_installment', 15, 2)->nullable()->after('interest_type');
            }
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
