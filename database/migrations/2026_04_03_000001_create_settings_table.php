<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean
            $table->string('label')->nullable();        // human-readable label
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        DB::table('settings')->insert([
            [
                'key' => 'max_borrow_days',
                'value' => '7',
                'type' => 'integer',
                'label' => 'Maksimal Hari Peminjaman',
                'description' => 'Jumlah hari maksimal untuk setiap peminjaman barang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_items_per_user',
                'value' => '3',
                'type' => 'integer',
                'label' => 'Maksimal Item per User',
                'description' => 'Jumlah maksimal item yang boleh dipinjam user secara bersamaan (pending + approved).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'enable_overdue_reminder',
                'value' => '1',
                'type' => 'boolean',
                'label' => 'Aktifkan Pengingat Terlambat',
                'description' => 'Kirim notifikasi ke user ketika barang belum dikembalikan melewati tanggal rencana.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'enable_pending_reminder',
                'value' => '1',
                'type' => 'boolean',
                'label' => 'Aktifkan Pengingat Pengajuan Pending',
                'description' => 'Kirim notifikasi ke admin ketika ada pengajuan yang belum disetujui lebih dari 1 hari.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
