<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->orderByDesc('tanggal')
            ->get();

        return response()->json(['success' => true, 'data' => $absensi]);
    }

    public function summary(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $semester = $request->get('semester', 'Ganjil');
        $tahunAjaran = $request->get('tahun_ajaran', '2025/2026');

        $query = Absensi::where('siswa_id', $siswa->id);

        // Filter by month if provided
        if ($request->has('bulan')) {
            $query->whereMonth('tanggal', $request->get('bulan'));
        }

        $total = $query->count();

        if ($total === 0) {
            return response()->json([
                'success' => true,
                'data' => [
                    'hadir' => 0,
                    'sakit' => 0,
                    'izin' => 0,
                    'alpha' => 0,
                    'persentase' => 0,
                ]
            ]);
        }

        $hadir = (clone $query)->where('status', 'Hadir')->count();
        $sakit = (clone $query)->where('status', 'Sakit')->count();
        $izin = (clone $query)->where('status', 'Izin')->count();
        $alpha = (clone $query)->where('status', 'Alpha')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpha' => $alpha,
                'persentase' => round(($hadir / $total) * 100, 1),
            ]
        ]);
    }

    public function riwayat(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->orderByDesc('tanggal')
            ->get()
            ->map(function ($a) {
                return [
                    'id' => $a->id,
                    'tanggal' => $a->tanggal ? $a->tanggal->format('Y-m-d') : null,
                    'hari' => $a->hari,
                    'status' => $a->status,
                    'mapel' => $a->mapel,
                    'jam' => $a->jam,
                    'keterangan' => $a->keterangan,
                ];
            });

        return response()->json(['success' => true, 'data' => $absensi]);
    }

    public function indexGuru(Request $request)
    {
        $user = $request->user();
        $guru = $user->guru;

        if (!$guru) {
            return response()->json(['success' => false, 'message' => 'Data guru tidak ditemukan'], 404);
        }

        $kelas = $request->get('kelas');
        $tanggal = $request->get('tanggal');

        $query = Absensi::query();

        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        } else {
            $query->whereDate('tanggal', now());
        }

        $query->orderByDesc('tanggal');

        $absensi = $query->get();

        return response()->json(['success' => true, 'data' => $absensi]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Sakit,Izin,Alpha',
            'mapel' => 'nullable|string',
            'jam' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'kelas' => 'nullable|string',
        ]);

        $absensi = Absensi::create([
            'siswa_id' => $request->siswa_id,
            'tanggal' => $request->tanggal,
            'hari' => \Carbon\Carbon::parse($request->tanggal)->locale('id')->dayName,
            'status' => $request->status,
            'mapel' => $request->mapel,
            'jam' => $request->jam,
            'keterangan' => $request->keterangan,
            'kelas' => $request->kelas,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan',
            'data' => $absensi,
        ], 201);
    }
}
