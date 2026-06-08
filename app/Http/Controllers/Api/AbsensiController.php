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
}
