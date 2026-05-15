<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->enum('status', ['in_repair', 'completed', 'scrapped'])->default('in_repair');
            $table->string('kondisi_masuk')->nullable(); // kondisi saat masuk servis
            $table->text('catatan')->nullable();         // catatan kerusakan
            $table->text('catatan_selesai')->nullable(); // catatan saat selesai
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['item_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
