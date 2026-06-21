<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoinSiswa extends Model
{
    protected $fillable = [
        'siswa_id', 'aktivitas', 'tipe', 'poin', 'tanggal',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
