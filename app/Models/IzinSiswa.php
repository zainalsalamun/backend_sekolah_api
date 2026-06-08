<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IzinSiswa extends Model
{
    protected $fillable = [
        'siswa_id', 'tanggal', 'alasan', 'bukti', 'status',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
