<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::all();
        return view('admin.jadwal', compact('jadwals'));
    }

    public function create()
    {
        return view('admin.jadwal_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas' => 'required|string|max:10',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|string|max:5',
            'jam_selesai' => 'required|string|max:5',
            'mata_pelajaran' => 'required|string|max:255',
            'guru_id' => 'required|exists:gurus,id',
        ]);

        Jadwal::create($validated);
        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        return view('admin.jadwal_edit', compact('jadwal'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $validated = $request->validate([
            'kelas' => 'required|string|max:10',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|string|max:5',
            'jam_selesai' => 'required|string|max:5',
            'mata_pelajaran' => 'required|string|max:255',
            'guru_id' => 'required|exists:gurus,id',
        ]);

        $jadwal->update($validated);
        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil dihapus');
    }
}
