<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanSiswa extends Model
{
    protected $fillable = [
        'siswa_id', 'guru_id', 'catatan', 'tanggal',
    ];

    protected $table = 'catatan_siswas';

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}
