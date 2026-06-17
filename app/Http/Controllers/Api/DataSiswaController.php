<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DataSiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $guru = $user->guru;

        if (!$guru) {
            return response()->json(['success' => false, 'message' => 'Data guru tidak ditemukan'], 404);
        }

        $siswa = Siswa::when($guru->kelas_wali, function ($q) use ($guru) {
            $q->where('kelas', $guru->kelas_wali);
        })->orderBy('nama')->get();

        return response()->json(['success' => true, 'data' => $siswa]);
    }

    public function show($id)
    {
        $siswa = Siswa::find($id);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $siswa]);
    }
}
