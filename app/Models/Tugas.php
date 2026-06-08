<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tugas extends Model
{
    protected $fillable = [
        'guru_id', 'judul', 'deskripsi', 'mata_pelajaran',
        'kelas', 'tanggal_pemberian', 'tenggat', 'file',
        'status',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}
