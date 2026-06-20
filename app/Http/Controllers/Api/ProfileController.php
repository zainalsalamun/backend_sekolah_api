<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'siswa') {
            $profile = Siswa::with('user')->where('user_id', $user->id)->first();
        } elseif ($user->role === 'guru') {
            $profile = $user->guru;
        } else {
            $profile = null;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'profile' => $profile,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // Validation rules based on role
        $rules = [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ];

        if ($user->role === 'siswa') {
            $siswaRules = [
                'nis' => 'sometimes|string|max:20|unique:siswas,nis,' . $user->siswa?->id,
                'nisn' => 'nullable|string|max:20|unique:siswas,nisn,' . $user->siswa?->id,
                'kelas' => 'sometimes|string|max:20',
                'jurusan' => 'nullable|string|max:100',
                'angkatan' => 'nullable|string|max:10',
                'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
                'tempat_lahir' => 'nullable|string|max:100',
                'tanggal_lahir' => 'nullable|date',
                'agama' => 'nullable|string|max:20',
            ];
            $rules = array_merge($rules, $siswaRules);
        } elseif ($user->role === 'guru') {
            $guruRules = [
                'nip' => 'sometimes|string|max:20|unique:gurus,nip,' . $user->guru?->id,
                'mata_pelajaran' => 'nullable|string|max:100',
                'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            ];
            $rules = array_merge($rules, $guruRules);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        // Update user data
        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        $user->save();

        // Update profile based on role
        if ($user->role === 'siswa') {
            $siswa = $user->siswa;
            if ($siswa) {
                $profileFields = ['nis', 'nisn', 'kelas', 'jurusan', 'angkatan', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama', 'phone', 'address'];
                foreach ($profileFields as $field) {
                    if (isset($validated[$field])) {
                        $siswa->$field = $validated[$field];
                    }
                }
                $siswa->save();
            }
        } elseif ($user->role === 'guru') {
            $guru = $user->guru;
            if ($guru) {
                $profileFields = ['nip', 'mata_pelajaran', 'jenis_kelamin', 'phone', 'address'];
                foreach ($profileFields as $field) {
                    if (isset($validated[$field])) {
                        $guru->$field = $validated[$field];
                    }
                }
                $guru->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => [
                'user' => $user,
                'profile' => $user->role === 'siswa' ? $user->siswa : $user->guru,
            ],
        ]);
    }
}
