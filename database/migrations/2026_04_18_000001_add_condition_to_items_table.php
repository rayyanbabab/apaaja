<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'kondisi')) {
                $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])
                      ->default('baik')
                      ->after('keterangan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('kondisi');
        });
    }
};
