<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowing_requests', function (Blueprint $table) {
            // BAP (Berita Acara Peminjaman) Digital Signature fields
            $table->string('bap_token', 64)->nullable()->unique()->after('completed_at');
            $table->text('signature_data')->nullable()->after('bap_token');          // Base64 PNG canvas data
            $table->string('signed_by_name')->nullable()->after('signature_data');  // Name printed under signature
            $table->timestamp('signed_at')->nullable()->after('signed_by_name');
            $table->string('bap_number')->nullable()->after('signed_at');           // e.g. BAP/2026/09/001
        });
    }

    public function down(): void
    {
        Schema::table('borrowing_requests', function (Blueprint $table) {
            $table->dropColumn(['bap_token', 'signature_data', 'signed_by_name', 'signed_at', 'bap_number']);
        });
    }
};
