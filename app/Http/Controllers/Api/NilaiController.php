<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $nilai = Nilai::where('siswa_id', $siswa->id)
            ->orderByDesc('tanggal')
            ->get();

        return response()->json(['success' => true, 'data' => $nilai]);
    }
}
