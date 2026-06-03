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
    Schema::create('tahap_pembayaran', function (Blueprint $table) {
        $table->id();
        $table->integer('tahap_ke'); // Menyimpan penanda Tahap 1, Tahap 2, atau Tahap 3
        $table->bigInteger('pph23')->default(0);
        $table->bigInteger('pph_pasal4')->default(0);
        $table->string('status_bayar')->default('Belum Terbayar');
        $table->timestamps();
    });

    // Opsional: Langsung isi data default untuk Tahap 1, 2, dan 3 agar baris hijau langsung muncul
    DB::table('tahap_pembayaran')->insert([
        ['tahap_ke' => 1, 'pph23' => 0, 'pph_pasal4' => 0, 'status_bayar' => 'Belum Terbayar', 'created_at' => now()],
        ['tahap_ke' => 2, 'pph23' => 0, 'pph_pasal4' => 0, 'status_bayar' => 'Belum Terbayar', 'created_at' => now()],
        ['tahap_ke' => 3, 'pph23' => 0, 'pph_pasal4' => 0, 'status_bayar' => 'Belum Terbayar', 'created_at' => now()],
    ]);
}
};
