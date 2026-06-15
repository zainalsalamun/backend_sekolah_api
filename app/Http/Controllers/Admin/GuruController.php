<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')->get();
        return view('admin.guru', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:20|unique:gurus,nip',
            'mata_pelajaran' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'foto' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        Guru::create($validated);
        return redirect()->route('admin.guru')->with('success', 'Data guru berhasil ditambahkan');
    }

    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru_edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:20|unique:gurus,nip,' . $guru->id,
            'mata_pelajaran' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'foto' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $guru->update($validated);
        return redirect()->route('admin.guru')->with('success', 'Data guru berhasil diupdate');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();
        return redirect()->route('admin.guru')->with('success', 'Data guru berhasil dihapus');
    }
}
