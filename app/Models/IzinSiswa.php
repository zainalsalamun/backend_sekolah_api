<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IzinSiswa extends Model
{
    protected $fillable = [
        'siswa_id', 'tanggal', 'jenis', 'alasan', 'bukti', 'status', 'disetujui_oleh', 'catatan_guru',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
