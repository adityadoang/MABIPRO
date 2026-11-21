<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            if (! Schema::hasColumn('units', 'lebar_tanah')) {
                $table->decimal('lebar_tanah', 10, 2)->nullable()->after('luas_tanah');
            }
            if (! Schema::hasColumn('units', 'lebar_bangunan')) {
                $table->decimal('lebar_bangunan', 10, 2)->nullable()->after('luas_bangunan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('units', 'lebar_tanah')) {
                $columns[] = 'lebar_tanah';
            }
            if (Schema::hasColumn('units', 'lebar_bangunan')) {
                $columns[] = 'lebar_bangunan';
            }
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
