<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'pinjamans';
    protected $fillable = [
        'user_id', 'tanggal_pengajuan', 'tanggal_cair', 'nominal_pinjam', 'tenor',
        'bunga_nominal', 'biaya_admin', 'metode_potongan', 'nominal_cair_bersih', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function angsurans()
    {
        return $this->hasMany(Angsuran::class);
    }
}
