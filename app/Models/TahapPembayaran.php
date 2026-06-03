<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahapPembayaran extends Model
{
    use HasFactory;

    protected $table = 'tahap_pembayaran';

    protected $fillable = [
        'rumahsakit',
        'nama_rs',
        'net_payment',
        'tahap_ke',
        'nominal_sebelum_pajak',
        'pph23',
        'pph_pasal4',
        'status_bayar',
    ];
}