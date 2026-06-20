<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'judul', 'konten', 'kategori', 'gambar', 'penulis', 'tanggal', 'views', 'status',
    ];
}
