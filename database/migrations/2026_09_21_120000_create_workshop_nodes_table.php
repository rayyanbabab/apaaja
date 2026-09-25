<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workshop_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['machine', 'rack', 'cabinet', 'workbench', 'safety_kiosk', 'logistics_bay'])->default('rack');
            $table->enum('zone', ['machining', 'tool_crib', 'assembly', 'safety', 'logistics'])->default('tool_crib');
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            
            // Koordinat persentase (0.00 - 100.00%) untuk kanvas responsif
            $table->decimal('pos_x', 6, 2)->default(10.00);
            $table->decimal('pos_y', 6, 2)->default(10.00);
            $table->decimal('width', 6, 2)->default(14.00);
            $table->decimal('height', 6, 2)->default(11.00);
            $table->integer('rotation')->default(0); // 0, 90, 180, 270
            
            // Visual styling
            $table->string('icon', 50)->default('rack');
            $table->string('color_theme', 30)->default('blue');
            $table->text('description')->nullable();
            $table->string('status_override', 30)->nullable(); // manual override jika ada: operational, maintenance, offline
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshop_nodes');
    }
};
