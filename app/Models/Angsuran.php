<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    protected $fillable = [
        'pinjaman_id', 'jatuh_tempo', 'tanggal_bayar', 'angsuran_ke', 'pokok',
        'bunga', 'biaya_admin', 'denda', 'status'
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }
}
