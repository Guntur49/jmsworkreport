<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // PENTING: Ganti 'tahap_pembayaran' jika nama tabel master termin Anda berbeda
        Schema::table('tahap_pembayaran', function (Blueprint $table) {
            $table->bigInteger('pph23')->nullable()->default(0)->after('status_bayar');
            $table->bigInteger('pph_pasal4')->nullable()->default(0)->after('pph23');
        });
    }

    public function down(): void
    {
        Schema::table('tahap_pembayaran', function (Blueprint $table) {
            $table->dropColumn(['pph23', 'pph_pasal4']);
        });
    }
};