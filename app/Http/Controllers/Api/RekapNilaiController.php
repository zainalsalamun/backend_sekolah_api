<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use Illuminate\Http\Request;

class RekapNilaiController extends Controller
{
    public function index(Request $request)
    {
        $rekap = Nilai::selectRaw('siswa_id, mapel, AVG(nilai) as rata_nilai, COUNT(*) as jumlah')
            ->groupBy('siswa_id', 'mapel')
            ->get();

        return response()->json(['success' => true, 'data' => $rekap]);
    }
}
