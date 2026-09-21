<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'tool_type')) {
                $table->enum('tool_type', [
                    'general',
                    'cutting_tool',
                    'measuring_tool',
                    'dies_mold',
                    'jig_fixture'
                ])->default('general')->after('type');
            }
            if (!Schema::hasColumn('items', 'calibration_due_date')) {
                $table->date('calibration_due_date')->nullable()->after('tool_type');
            }
            if (!Schema::hasColumn('items', 'calibration_status')) {
                $table->enum('calibration_status', [
                    'calibrated',
                    'due_soon',
                    'expired',
                    'not_applicable'
                ])->default('not_applicable')->after('calibration_due_date');
            }
            if (!Schema::hasColumn('items', 'calibration_certificate_number')) {
                $table->string('calibration_certificate_number')->nullable()->after('calibration_status');
            }
            if (!Schema::hasColumn('items', 'calibration_notes')) {
                $table->text('calibration_notes')->nullable()->after('calibration_certificate_number');
            }
            if (!Schema::hasColumn('items', 'tool_life_hours')) {
                $table->decimal('tool_life_hours', 8, 2)->default(0)->after('calibration_notes');
            }
            if (!Schema::hasColumn('items', 'max_tool_life_hours')) {
                $table->decimal('max_tool_life_hours', 8, 2)->nullable()->after('tool_life_hours');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'tool_type',
                'calibration_due_date',
                'calibration_status',
                'calibration_certificate_number',
                'calibration_notes',
                'tool_life_hours',
                'max_tool_life_hours'
            ]);
        });
    }
};
