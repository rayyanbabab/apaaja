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
        // 1. Tambah atribut K3 pada tabel items
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'safety_risk_level')) {
                $table->enum('safety_risk_level', ['low', 'medium', 'high'])->default('low')->after('max_tool_life_hours');
            }
            if (!Schema::hasColumn('items', 'required_apd')) {
                $table->json('required_apd')->nullable()->after('safety_risk_level');
            }
            if (!Schema::hasColumn('items', 'safety_instruction')) {
                $table->text('safety_instruction')->nullable()->after('required_apd');
            }
            if (!Schema::hasColumn('items', 'k3_quiz_required')) {
                $table->boolean('k3_quiz_required')->default(false)->after('safety_instruction');
            }
        });

        // 2. Tambah kolom clearance & verifikasi APD pada borrowing_requests
        Schema::table('borrowing_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('borrowing_requests', 'safety_agreed_at')) {
                $table->dateTime('safety_agreed_at')->nullable()->after('kondisi_pinjam');
            }
            if (!Schema::hasColumn('borrowing_requests', 'safety_apd_checklist')) {
                $table->json('safety_apd_checklist')->nullable()->after('safety_agreed_at');
            }
            if (!Schema::hasColumn('borrowing_requests', 'safety_verified_by')) {
                $table->unsignedBigInteger('safety_verified_by')->nullable()->after('safety_apd_checklist');
                $table->foreign('safety_verified_by')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('borrowing_requests', 'safety_verified_at')) {
                $table->dateTime('safety_verified_at')->nullable()->after('safety_verified_by');
            }
        });

        // 3. Buat tabel pencatatan insiden & pelanggaran K3 laboratorium
        if (!Schema::hasTable('safety_incidents')) {
            Schema::create('safety_incidents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('item_id')->nullable();
                $table->unsignedBigInteger('borrowing_request_id')->nullable();
                $table->enum('incident_type', ['minor_injury', 'near_miss', 'apd_violation', 'sop_violation', 'tool_misuse'])->default('apd_violation');
                $table->date('incident_date');
                $table->string('location')->nullable();
                $table->text('description');
                $table->text('action_taken')->nullable();
                $table->integer('penalty_days')->default(0);
                $table->unsignedBigInteger('reported_by');
                $table->enum('status', ['investigating', 'resolved', 'closed'])->default('resolved');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                $table->foreign('item_id')->references('id')->on('items')->nullOnDelete();
                $table->foreign('borrowing_request_id')->references('id')->on('borrowing_requests')->nullOnDelete();
                $table->foreign('reported_by')->references('id')->on('users')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safety_incidents');

        Schema::table('borrowing_requests', function (Blueprint $table) {
            $table->dropForeign(['safety_verified_by']);
            $table->dropColumn([
                'safety_agreed_at',
                'safety_apd_checklist',
                'safety_verified_by',
                'safety_verified_at'
            ]);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'safety_risk_level',
                'required_apd',
                'safety_instruction',
                'k3_quiz_required'
            ]);
        });
    }
};
