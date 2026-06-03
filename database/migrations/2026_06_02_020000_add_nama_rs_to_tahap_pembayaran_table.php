<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tahap_pembayaran', function (Blueprint $table) {
            if (!Schema::hasColumn('tahap_pembayaran', 'nama_rs')) {
                $table->string('nama_rs')->nullable()->after('tahap_ke');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tahap_pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('tahap_pembayaran', 'nama_rs')) {
                $table->dropColumn('nama_rs');
            }
        });
    }
};
