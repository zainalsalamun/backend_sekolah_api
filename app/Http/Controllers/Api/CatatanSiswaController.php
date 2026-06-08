<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatatanSiswa;
use Illuminate\Http\Request;

class CatatanSiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $catatan = CatatanSiswa::where('siswa_id', $siswa->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $catatan]);
    }

    public function show($id)
    {
        $catatan = CatatanSiswa::find($id);
        if (!$catatan) {
            return response()->json(['success' => false, 'message' => 'Catatan tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $catatan]);
    }
}
