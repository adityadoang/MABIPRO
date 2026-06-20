<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Daftar semua user.
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Form tambah user baru.
     */
    public function create()
    {
        $roles = $this->availableRoles();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in($this->availableRoles())],
        ], $this->validationMessages());

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$validated['name']} berhasil ditambahkan.");
    }

    /**
     * Form edit user.
     */
    public function edit(User $user)
    {
        $roles = $this->availableRoles();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'  => ['required', Rule::in($this->availableRoles())],
        ];

        // Password opsional saat edit; jika diisi wajib minimal 8 karakter
        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:8', 'confirmed'];
        }

        // Cegah admin mengubah role dirinya ke non-Admin jika dia satu-satunya Admin
        if (
            $user->id === Auth::id() &&
            $request->input('role') !== 'Admin' &&
            User::where('role', 'Admin')->count() <= 1
        ) {
            return back()
                ->withErrors(['role' => 'Tidak dapat mengubah role Anda karena Anda adalah satu-satunya Admin.'])
                ->withInput();
        }

        $validated = $request->validate($rules, $this->validationMessages());

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ];

        // Hanya perbarui password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} berhasil diperbarui.");
    }

    /**
     * Hapus user.
     */
    public function destroy(User $user)
    {
        // Cegah admin menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Cegah penghapusan satu-satunya Admin
        if ($user->role === 'Admin' && User::where('role', 'Admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus satu-satunya akun Admin.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User {$name} berhasil dihapus.");
    }

    /**
     * Daftar role yang tersedia.
     */
    private function availableRoles(): array
    {
        return ['Admin', 'Marketing', 'Produksi', 'Legalitas'];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    private function validationMessages(): array
    {
        return [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah digunakan oleh user lain.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required'      => 'Role wajib dipilih.',
            'role.in'            => 'Role yang dipilih tidak valid.',
        ];
    }
}
