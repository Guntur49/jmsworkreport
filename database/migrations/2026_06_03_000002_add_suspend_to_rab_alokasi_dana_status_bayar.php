<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add Suspend value to the status_bayar enum in rab_alokasi_dana.
        DB::statement("ALTER TABLE `rab_alokasi_dana` MODIFY `status_bayar` ENUM('Belum Terbayar','Terbayar','URGENT','Suspend') NOT NULL DEFAULT 'Belum Terbayar';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `rab_alokasi_dana` MODIFY `status_bayar` ENUM('Belum Terbayar','Terbayar','URGENT') NOT NULL DEFAULT 'Belum Terbayar';");
    }
};
