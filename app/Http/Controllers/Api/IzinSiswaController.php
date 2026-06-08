<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IzinSiswa;
use Illuminate\Http\Request;

class IzinSiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $izin = IzinSiswa::where('siswa_id', $siswa->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $izin]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|string',
            'alasan' => 'required|string',
            'bukti' => 'nullable|string',
        ]);

        $izin = IzinSiswa::create([
            'siswa_id' => $siswa->id,
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'alasan' => $request->alasan,
            'bukti' => $request->bukti,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Izin berhasil diajukan',
            'data' => $izin,
        ], 201);
    }
}
