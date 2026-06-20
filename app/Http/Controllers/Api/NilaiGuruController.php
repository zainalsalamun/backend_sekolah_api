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
            return response()->json(['success' => false, 'message' => 'Data guru tidak ditemukan'], 404);
        }

        $nilai = Nilai::where('guru_id', $guru->id)
            ->with('siswa')
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $nilai,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $guru = $user->guru;

        if (!$guru) {
            return response()->json(['success' => false, 'message' => 'Data guru tidak ditemukan'], 404);
        }

        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'mapel' => 'required|string',
            'nilai' => 'required|numeric|min:0|max:100',
            'tipe' => 'required|string',
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
