<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 
        'penulis',
        'tahun_terbit',
        'stok',
        'kategori_id',
        'gambar',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

   
    public function bukus()
    {
        return $this->hasMany(Buku::class);
    }


    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
