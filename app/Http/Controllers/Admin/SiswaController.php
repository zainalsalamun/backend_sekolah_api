<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with('user')->get();
        return view('admin.siswa', compact('siswas'));
    }

    public function create()
    {
        return view('admin.siswa_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nisn' => 'required|string|max:20|unique:siswas,nisn',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:10',
            'jurusan' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'telepon_orangtua' => 'nullable|string|max:20',
            'foto' => 'nullable|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        Siswa::create($validated);
        return redirect()->route('admin.siswa')->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('admin.siswa_edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $validated = $request->validate([
            'nisn' => 'required|string|max:20|unique:siswas,nisn,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:10',
            'jurusan' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'telepon_orangtua' => 'nullable|string|max:20',
            'foto' => 'nullable|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $siswa->update($validated);
        return redirect()->route('admin.siswa')->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();
        return redirect()->route('admin.siswa')->with('success', 'Data siswa berhasil dihapus');
    }
}
