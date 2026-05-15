<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');           // e.g. item.created, borrowing.approved
            $table->string('module');           // e.g. Inventory, Peminjaman, User
            $table->string('description');      // Human-readable summary
            $table->nullableMorphs('subject');  // subject_id + subject_type
            $table->json('properties')->nullable(); // Before/after snapshot
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['action', 'module']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
