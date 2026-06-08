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

        // Get today's schedule
        $hariIni = now()->locale('id')->dayName;
        $jadwalHariIni = Jadwal::where('kelas', $siswa->kelas)
            ->where('hari', ucfirst($hariIni))
            ->orderBy('jam_mulai')
            ->get();

        // Get recent grades
        $nilaiTerbaru = Nilai::where('siswa_id', $siswa->id)
            ->orderByDesc('tanggal')
            ->limit(5)
            ->get();

        // Count unread notifications
        $notifikasiBelumDibaca = Notifikasi::where('siswa_id', $siswa->id)
            ->where('dibaca', false)
            ->count();

        // Get attendance stats this month
        $absensiBulanIni = Absensi::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->get();

        // Get total poin
        $totalPoin = PoinSiswa::where('siswa_id', $siswa->id)->sum('poin');

        return response()->json([
            'success' => true,
            'data' => [
                'siswa' => $siswa,
                'jadwal_hari_ini' => $jadwalHariIni,
                'nilai_terbaru' => $nilaiTerbaru,
                'notifikasi_belum_dibaca' => $notifikasiBelumDibaca,
                'rekap_absensi' => [
                    'hadir' => $absensiBulanIni->where('status', 'Hadir')->count(),
                    'izin' => $absensiBulanIni->where('status', 'Izin')->count(),
                    'sakit' => $absensiBulanIni->where('status', 'Sakit')->count(),
                    'alpha' => $absensiBulanIni->where('status', 'Alpha')->count(),
                ],
                'total_poin' => (int) $totalPoin,
            ],
        ]);
    }
}
