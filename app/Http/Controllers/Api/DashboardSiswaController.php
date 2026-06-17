<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Notifikasi;
use App\Models\PoinSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardSiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        // Get latest attendance status
        $absensiTerbaru = Absensi::where('siswa_id', $siswa->id)
            ->orderByDesc('tanggal')
            ->first();

        // Get average nilai
        $nilaiRataRata = Nilai::where('siswa_id', $siswa->id)->avg('nilai');

        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $siswa->nama,
                'kelas' => $siswa->kelas,
                'nis' => $siswa->nisn,
                'status_absensi' => $absensiTerbaru ? $absensiTerbaru->status : 'Belum Absen',
                'nilai_rata_rata' => round($nilaiRataRata ?? 0),
            ],
        ]);
    }
}
