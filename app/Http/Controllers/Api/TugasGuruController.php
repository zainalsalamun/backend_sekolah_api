<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasGuruController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $guru = $user->guru;

        if (!$guru) {
            return response()->json(['success' => false, 'message' => 'Data guru tidak ditemukan'], 404);
        }

        $tugas = Tugas::where('guru_id', $guru->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $tugas]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $guru = $user->guru;

        $request->validate([
            'mapel' => 'required|string',
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'tanggal_pemberian' => 'required|date',
            'tanggal_pengumpulan' => 'required|date',
            'file' => 'nullable|string',
        ]);

        $tugas = Tugas::create([
            'guru_id' => $guru->id,
            'mapel' => $request->mapel,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_pemberian' => $request->tanggal_pemberian,
            'tanggal_pengumpulan' => $request->tanggal_pengumpulan,
            'file' => $request->file,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat',
            'data' => $tugas,
        ], 201);
    }

    public function show($id)
    {
        $tugas = Tugas::find($id);
        if (!$tugas) {
            return response()->json(['success' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $tugas]);
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::find($id);
        if (!$tugas) {
            return response()->json(['success' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }

        $tugas->update($request->only([
            'mapel', 'judul', 'deskripsi', 'tanggal_pemberian', 'tanggal_pengumpulan', 'file',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diupdate',
            'data' => $tugas,
        ]);
    }

    public function destroy($id)
    {
        $tugas = Tugas::find($id);
        if (!$tugas) {
            return response()->json(['success' => false, 'message' => 'Tugas tidak ditemukan'], 404);
        }

        $tugas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus',
        ]);
    }
}
