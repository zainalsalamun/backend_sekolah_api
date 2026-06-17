<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah',
            ], 401);
        }

        $token = $user->createToken('mobile-sekolah')->plainTextToken;

        // Get profile based on role
        $profile = null;
        if ($user->role === 'siswa') {
            $profile = $user->siswa;
        } elseif ($user->role === 'guru') {
            $profile = $user->guru;
        }

        $data = [
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'role' => $user->role,
            'token' => $token,
        ];

        // Flatten profile fields into response
        if ($user->role === 'siswa' && $user->siswa) {
            $s = $user->siswa;
            $data = array_merge($data, [
                'nis' => $s->nis,
                'nisn' => $s->nisn,
                'kelas' => $s->kelas,
                'jurusan' => $s->jurusan,
                'no_absen' => $s->no_absen,
                'jenis_kelamin' => $s->jenis_kelamin,
                'tempat_lahir' => $s->tempat_lahir,
                'tanggal_lahir' => $s->tanggal_lahir,
                'alamat' => $s->alamat,
                'agama' => $s->agama,
                'no_hp' => $s->no_hp,
                'email' => $s->email,
                'nama_ayah' => $s->nama_ayah,
                'nama_ibu' => $s->nama_ibu,
                'pekerjaan_ayah' => $s->pekerjaan_ayah,
                'pekerjaan_ibu' => $s->pekerjaan_ibu,
                'tanggal_masuk' => $s->tanggal_masuk ? $s->tanggal_masuk->format('Y-m-d') : null,
                'status_siswa' => $s->status_siswa,
                'foto_url' => $s->foto_url,
            ]);
        } elseif ($user->role === 'guru' && $user->guru) {
            $g = $user->guru;
            $data = array_merge($data, [
                'nip' => $g->nip,
                'jabatan' => $g->jabatan,
                'mata_pelajaran' => $g->mata_pelajaran,
                'kelas_ampu' => $g->kelas_ampu,
                'pendidikan_terakhir' => $g->pendidikan_terakhir,
                'status_kepegawaian' => $g->status_kepegawaian,
                'golongan' => $g->golongan,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => $data,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }
}
