<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $fillable = [
        'user_id', 'nama', 'nisn', 'kelas', 'jurusan',
        'tanggal_lahir', 'alamat', 'telepon_orangtua',
        'foto', 'total_poin', 'ranking', 'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function izinSiswas(): HasMany
    {
        return $this->hasMany(IzinSiswa::class);
    }

    public function poinSiswas(): HasMany
    {
        return $this->hasMany(PoinSiswa::class);
    }

    public function tugaskus(): HasMany
    {
        return $this->hasMany(Tugasku::class);
    }

    public function catatanSiswas(): HasMany
    {
        return $this->hasMany(CatatanSiswa::class);
    }
}
