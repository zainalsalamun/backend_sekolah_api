<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Tugas;
use App\Models\Tugasku;
use App\Models\Pengumuman;
use App\Models\Article;
use App\Models\Ebook;
use App\Models\Notifikasi;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_siswa' => Siswa::count(),
            'total_guru' => Guru::count(),
            'total_kelas' => Siswa::distinct('kelas')->count('kelas'),
            'total_tugas' => Tugas::count(),
        ];

        $siswaPerKelas = Siswa::selectRaw('kelas, count(*) as total')
            ->groupBy('kelas')
            ->get();

        $recentPengumuman = Pengumuman::latest()->take(5)->get();
        $recentAbsensi = Absensi::with(['siswa.user'])->latest()->take(10)->get();
        $recentNotifikasi = Notifikasi::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'siswaPerKelas', 'recentPengumuman', 'recentAbsensi', 'recentNotifikasi'));
    }

    public function siswa()
    {
        $siswas = Siswa::with(['user'])->get();
        return view('admin.siswa', compact('siswas'));
    }

    public function guru()
    {
        $gurus = Guru::with('user')->get();
        return view('admin.guru', compact('gurus'));
    }

    public function jadwal()
    {
        $jadwals = Jadwal::all();
        return view('admin.jadwal', compact('jadwals'));
    }

    public function nilai()
    {
        $nilais = Nilai::with(['siswa.user'])->get();
        return view('admin.nilai', compact('nilais'));
    }

    public function absensi()
    {
        $absensis = Absensi::with(['siswa.user'])->latest()->get();
        return view('admin.absensi', compact('absensis'));
    }

    public function tugas()
    {
        $tugas = Tugas::with(['guru.user'])->latest()->get();
        return view('admin.tugas', compact('tugas'));
    }

    public function pengumuman()
    {
        $pengumumen = Pengumuman::latest()->get();
        return view('admin.pengumuman', compact('pengumumen'));
    }

    public function artikel()
    {
        $artikels = Article::latest()->get();
        return view('admin.artikel', compact('artikels'));
    }

    public function ebook()
    {
        $ebooks = Ebook::latest()->get();
        return view('admin.ebook', compact('ebooks'));
    }
}
