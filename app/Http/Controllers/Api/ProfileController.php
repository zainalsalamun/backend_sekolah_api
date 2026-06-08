<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

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
}
