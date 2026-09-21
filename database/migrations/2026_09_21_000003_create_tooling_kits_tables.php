<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tooling_kits')) {
            Schema::create('tooling_kits', function (Blueprint $table) {
                $table->id();
                $table->string('kode')->unique();
                $table->string('nama');
                $table->string('target_machine')->nullable(); // Mesin Bubut, Milling, Stamping, dll.
                $table->text('deskripsi')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tooling_kit_items')) {
            Schema::create('tooling_kit_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tooling_kit_id')->constrained('tooling_kits')->cascadeOnDelete();
                $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
                $table->integer('jumlah')->default(1);
                $table->string('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tooling_kit_items');
        Schema::dropIfExists('tooling_kits');
    }
};
