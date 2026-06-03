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
        Schema::table('rab_alokasi_dana', function (Blueprint $table) {
            // Menambahkan komponen finansial pajak setelah kolom nominal awal Anda
            // Menggunakan bigInteger agar aman dari batasan angka integer biasa
            $table->bigInteger('dpp')->nullable()->default(0)->after('nominal');
            $table->bigInteger('ppn')->nullable()->default(0)->after('dpp');
            $table->bigInteger('pph23')->nullable()->default(0)->after('ppn');
            $table->bigInteger('pph_pasal4')->nullable()->default(0)->after('pph23');
            $table->bigInteger('net_payment')->nullable()->default(0)->after('pph_pasal4');
            
            // Kolom Dokumen Invoice & Bukti Fisik
            $table->string('no_invoice')->nullable()->after('net_payment');
            $table->string('file_invoice')->nullable()->after('no_invoice');
            
            // Kolom Dokumen Perpajakan
            $table->string('no_faktur_pajak')->nullable()->after('file_invoice');
            $table->string('file_faktur_pajak')->nullable()->after('no_faktur_pajak');
            $table->string('no_bukti_potong')->nullable()->after('file_faktur_pajak');
            $table->string('file_bukti_potong')->nullable()->after('no_bukti_potong');

            // Status Kontrol Pembayaran
            $table->enum('status_bayar', ['Belum Terbayar', 'Terbayar', 'URGENT'])->default('Belum Terbayar')->after('file_bukti_potong');
            $table->date('tanggal_realisasi')->nullable()->after('status_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rab_alokasi_dana', function (Blueprint $table) {
            // Drop semua kolom jika dilakukan rollback migration
            $table->dropColumn([
                'dpp', 'ppn', 'pph23', 'pph_pasal4', 'net_payment',
                'no_invoice', 'file_invoice',
                'no_faktur_pajak', 'file_faktur_pajak',
                'no_bukti_potong', 'file_bukti_potong',
                'status_bayar', 'tanggal_realisasi'
            ]);
        });
    }
};