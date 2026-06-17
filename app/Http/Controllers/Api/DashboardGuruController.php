<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Tugas;
use Illuminate\Http\Request;

class DashboardGuruController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $guru = $user->guru;

        if (!$guru) {
            return response()->json(['success' => false, 'message' => 'Data guru tidak ditemukan'], 404);
        }

        // Capitalize first letter to match DB format ("Senin", "Selasa", etc.)
        $hariIni = ucfirst(now()->locale('id')->dayName);
        // jadwals.guru is text (nama guru), not FK
        $jadwalHariIni = Jadwal::where('guru', $guru->nama)
            ->where('hari', $hariIni)
            ->get();

        $totalSiswa = Siswa::count();
        $tugasAktif = Tugas::where('guru_id', $guru->id)->where('status', 'aktif')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'guru' => $guru,
                'jadwal_hari_ini' => $jadwalHariIni,
                'total_siswa' => $totalSiswa,
                'tugas_aktif' => $tugasAktif,
            ],
        ]);
    }
}
