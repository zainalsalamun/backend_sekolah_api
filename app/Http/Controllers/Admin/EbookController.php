<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::all();
        return view('admin.ebook', compact('ebooks'));
    }

    public function create()
    {
        return view('admin.ebook_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'mata_pelajaran' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|string|max:255',
            'file_url' => 'nullable|string|max:255',
            'halaman' => 'nullable|integer',
            'ukuran' => 'nullable|numeric',
            'rating' => 'nullable|numeric|min:0|max:5',
            'jumlah_download' => 'nullable|integer',
            'status' => 'nullable|string|max:20',
        ]);

        Ebook::create($validated);
        return redirect()->route('admin.ebook')->with('success', 'Ebook berhasil ditambahkan');
    }

    public function edit($id)
    {
        $ebook = Ebook::findOrFail($id);
        return view('admin.ebook_edit', compact('ebook'));
    }

    public function update(Request $request, $id)
    {
        $ebook = Ebook::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'mata_pelajaran' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|string|max:255',
            'file_url' => 'nullable|string|max:255',
            'halaman' => 'nullable|integer',
            'ukuran' => 'nullable|numeric',
            'rating' => 'nullable|numeric|min:0|max:5',
            'jumlah_download' => 'nullable|integer',
            'status' => 'nullable|string|max:20',
        ]);

        $ebook->update($validated);
        return redirect()->route('admin.ebook')->with('success', 'Ebook berhasil diupdate');
    }

    public function destroy($id)
    {
        $ebook = Ebook::findOrFail($id);
        $ebook->delete();
        return redirect()->route('admin.ebook')->with('success', 'Ebook berhasil dihapus');
    }
}
