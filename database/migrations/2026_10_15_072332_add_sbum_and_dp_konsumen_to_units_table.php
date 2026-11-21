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
            if (!Schema::hasColumn('units', 'dp_konsumen_amount')) {
                $table->decimal('dp_konsumen_amount', 15, 2)->nullable()->after('dp_percentage');
            }
            if (!Schema::hasColumn('units', 'dp_konsumen_percentage')) {
                $table->decimal('dp_konsumen_percentage', 5, 2)->nullable()->after('dp_konsumen_amount');
            }
            if (!Schema::hasColumn('units', 'sbum_amount')) {
                $table->decimal('sbum_amount', 15, 2)->nullable()->after('dp_konsumen_percentage');
            }
            if (!Schema::hasColumn('units', 'sbum_percentage')) {
                $table->decimal('sbum_percentage', 5, 2)->nullable()->after('sbum_amount');
            }
            if (!Schema::hasColumn('units', 'sbum_status')) {
                $table->string('sbum_status', 50)->nullable()->default('Belum Cair')->after('sbum_percentage');
            }
            if (!Schema::hasColumn('units', 'sbum_cair_date')) {
                $table->date('sbum_cair_date')->nullable()->after('sbum_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn([
                'dp_konsumen_amount',
                'dp_konsumen_percentage',
                'sbum_amount',
                'sbum_percentage',
                'sbum_status',
                'sbum_cair_date',
            ]);
        });
    }
};
