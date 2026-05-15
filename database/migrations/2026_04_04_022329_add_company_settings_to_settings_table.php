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
                'key'         => 'company_name',
                'value'       => 'PT. ARTILIA',
                'type'        => 'string',
                'label'       => 'Nama Perusahaan',
                'description' => 'Nama perusahaan yang ditampilkan di laporan.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'company_tagline',
                'value'       => 'Inventory Management System',
                'type'        => 'string',
                'label'       => 'Tagline / Keterangan',
                'description' => 'Tagline atau keterangan singkat perusahaan.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'company_address',
                'value'       => 'Jl. Cipendeu No. 123, Jakarta Selatan',
                'type'        => 'string',
                'label'       => 'Alamat Perusahaan',
                'description' => 'Alamat lengkap perusahaan.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'company_phone',
                'value'       => '(021) 1234567',
                'type'        => 'string',
                'label'       => 'Nomor Telepon',
                'description' => 'Nomor telepon perusahaan.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'company_email',
                'value'       => 'info@artilia.com',
                'type'        => 'string',
                'label'       => 'Email Perusahaan',
                'description' => 'Alamat email resmi perusahaan.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'company_logo',
                'value'       => null,
                'type'        => 'string',
                'label'       => 'Logo Perusahaan',
                'description' => 'Path ke file logo perusahaan (diisi otomatis saat upload).',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        foreach ($defaults as $row) {
            if (! in_array($row['key'], $existing)) {
                DB::table('settings')->insert($row);
            }
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'company_name', 'company_tagline', 'company_address',
            'company_phone', 'company_email', 'company_logo',
        ])->delete();
    }
};
