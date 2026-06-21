<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $fillable = [
        'user_id', 'nis', 'nama', 'nisn', 'kelas', 'jurusan', 'no_absen',
        'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat',
        'agama', 'no_hp', 'email', 'nama_ayah', 'nama_ibu',
        'pekerjaan_ayah', 'pekerjaan_ibu', 'tanggal_masuk',
        'status_siswa', 'foto_url', 'telepon_orangtua',
        'foto', 'total_poin', 'ranking', 'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'mata_pelajaran' => 'array',
        'kelas_ampu' => 'array',
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
