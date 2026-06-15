<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumen = Pengumuman::all();
        return view('admin.pengumuman', compact('pengumumen'));
    }

    public function create()
    {
        return view('admin.pengumuman_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
            'untuk' => 'required|string|max:255',
            'prioritas' => 'nullable|string|max:20',
        ]);

        Pengumuman::create($validated);
        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('admin.pengumuman_edit', compact('pengumuman'));
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
            'untuk' => 'required|string|max:255',
            'prioritas' => 'nullable|string|max:20',
        ]);

        $pengumuman->update($validated);
        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil diupdate');
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();
        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil dihapus');
    }
}
