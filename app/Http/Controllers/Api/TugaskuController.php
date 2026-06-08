<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tugasku;
use Illuminate\Http\Request;

class TugaskuController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $tugasku = Tugasku::where('siswa_id', $siswa->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $tugasku]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'status' => 'required|string',
            'file' => 'nullable|string',
        ]);

        $tugasku = Tugasku::create([
            'siswa_id' => $siswa->id,
            'tugas_id' => $request->tugas_id,
            'status' => $request->status,
            'file' => $request->file,
            'dikumpulkan_pada' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dikumpulkan',
            'data' => $tugasku,
        ], 201);
    }
}
