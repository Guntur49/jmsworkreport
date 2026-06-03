<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TABEL UTAMA RAB
        Schema::create('rab_pra_operasional', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rs'); // Mengunci data per Rumah Sakit aktif
            $table->string('no_kontrak');
            $table->date('tgl_kontrak');
            $table->integer('jumlah_mesin');
            $table->bigInteger('dana_operasional_per_mesin');
            $table->bigInteger('total_dana_operasional'); // Hasil kali mesin x dana
            $table->integer('jumlah_mesin_cadangan'); // Hanya ditampilkan
            $table->timestamp('created_at')->useCurrent();
        });

        // 2. TABEL RINCIAN ALOKASI DANA PER TAHAP
        Schema::create('rab_alokasi_dana', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rab_id'); // Relasi ke tabel utama
            $table->integer('tahap'); // Nilai: 1, 2, atau 3
            $table->date('jatuh_tempo_tahap'); // Tanggal otomatis dari sistem
            $table->string('penerima_alokasi');
            $table->text('deskripsi');
            $table->bigInteger('nominal');
            
            // Foreign key constraints
            $table->foreign('rab_id')->references('id')->on('rab_pra_operasional')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rab_alokasi_dana');
        Schema::dropIfExists('rab_pra_operasional');
    }
};