<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $notifikasi]);
    }

    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        $notifikasi = Notifikasi::where('user_id', $user->id)->findOrFail($id);
        $notifikasi->update(['dibaca' => true]);

        return response()->json(['success' => true, 'message' => 'Notifikasi ditandai sudah dibaca']);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        Notifikasi::where('user_id', $user->id)
            ->where('dibaca', false)
            ->update(['dibaca' => true]);

        return response()->json(['success' => true, 'message' => 'Semua notifikasi ditandai sudah dibaca']);
    }
}
