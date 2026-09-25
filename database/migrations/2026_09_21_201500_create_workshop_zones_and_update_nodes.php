<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat tabel workshop_zones jika belum ada
        if (!Schema::hasTable('workshop_zones')) {
            Schema::create('workshop_zones', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('color')->default('blue'); // blue, emerald, cyan, rose, slate, amber, purple, indigo
                $table->decimal('pos_x', 6, 2)->default(10.00);
                $table->decimal('pos_y', 6, 2)->default(10.00);
                $table->decimal('width', 6, 2)->default(30.00);
                $table->decimal('height', 6, 2)->default(30.00);
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 2. Ubah kolom zone pada workshop_nodes dari enum menjadi varchar agar mendukung zona kustom
        try {
            DB::statement('ALTER TABLE workshop_nodes MODIFY COLUMN zone VARCHAR(100) NOT NULL DEFAULT "tool_crib"');
        } catch (\Throwable $e) {
            // fallback via schema jika statement gagal
            Schema::table('workshop_nodes', function (Blueprint $table) {
                $table->string('zone', 100)->default('tool_crib')->change();
            });
        }

        // 3. Seed default 5 zones
        if (DB::table('workshop_zones')->count() === 0) {
            DB::table('workshop_zones')->insert([
                [
                    'name'        => 'Zona A: Machining & Fabrikasi Presisi',
                    'code'        => 'machining',
                    'color'       => 'blue',
                    'pos_x'       => 4.00,
                    'pos_y'       => 6.00,
                    'width'       => 44.00,
                    'height'      => 46.00,
                    'description' => 'Area pemesinan berat: Mesin Bubut, Mesin Milling CNC, dan Bor Duduk presisi.',
                    'is_active'   => true,
                    'sort_order'  => 1,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Zona B: Tool Crib & Rak Penyimpanan Utama',
                    'code'        => 'tool_crib',
                    'color'       => 'emerald',
                    'pos_x'       => 50.00,
                    'pos_y'       => 6.00,
                    'width'       => 46.00,
                    'height'      => 46.00,
                    'description' => 'Pusat penyimpanan perkakas mekanik, alat ukur presisi, lemari peminjaman, dan fastener.',
                    'is_active'   => true,
                    'sort_order'  => 2,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Zona C: Meja Perakitan & Stasiun Solder',
                    'code'        => 'assembly',
                    'color'       => 'cyan',
                    'pos_x'       => 4.00,
                    'pos_y'       => 56.00,
                    'width'       => 44.00,
                    'height'      => 38.00,
                    'description' => 'Meja kerja assembly mekanik dan soldering station terpadu.',
                    'is_active'   => true,
                    'sort_order'  => 3,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Zona D: K3 & APD',
                    'code'        => 'safety',
                    'color'       => 'rose',
                    'pos_x'       => 50.00,
                    'pos_y'       => 56.00,
                    'width'       => 22.00,
                    'height'      => 38.00,
                    'description' => 'Kios keselamatan kerja, verifikasi APD, dan tabung APAR/P3K.',
                    'is_active'   => true,
                    'sort_order'  => 4,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Zona E: Logistik Material & Karantina',
                    'code'        => 'logistics',
                    'color'       => 'slate',
                    'pos_x'       => 74.00,
                    'pos_y'       => 56.00,
                    'width'       => 22.00,
                    'height'      => 38.00,
                    'description' => 'Area logistik inbound/outbound dan transit material.',
                    'is_active'   => true,
                    'sort_order'  => 5,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshop_zones');
    }
};
