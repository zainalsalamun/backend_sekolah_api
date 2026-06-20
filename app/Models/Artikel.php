<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [
        'judul',
        'kategori',
        'konten',
        'gambar',
        'penulis',
        'tanggal',
        'views',
        'status',
    ];
}
