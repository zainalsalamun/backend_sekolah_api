<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminuse Illuminuse Illuminuse Illuminuse Illuminuse Illu Controuse Illuminuse Illuminuse Illuexuse Illuminuse Illuminuse Illuminuse Illuminuse Illuminuse Illu Controuse Illuminuse Illuminuse Illuexuse Illuminuse Illuminuse Illuminuse Illuminuse Illuminuse Illu Controuse Illuminuse Illuminuse Illuexuse Illuminuse Illuminuse Illuminuse Illuminuse Illuminuse Illu Controuse Illuminuse Illuminuse Illuexuse Illuminuse Illuminuse Illuminuse Illuminuse Illuminuse Illu Controuse Illuminuse Illuminuse Illuexuse Illuminuse Illuminuse Illuminuse Illuminuse Illuminuse Illu Controuse Illuminuse Illuminuse Illuexuse Illuminuse Illuminuse Illuminuse Illuminuse Illuminuse Illu Controuse Illuminuse Illuminuse Illuexuse Illuminuse Illuminuse Illuminuse Illuminuse Illuminuse Illu Controuse Illuminuse Illuminuse Illuexuse IllumIlluse Illuminuse Illuminuse I

class RekapNilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $rekap = Nilai::where('siswa_id', $siswa->id)
            ->select('mapel', DB::raw('AVG(nilai) as rata_nilai'), DB::raw('COUNT(*) as jumlah_tugas'))
            ->groupBy('mapel')
            ->get();

        return response()->json(['success' => true, 'data' => $rekap]);
    }
}
