<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'bin_rack')) {
                $table->string('bin_rack')->nullable()->after('location_id');
            }
            if (!Schema::hasColumn('items', 'lead_time_days')) {
                $table->integer('lead_time_days')->default(3)->after('bin_rack');
            }
            if (!Schema::hasColumn('items', 'daily_usage_rate')) {
                $table->decimal('daily_usage_rate', 8, 2)->default(1.00)->after('lead_time_days');
            }
            if (!Schema::hasColumn('items', 'safety_stock')) {
                $table->integer('safety_stock')->default(5)->after('daily_usage_rate');
            }
            if (!Schema::hasColumn('items', 'holding_cost')) {
                $table->integer('holding_cost')->default(5000)->after('safety_stock');
            }
            if (!Schema::hasColumn('items', 'order_cost')) {
                $table->integer('order_cost')->default(50000)->after('holding_cost');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'bin_rack',
                'lead_time_days',
                'daily_usage_rate',
                'safety_stock',
                'holding_cost',
                'order_cost'
            ]);
        });
    }
};
