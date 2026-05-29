<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuKas extends Model
{
    protected $table = 'buku_kas';
    protected $fillable = [
        'tanggal', 'keterangan', 'jenis', 'kategori', 'nominal'
    ];
}
