<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('progress_photos', function (Blueprint $table) {
            $table->foreignId('progress_id')
                  ->after('id')
                  ->nullable()
                  ->constrained('construction_progress')
                  ->onDelete('cascade');
            
            $table->string('keterangan')->nullable()->after('file_path');
        });
    }

    public function down(): void
    {
        Schema::table('progress_photos', function (Blueprint $table) {
            $table->dropForeign(['progress_id']);
            $table->dropColumn(['progress_id', 'keterangan']);
        });
    }
};