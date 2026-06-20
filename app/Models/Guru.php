<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    protected $fillable = [
        'user_id', 'nama', 'nip', 'mata_pelajaran',
        'telepon', 'foto', 'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // jadwals uses text 'guru' field (nama), not guru_id FK

    public function tugass(): HasMany
    {
        return $this->hasMany(Tugas::class);
    }

    public function pengumumen(): HasMany
    {
        return $this->hasMany(Pengumuman::class);
    }

    public function catatanSiswas(): HasMany
    {
        return $this->hasMany(CatatanSiswa::class);
    }
}
