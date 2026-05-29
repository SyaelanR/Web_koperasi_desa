<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    protected $fillable = [
        'user_id', 'tagihan_id', 'tanggal', 'jenis', 'nominal', 'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
