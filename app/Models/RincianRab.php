<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RincianRab extends Model
{
    // Sesuaikan nama tabel Anda jika berbeda
    protected $table = 'rincian_rab'; 

    protected $fillable = [
        'rab_id', // foreign key lama Anda
        'penerima',
        'deskripsi',
        'nominal',
        
        // Kolom tambahan baru (Opsi 2)
        'dpp',
        'ppn',
        'pph23',
        'pph_pasal4',
        'net_payment',
        'transfer_to',
        'no_invoice',
        'file_invoice',
        'no_faktur_pajak',
        'file_faktur_pajak',
        'no_bukti_potong',
        'file_bukti_potong',
        'status_bayar',
        'tanggal_realisasi',
    ];
}