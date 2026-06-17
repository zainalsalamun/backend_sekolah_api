<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $semester = $request->get('semester', 'Ganjil');
        $tahunAjaran = $request->get('tahun_ajaran', '2025/2026');

        $nilaiRaw = Nilai::where('siswa_id', $siswa->id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get();

        // Group by mapel and aggregate
        $grouped = $nilaiRaw->groupBy('mapel');
        $result = [];

        foreach ($grouped as $mapel => $items) {
            $tugas = $items->where('jenis', 'Tugas')->pluck('nilai')->toArray();
            $uts = $items->where('jenis', 'UTS')->first();
            $uas = $items->where('jenis', 'UAS')->first();

            $allValues = $items->pluck('nilai')->toArray();
            $rata = count($allValues) > 0 ? (int) round(array_sum($allValues) / count($allValues)) : 0;

            $result[] = [
                'nama' => $mapel,
                'kkm' => 75,
                'rata' => $rata,
                'tugas' => $tugas,
                'uts' => $uts ? $uts->nilai : 0,
                'uas' => $uas ? $uas->nilai : 0,
            ];
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    public function riwayat(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $nilai = Nilai::where('siswa_id', $siswa->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'mapel' => $n->mapel,
                    'jenis' => $n->jenis,
                    'nilai' => $n->nilai,
                    'semester' => $n->semester,
                    'tahun_ajaran' => $n->tahun_ajaran,
                    'tanggal' => $n->created_at ? $n->created_at->format('Y-m-d') : null,
                ];
            });

        return response()->json(['success' => true, 'data' => $nilai]);
    }
}
