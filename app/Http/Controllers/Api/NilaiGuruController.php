<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiGuruController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $guru = $user->guru;

        if (!$guru) {
            return response()->json(['success' => false, 'message' => 'D            return response()->json(['success' => false, 'message' => 'D            return response()->json(['success' => false, 'message' => 'D            return response()->json(['success' => false, 'message' => 'D            return response()->json(['success' => false, 'message' => 'D            return response()->json(['success' => false, 'message' => 'D            return response()->json(['success' => false, 'message' => 'D            return response()->json(['success' => false, 'message' => 'D          c|m            return response()->json(['success' => fal',
            'tanggal' => 'required|date',
        ]);

        $nilai = Nilai::create([
            'siswa_id' => $request->siswa_id,
            'guru_id' => $guru->id,
            'mapel' => $request->mapel,
            'nilai' => $request->nilai,
            'tipe' => $request->tipe,
            'tanggal' => $request->tanggal,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil ditambahkan',
            'data' => $nilai,
        ], 201);
    }
}
