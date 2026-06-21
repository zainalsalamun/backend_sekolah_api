<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    protected $fillable = [
        'hari', 'mapel', 'jam_mulai',
        'jam_selesai', 'guru', 'kelas', 'ruangan',
        'warna', 'jenis',
    ];

    // guru field is text (nama guru), not FK
}
