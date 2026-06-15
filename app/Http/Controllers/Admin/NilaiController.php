<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        $nilais = Nilai::with('siswa')->get();
        return view('admin.nilai', compact('nilais'));
    }

    public function create()
    {
        $siswas = Siswa::all();
        return view('admin.nilai_create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'mata_pelajaran' => 'required|string|max:255',
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_uts' => 'required|numeric|min:0|max:100',
            'nilai_uas' => 'required|numeric|min:0|max:100',
            'nilai_rata_rata' => 'required|numeric|min:0|max:100',
            'semester' => 'required|string|max:10',
            'tahun_ajaran' => 'required|string|max:10',
        ]);

        Nilai::create($validated);
        return redirect()->route('admin.nilai')->with('success', 'Data nilai berhasil ditambahkan');
    }

    public function edit($id)
    {
        $nilai = Nilai::findOrFail($id);
        $siswas = Siswa::all();
        return view('admin.nilai_edit', compact('nilai', 'siswas'));
    }

    public function update(Request $request, $id)
    {
        $nilai = Nilai::findOrFail($id);
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'mata_pelajaran' => 'required|string|max:255',
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_uts' => 'required|numeric|min:0|max:100',
            'nilai_uas' => 'required|numeric|min:0|max:100',
            'nilai_rata_rata' => 'required|numeric|min:0|max:100',
            'semester' => 'required|string|max:10',
            'tahun_ajaran' => 'required|string|max:10',
        ]);

        $nilai->update($validated);
        return redirect()->route('admin.nilai')->with('success', 'Data nilai berhasil diupdate');
    }

    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();
        return redirect()->route('admin.nilai')->with('success', 'Data nilai berhasil dihapus');
    }
}
