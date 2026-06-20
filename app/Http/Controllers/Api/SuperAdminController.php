<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Article;
use App\Models\Ebook;
use App\Models\Pengumuman;
use App\Models\Notifikasi;
use App\Models\Tugas;
use App\Models\Tugasku;
use App\Models\PoinSiswa;
use App\Models\IzinSiswa;
use App\Models\CatatanSiswa;

class SuperAdminController extends Controller
{
    // ==================== DASHBOARD ====================
    public function dashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_siswa' => Siswa::count(),
                'total_guru' => Guru::count(),
                'total_user' => User::count(),
                'total_artikel' => Article::count(),
                'total_ebook' => Ebook::count(),
                'total_pengumuman' => Pengumuman::count(),
                'total_jadwal' => Jadwal::count(),
                'total_tugas' => Tugas::count(),
                'siswa_per_kelas' => Siswa::selectRaw('kelas, COUNT(*) as jumlah')->groupBy('kelas')->get(),
                'users_per_role' => User::selectRaw('role, COUNT(*) as jumlah')->groupBy('role')->get(),
            ],
        ]);
    }

    // ==================== MANAGE USERS ====================
    public function users(Request $request)
    {
        $query = User::query()->with(['siswa', 'guru']);

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->orderBy('id')->get();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:3',
            'role' => 'required|in:siswa,guru,admin,superadmin',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat',
            'data' => $user,
        ], 201);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $id,
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:3',
            'role' => 'sometimes|in:siswa,guru,admin,superadmin',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
            'data' => $user->fresh(),
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'superadmin') {
            return response()->json(['success' => false, 'message' => 'Tidak bisa menghapus superadmin'], 403);
        }
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus',
        ]);
    }

    // ==================== MANAGE SISWA ====================
    public function siswa(Request $request)
    {
        $query = Siswa::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nisn', 'like', "%$search%")
                  ->orWhere('nis', 'like', "%$search%")
                  ->orWhere('kelas', 'like', "%$search%");
            });
        }

        if ($request->has('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('nama')->get(),
        ]);
    }

    public function createSiswa(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:50|unique:siswas,nis',
            'nisn' => 'required|string|max:50|unique:siswas,nisn',
            'kelas' => 'required|string|max:50',
            'jurusan' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'agama' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'telepon_orangtua' => 'nullable|string|max:20',
            'status' => 'nullable|in:Aktif,Nonaktif,Lulus',
            'username' => 'nullable|string|max:255|unique:users',
            'password' => 'nullable|string|min:3',
        ]);

        // Create user account
        $password = $validated['password'] ?? '123';
        $username = $validated['username'] ?? strtolower(str_replace(' ', '.', $validated['nama']));
        $user = User::create([
            'name' => $validated['nama'],
            'username' => $username,
            'email' => $validated['email'] ?? $username . '@siswa.sekolah.sch.id',
            'password' => Hash::make($password),
            'role' => 'siswa',
        ]);

        unset($validated['username'], $validated['password']);
        $validated['user_id'] = $user->id;
        $validated['status'] = $validated['status'] ?? 'Aktif';
        $siswa = Siswa::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil ditambahkan',
            'data' => $siswa->load('user'),
        ], 201);
    }

    public function updateSiswa(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'nis' => 'sometimes|string|max:50|unique:siswas,nis,' . $id,
            'nisn' => 'sometimes|string|max:50|unique:siswas,nisn,' . $id,
            'kelas' => 'sometimes|string|max:50',
            'jurusan' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'agama' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'telepon_orangtua' => 'nullable|string|max:20',
            'status' => 'nullable|in:Aktif,Nonaktif,Lulus',
        ]);

        $siswa->update($validated);

        // Update user name if siswa nama changed
        if (isset($validated['nama']) && $siswa->user) {
            $siswa->user->update(['name' => $validated['nama']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil diupdate',
            'data' => $siswa->fresh()->load('user'),
        ]);
    }

    public function deleteSiswa($id)
    {
        $siswa = Siswa::findOrFail($id);
        if ($siswa->user) {
            $siswa->user->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dihapus',
        ]);
    }

    // ==================== MANAGE GURU ====================
    public function guru(Request $request)
    {
        $query = Guru::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nip', 'like', "%$search%")
                  ->orWhere('mapel', 'like', "%$search%");
            });
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('nama')->get(),
        ]);
    }

    public function createGuru(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:gurus,nip',
            'mapel' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'kelas_wali' => 'nullable|string|max:50',
            'username' => 'nullable|string|max:255|unique:users',
            'password' => 'nullable|string|min:3',
        ]);

        $password = $validated['password'] ?? '123';
        $username = $validated['username'] ?? strtolower(str_replace([' ', ','], '.', $validated['nama']));
        $user = User::create([
            'name' => $validated['nama'],
            'username' => $username,
            'email' => $username . '@sekolah.sch.id',
            'password' => Hash::make($password),
            'role' => 'guru',
        ]);

        unset($validated['username'], $validated['password']);
        $validated['user_id'] = $user->id;
        $guru = Guru::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil ditambahkan',
            'data' => $guru->load('user'),
        ], 201);
    }

    public function updateGuru(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'nip' => 'sometimes|string|max:50|unique:gurus,nip,' . $id,
            'mapel' => 'sometimes|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'kelas_wali' => 'nullable|string|max:50',
        ]);

        $guru->update($validated);

        if (isset($validated['nama']) && $guru->user) {
            $guru->user->update(['name' => $validated['nama']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil diupdate',
            'data' => $guru->fresh()->load('user'),
        ]);
    }

    public function deleteGuru($id)
    {
        $guru = Guru::findOrFail($id);
        if ($guru->user) {
            $guru->user->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil dihapus',
        ]);
    }

    // ==================== MANAGE JADWAL ====================
    public function jadwal(Request $request)
    {
        $query = Jadwal::query();

        if ($request->has('hari')) {
            $query->where('hari', $request->hari);
        }
        if ($request->has('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 END")->get(),
        ]);
    }

    public function createJadwal(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required|string|max:20',
            'mapel' => 'required|string|max:100',
            'jam_mulai' => 'required|string|max:10',
            'jam_selesai' => 'required|string|max:10',
            'guru' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'ruangan' => 'nullable|string|max:50',
        ]);

        $jadwal = Jadwal::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $jadwal,
        ], 201);
    }

    public function updateJadwal(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'hari' => 'sometimes|string|max:20',
            'mapel' => 'sometimes|string|max:100',
            'jam_mulai' => 'sometimes|string|max:10',
            'jam_selesai' => 'sometimes|string|max:10',
            'guru' => 'sometimes|string|max:255',
            'kelas' => 'sometimes|string|max:50',
            'ruangan' => 'nullable|string|max:50',
        ]);

        $jadwal->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diupdate',
            'data' => $jadwal->fresh(),
        ]);
    }

    public function deleteJadwal($id)
    {
        Jadwal::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus',
        ]);
    }

    // ==================== MANAGE ARTIKEL ====================
    public function artikel(Request $request)
    {
        $query = Article::query();

        if ($request->has('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('tanggal', 'desc')->get(),
        ]);
    }

    public function createArtikel(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'nullable|string|max:50',
            'gambar' => 'nullable|string',
            'penulis' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'status' => 'nullable|in:published,draft',
        ]);

        $validated['tanggal'] = $validated['tanggal'] ?? now()->toDateString();
        $validated['status'] = $validated['status'] ?? 'published';
        $validated['views'] = 0;

        $artikel = Article::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil ditambahkan',
            'data' => $artikel,
        ], 201);
    }

    public function updateArtikel(Request $request, $id)
    {
        $artikel = Article::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'sometimes|string|max:255',
            'konten' => 'sometimes|string',
            'kategori' => 'nullable|string|max:50',
            'gambar' => 'nullable|string',
            'penulis' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'status' => 'nullable|in:published,draft',
        ]);

        $artikel->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil diupdate',
            'data' => $artikel->fresh(),
        ]);
    }

    public function deleteArtikel($id)
    {
        Article::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dihapus',
        ]);
    }

    // ==================== MANAGE EBOOK ====================
    public function ebook(Request $request)
    {
        $query = Ebook::query();

        if ($request->has('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('judul')->get(),
        ]);
    }

    public function createEbook(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'mata_pelajaran' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|string',
            'file_url' => 'nullable|string',
            'halaman' => 'nullable|integer',
            'ukuran' => 'nullable|numeric',
            'rating' => 'nullable|numeric|min:0|max:5',
            'status' => 'nullable|string|max:20',
        ]);

        $ebook = Ebook::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ebook berhasil ditambahkan',
            'data' => $ebook,
        ], 201);
    }

    public function updateEbook(Request $request, $id)
    {
        $ebook = Ebook::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'sometimes|string|max:255',
            'pengarang' => 'sometimes|string|max:255',
            'mata_pelajaran' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|string',
            'file_url' => 'nullable|string',
            'halaman' => 'nullable|integer',
            'ukuran' => 'nullable|numeric',
            'rating' => 'nullable|numeric|min:0|max:5',
            'status' => 'nullable|string|max:20',
        ]);

        $ebook->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ebook berhasil diupdate',
            'data' => $ebook->fresh(),
        ]);
    }

    public function deleteEbook($id)
    {
        Ebook::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ebook berhasil dihapus',
        ]);
    }

    // ==================== MANAGE PENGUMUMAN ====================
    public function pengumuman(Request $request)
    {
        $query = Pengumuman::query();

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('tanggal', 'desc')->get(),
        ]);
    }

    public function createPengumuman(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
        ]);

        $validated['tanggal'] = $validated['tanggal'] ?? now()->toDateString();
        $pengumuman = Pengumuman::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil ditambahkan',
            'data' => $pengumuman,
        ], 201);
    }

    public function updatePengumuman(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'sometimes|string|max:255',
            'isi' => 'sometimes|string',
            'kategori' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
        ]);

        $pengumuman->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil diupdate',
            'data' => $pengumuman->fresh(),
        ]);
    }

    public function deletePengumuman($id)
    {
        Pengumuman::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil dihapus',
        ]);
    }

    // ==================== MANAGE TUGAS GURU ====================
    public function tugas(Request $request)
    {
        $query = Tugas::query();

        if ($request->has('mata_pelajaran')) {
            $query->where('mata_pelajaran', $request->mata_pelajaran);
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('tenggat', 'desc')->get(),
        ]);
    }

    public function createTugas(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mata_pelajaran' => 'required|string|max:100',
            'kelas' => 'required|string|max:50',
            'tanggal_pemberian' => 'nullable|date',
            'tenggat' => 'required|date',
            'status' => 'nullable|in:aktif,selesai,tidak_aktif',
        ]);

        $validated['tanggal_pemberian'] = $validated['tanggal_pemberian'] ?? now()->toDateString();
        $tugas = Tugas::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditambahkan',
            'data' => $tugas,
        ], 201);
    }

    public function updateTugas(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'sometimes|string|max:255',
            'deskripsi' => 'nullable|string',
            'mata_pelajaran' => 'sometimes|string|max:100',
            'kelas' => 'sometimes|string|max:50',
            'tenggat' => 'sometimes|date',
            'status' => 'nullable|in:aktif,selesai,tidak_aktif',
        ]);

        $tugas->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diupdate',
            'data' => $tugas->fresh(),
        ]);
    }

    public function deleteTugas($id)
    {
        Tugas::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus',
        ]);
    }

    // ==================== MANAGE NILAI ====================
    public function nilai(Request $request)
    {
        $query = Nilai::with('siswa');

        if ($request->has('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        if ($request->has('mapel')) {
            $query->where('mapel', $request->mapel);
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function createNilai(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'mapel' => 'required|string|max:100',
            'jenis' => 'required|string|max:20',
            'nilai' => 'required|numeric|min:0|max:100',
            'semester' => 'nullable|string|max:20',
            'tahun_ajaran' => 'nullable|string|max:20',
        ]);

        $validated['semester'] = $validated['semester'] ?? 'Ganjil';
        $validated['tahun_ajaran'] = $validated['tahun_ajaran'] ?? '2025/2026';

        $nilai = Nilai::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil ditambahkan',
            'data' => $nilai,
        ], 201);
    }

    public function updateNilai(Request $request, $id)
    {
        $nilai = Nilai::findOrFail($id);

        $validated = $request->validate([
            'mapel' => 'sometimes|string|max:100',
            'jenis' => 'sometimes|string|max:20',
            'nilai' => 'sometimes|numeric|min:0|max:100',
            'semester' => 'nullable|string|max:20',
            'tahun_ajaran' => 'nullable|string|max:20',
        ]);

        $nilai->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil diupdate',
            'data' => $nilai->fresh(),
        ]);
    }

    public function deleteNilai($id)
    {
        Nilai::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil dihapus',
        ]);
    }

    // ==================== MANAGE ABSENSI ====================
    public function absensi(Request $request)
    {
        $query = Absensi::with('siswa');

        if ($request->has('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        if ($request->has('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('tanggal', 'desc')->get(),
        ]);
    }

    public function createAbsensi(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Sakit,Izin,Alpha',
            'jam_masuk' => 'nullable|string|max:10',
            'jam_pulang' => 'nullable|string|max:10',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi = Absensi::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil ditambahkan',
            'data' => $absensi,
        ], 201);
    }

    public function updateAbsensi(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        $validated = $request->validate([
            'status' => 'sometimes|in:Hadir,Sakit,Izin,Alpha',
            'jam_masuk' => 'nullable|string|max:10',
            'jam_pulang' => 'nullable|string|max:10',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil diupdate',
            'data' => $absensi->fresh(),
        ]);
    }

    public function deleteAbsensi($id)
    {
        Absensi::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil dihapus',
        ]);
    }
}
