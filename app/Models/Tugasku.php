<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tugasku extends Model
{
    protected $fillable = [
        'tugas_id', 'siswa_id', 'file_jawaban', 'catatan',
        'tanggal_pengumpulan', 'status', 'nilai',
    ];

    protected $table = 'tugaskus';

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
