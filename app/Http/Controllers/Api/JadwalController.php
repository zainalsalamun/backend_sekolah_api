<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $jadwal = Jadwal::where('kelas', $siswa->kelas)
            ->orderByRaw("CASE WHEN hari='Senin' THEN 1 WHEN hari='Selasa' THEN 2 WHEN hari='Rabu' THEN 3 WHEN hari='Kamis' THEN 4 WHEN hari='Jumat' THEN 5 ELSE 6 END")
            ->orderBy('jam_mulai')
            ->get();

        return response()->json(['success' => true, 'data' => $jadwal]);
    }
}
