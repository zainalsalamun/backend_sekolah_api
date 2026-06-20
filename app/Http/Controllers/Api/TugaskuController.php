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

        $tugasku = Tugasku::with('tugas')
            ->where('siswa_id', $siswa->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $tugasku]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $tugasku = Tugasku::with('tugas')
            ->where('siswa_id', $siswa->id)
            ->where('id', $id)
            ->first();

        if (!$tugasku) {
            return response()->json(['success' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $tugasku]);
    }

    public function submit(Request $request, $id)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $request->validate([
            'status' => 'required|string',
            'file' => 'nullable|string',
        ]);

        $tugasku = Tugasku::where('siswa_id', $siswa->id)
            ->where('id', $id)
            ->first();

        if (!$tugasku) {
            return response()->json(['success' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }

        $tugasku->status = $request->status;
        $tugasku->file = $request->file;
        $tugasku->dikumpulkan_pada = now();
        $tugasku->save();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dikumpulkan',
            'data' => $tugasku,
        ]);
    }

    public function riwayat(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan'], 404);
        }

        $tugasku = Tugasku::with('tugas')
            ->where('siswa_id', $siswa->id)
            ->whereNotNull('dikumpulkan_pada')
            ->orderByDesc('dikumpulkan_pada')
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'tugas_id' => $t->tugas_id,
                    'judul' => $t->tugas ? $t->tugas->judul : '-',
                    'mapel' => $t->tugas ? $t->tugas->mapel : '-',
                    'status' => $t->status,
                    'nilai' => $t->nilai,
                    'dikumpulkan_pada' => $t->dikumpulkan_pada ? $t->dikumpulkan_pada->format('Y-m-d H:i') : null,
                ];
            });

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
