<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->timestamp('tanggal_akhir_progres')->nullable()->after('status_legalitas');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('tanggal_akhir_progres');
        });
    }
};