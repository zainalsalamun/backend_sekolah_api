<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $pengumuman = Pengumuman::where('status', 'published')
            ->orderByDesc('tanggal')
            ->get();

        return response()->json(['success' => true, 'data' => $pengumuman]);
    }
}
