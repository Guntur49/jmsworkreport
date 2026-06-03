<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_table_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rs'); // Mengunci data per Rumah Sakit
            $table->string('jenis_pekerjaan'); // Contoh: Renovasi Ruang HD
            
            // Menyimpan format waktu
            $table->integer('tahun'); // Contoh: 2026
            $table->integer('bulan'); // Nilai 1 - 12 (Contoh: 5 untuk Mei)
            $table->integer('minggu'); // Nilai 1 - 4 (Minggu ke-1 sampai ke-4)
            
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_table_pekerjaan');
    }
};