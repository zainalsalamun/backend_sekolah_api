<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Guru;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index()
    {
        $tugass = Tugas::all();
        return view('admin.tugas', compact('tugass'));
    }

    public function create()
    {
        $gurus = Guru::all();
        return view('admin.tugas_create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'batas_waktu' => 'required|date',
            'kelas' => 'required|string|max:10',
            'mata_pelajaran' => 'required|string|max:255',
            'guru_id' => 'required|exists:gurus,id',
        ]);

        Tugas::create($validated);
        return redirect()->route('admin.tugas')->with('success', 'Tugas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $tugas = Tugas::findOrFail($id);
        $gurus = Guru::all();
        return view('admin.tugas_edit', compact('tugas', 'gurus'));
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'batas_waktu' => 'required|date',
            'kelas' => 'required|string|max:10',
            'mata_pelajaran' => 'required|string|max:255',
            'guru_id' => 'required|exists:gurus,id',
        ]);

        $tugas->update($validated);
        return redirect()->route('admin.tugas')->with('success', 'Tugas berhasil diupdate');
    }

    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();
        return redirect()->route('admin.tugas')->with('success', 'Tugas berhasil dihapus');
    }
}
