<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    protected $fillable = [
        'guru_id', 'mata_pelajaran', 'hari', 'jam_mulai',
        'jam_selesai', 'ruangan', 'kelas', 'tipe',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}
