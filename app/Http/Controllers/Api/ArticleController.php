<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::orderByDesc('created_at')->get();
        return response()->json(['success' => true, 'data' => $articles]);
    }

    public function show($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['success' => false, 'message' => 'Artikel tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $article]);
    }
}
