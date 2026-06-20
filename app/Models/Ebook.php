<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'pengarang',
        'mata_pelajaran',
        'deskripsi',
        'gambar',
        'file_url',
        'halaman',
        'ukuran',
        'rating',
        'jumlah_download',
        'status',
    ];
}
