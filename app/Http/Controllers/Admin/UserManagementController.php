<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->with(['siswa', 'guru'])->latest()->paginate(10);
        $roles = ['admin', 'guru', 'siswa', 'superadmin'];

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = ['admin', 'guru', 'siswa', 'superadmin'];
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(6)],
            'role' => 'required|in:admin,guru,siswa,superadmin',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // Auto-create siswa or guru profile
        if ($user->role === 'siswa') {
            Siswa::create([
                'user_id' => $user->id,
                'nisn' => '',
                'nama' => $user->name,
                'kelas' => '-',
                'jurusan' => '-',
                'status' => 'Aktif',
                'total_poin' => 0,
                'ranking' => 0,
            ]);
        } elseif ($user->role === 'guru') {
            Guru::create([
                'user_id' => $user->id,
                'nip' => '',
                'nama' => $user->name,
                'mapel' => '-',
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'guru', 'siswa', 'superadmin'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(6)],
            'role' => 'required|in:admin,guru,siswa,superadmin',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}
