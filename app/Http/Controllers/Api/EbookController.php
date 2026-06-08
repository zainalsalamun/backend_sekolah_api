<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    public function index(Request $request)
    {
        $ebooks = Ebook::orderByDesc('created_at')->get();
        return response()->json(['success' => true, 'data' => $ebooks]);
    }

    public function show($id)
    {
        $ebook = Ebook::find($id);
        if (!$ebook) {
            return response()->json(['success' => false, 'message' => 'E-book tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $ebook]);
    }
}
