<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'kode')) {
                $table->string('kode')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('items', 'stok_total')) {
                $table->integer('stok_total')->default(0)->after('category_id');
            }
            if (!Schema::hasColumn('items', 'stok_reguler')) {
                $table->integer('stok_reguler')->default(0)->after('stok_total');
            }
            if (!Schema::hasColumn('items', 'stok_peminjaman')) {
                $table->integer('stok_peminjaman')->default(0)->after('stok_reguler');
            }
            if (!Schema::hasColumn('items', 'harga')) {
                $table->integer('harga')->default(0)->after('stok_peminjaman');
            }
            if (!Schema::hasColumn('items', 'gambar')) {
                $table->string('gambar')->nullable()->after('harga');
            }
            if (!Schema::hasColumn('items', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('gambar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'kode', 'stok_total', 'stok_reguler',
                'stok_peminjaman', 'harga', 'gambar', 'keterangan',
            ]);
        });
    }
};
