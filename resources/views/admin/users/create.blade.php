@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')
@section('breadcrumb', 'Admin / Pengguna / Tambah')

@section('content')

<div class="card" style="max-width:640px;">
    <div class="card-header">
        <span class="card-title">Form Tambah Pengguna</span>
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

        <form method="POST" action="{{ route('admin.users.store') }}" id="form-tambah-user">
            @csrf

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="name">Nama <span>*</span></label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{ old('name') }}"
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
                        <option value="">— Pilih Role —</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email <span>*</span></label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    placeholder="contoh@email.com"
                >
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="password">Password <span>*</span></label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        required
                        autocomplete="new-password"
                        placeholder="Min. 8 karakter"
                    >
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password <span>*</span></label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi password"
                    >
                </div>
            </div>

            <p class="form-hint" style="margin-bottom:20px;">
                Password tidak akan ditampilkan setelah disimpan.
            </p>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="btn-simpan-user">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengguna
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
