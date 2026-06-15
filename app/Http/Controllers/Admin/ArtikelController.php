<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::all();
        return view('admin.artikel', compact('artikels'));
    }

    public function create()
    {
        return view('admin.artikel_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'penulis' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'kategori' => 'nullable|string|max:255',
            'gambar' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:20',
        ]);

        Artikel::create($validated);
        return redirect()->route('admin.artikel')->with('success', 'Artikel berhasil ditambahkan');
    }

    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('admin.artikel_edit', compact('artikel'));
    }

    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'penulis' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'kategori' => 'nullable|string|max:255',
            'gambar' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:20',
        ]);

        $artikel->update($validated);
        return redirect()->route('admin.artikel')->with('success', 'Artikel berhasil diupdate');
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);
        $artikel->delete();
        return redirect()->route('admin.artikel')->with('success', 'Artikel berhasil dihapus');
    }
}
