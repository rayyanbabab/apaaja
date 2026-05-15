<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $existing = DB::table('settings')->pluck('key')->toArray();

        $defaults = [
            [
                'key'         => 'low_stock_threshold',
                'value'       => '5',
                'type'        => 'integer',
                'label'       => 'Batas Stok Minimum',
                'description' => 'Jumlah stok minimum sebelum item dianggap "stok rendah" dan notifikasi dikirim ke admin.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'enable_low_stock_alert',
                'value'       => '1',
                'type'        => 'boolean',
                'label'       => 'Peringatan Stok Minimum',
                'description' => 'Kirim notifikasi ke admin ketika stok item mencapai batas minimum.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        foreach ($defaults as $row) {
            if (! in_array($row['key'], $existing)) {
                DB::table('settings')->insert($row);
            } else {
                // Backfill type/label/description for settings that were created without them
                DB::table('settings')->where('key', $row['key'])->update([
                    'type'        => $row['type'],
                    'label'       => $row['label'],
                    'description' => $row['description'],
                    'updated_at'  => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'low_stock_threshold',
            'enable_low_stock_alert',
        ])->delete();
    }
};
