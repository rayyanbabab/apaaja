<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_request_id')->nullable()->constrained('borrowing_requests')->nullOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('inspector_id')->constrained('users')->cascadeOnDelete();

            // Image evidence
            $table->string('image_path')->nullable();
            $table->string('image_original_name')->nullable();

            // AI Analysis Results
            $table->enum('verdict', ['ok', 'minor_wear', 'damaged', 'critical'])->default('ok');
            $table->json('defect_types')->nullable();      // ['chip','crack','corrosion','wear']
            $table->unsignedTinyInteger('wear_percentage')->default(0);
            $table->unsignedTinyInteger('defect_confidence')->default(0); // 0-100
            $table->json('defect_regions')->nullable();    // [{x,y,w,h,type,confidence}]
            $table->json('scan_metrics')->nullable();      // edge_density, contrast_variance, etc.

            // Inspector notes
            $table->string('inspection_stage')->default('return'); // 'borrow' | 'return'
            $table->text('notes')->nullable();
            $table->string('scan_duration_ms')->nullable();

            // BAK (Berita Acara Kerusakan)
            $table->boolean('bak_issued')->default(false);
            $table->string('bak_number')->nullable()->unique();
            $table->timestamp('bak_issued_at')->nullable();

            // Auto-trigger maintenance
            $table->boolean('maintenance_triggered')->default(false);
            $table->foreignId('maintenance_id')->nullable()->constrained('maintenances')->nullOnDelete();

            $table->timestamps();

            $table->index(['item_id', 'verdict']);
            $table->index(['borrowing_request_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_inspections');
    }
};
