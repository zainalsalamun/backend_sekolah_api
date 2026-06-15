<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensis = Absensi::with('siswa')->get();
        return view('admin.absensi', compact('absensis'));
    }

    public function create()
    {
        $siswas = Siswa::all();
        return view('admin.absensi_create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'status' => 'required|string|max:20',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Absensi::create($validated);
        return redirect()->route('admin.absensi')->with('success', 'Data absensi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $absensi = Absensi::findOrFail($id);
        $siswas = Siswa::all();
        return view('admin.absensi_edit', compact('absensi', 'siswas'));
    }

    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'status' => 'required|string|max:20',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi->update($validated);
        return redirect()->route('admin.absensi')->with('success', 'Data absensi berhasil diupdate');
    }

    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();
        return redirect()->route('admin.absensi')->with('success', 'Data absensi berhasil dihapus');
    }
}
