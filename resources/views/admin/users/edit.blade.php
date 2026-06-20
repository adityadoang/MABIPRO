@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')
@section('breadcrumb', 'Admin / Pengguna / Edit')

@section('content')

<div class="card" style="max-width:640px;">
    <div class="card-header">
        <span class="card-title">Edit: {{ $user->name }}</span>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
    <div class="card-body">

        {{-- Error summary --}}
        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <strong>Terdapat {{ $errors->count() }} kesalahan:</strong>
                    <ul style="margin-top:6px;padding-left:16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST"
              action="{{ route('admin.users.update', $user) }}"
              id="form-edit-user-{{ $user->id }}">
            @csrf
            @method('PUT')

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="name">Nama <span>*</span></label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{ old('name', $user->name) }}"
                        required
                        autocomplete="name"
                        placeholder="Nama lengkap"
                    >
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="role">Role <span>*</span></label>
                    <select
                        id="role"
                        name="role"
                        class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}"
                        required
                    >
                        @foreach ($roles as $role)
                            <option value="{{ $role }}"
                                {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="form-error">{{ $message }}</div>
                    @enderror

                    @if ($user->id === Auth::id() && \App\Models\User::where('role', 'Admin')->count() <= 1)
                        <div class="form-hint" style="color:#f59e0b;">
                            ⚠ Anda adalah satu-satunya Admin. Role tidak dapat diubah.
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email <span>*</span></label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="email"
                    placeholder="contoh@email.com"
                >
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Divider password --}}
            <div class="divider"></div>
            <p style="font-size:13px;font-weight:600;color:#374151;margin-bottom:14px;">
                Ganti Password
                <span style="font-weight:400;color:#94a3b8;">(opsional — kosongkan jika tidak ingin mengubah)</span>
            </p>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="password">Password Baru</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        autocomplete="new-password"
                        placeholder="Min. 8 karakter"
                    >
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        autocomplete="new-password"
                        placeholder="Ulangi password baru"
                    >
                </div>
            </div>

            <p class="form-hint" style="margin-bottom:20px;">
                Password tidak akan ditampilkan dan tidak pernah disimpan dalam bentuk teks biasa.
            </p>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="btn-update-user-{{ $user->id }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Perbarui Pengguna
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>

    </div>
</div>

@endsection
