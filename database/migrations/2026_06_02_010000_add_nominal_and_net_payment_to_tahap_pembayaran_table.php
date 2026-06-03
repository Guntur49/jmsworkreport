<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tahap_pembayaran', function (Blueprint $table) {
            if (!Schema::hasColumn('tahap_pembayaran', 'nominal_sebelum_pajak')) {
                $table->bigInteger('nominal_sebelum_pajak')->nullable()->default(0)->after('tahap_ke');
            }
            if (!Schema::hasColumn('tahap_pembayaran', 'net_payment')) {
                $table->bigInteger('net_payment')->nullable()->default(0)->after('pph_pasal4');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tahap_pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('tahap_pembayaran', 'net_payment')) {
                $table->dropColumn('net_payment');
            }
            if (Schema::hasColumn('tahap_pembayaran', 'nominal_sebelum_pajak')) {
                $table->dropColumn('nominal_sebelum_pajak');
            }
        });
    }
};
