<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PoinSiswa;
use Illuminate\Http\Request;

class PoinController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $riwayat = PoinSiswa::where('siswa_id', $siswa->id)
            ->orderByDesc('created_at')
            ->get();

        $totalPoin = PoinSiswa::where('siswa_id', $siswa->id)->sum('poin');

        return response()->json([
            'success' => true,
            'data' => [
                'total_poin' => (int) $totalPoin,
                'riwayat' => $riwayat,
            ],
        ]);
    }
}
