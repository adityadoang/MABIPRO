@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed mb-2">Edit Pengguna: {{ $user->name }}</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Perbarui informasi dan role akun pengguna.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 bg-surface-container-high hover:bg-surface-dim text-on-surface px-4 py-2 rounded-xl transition-all font-label-md font-bold">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        Kembali
    </a>
</div>

<div class="bg-surface-container dark:bg-surface-container-low rounded-3xl p-6 md:p-8 border border-outline-variant dark:border-outline">
    @if ($errors->any())
        <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-6 border border-error/20 flex items-start gap-3">
            <span class="material-symbols-outlined shrink-0 mt-0.5">error</span>
            <div>
                <p class="font-bold mb-1">Terdapat {{ $errors->count() }} kesalahan:</p>
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-label-md font-bold text-on-surface mb-2" for="name">
                    Nama <span class="text-error">*</span>
                </label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" placeholder="Nama lengkap"
                       class="w-full bg-surface-bright dark:bg-surface-dim border {{ $errors->has('name') ? 'border-error' : 'border-outline-variant dark:border-outline' }} rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                @error('name')
                    <p class="text-error font-body-sm mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-label-md font-bold text-on-surface mb-2" for="role">
                    Role <span class="text-error">*</span>
                </label>
                <select id="role" name="role" required
                        class="w-full bg-surface-bright dark:bg-surface-dim border {{ $errors->has('role') ? 'border-error' : 'border-outline-variant dark:border-outline' }} rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-error font-body-sm mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                @enderror

                @if ($user->id === Auth::id() && \App\Models\User::where('role', 'Admin')->count() <= 1)
                    <p class="text-error font-body-sm mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">warning</span>
                        Anda adalah satu-satunya Admin. Role tidak dapat diubah.
                    </p>
                @endif
            </div>
        </div>

        <div>
            <label class="block font-label-md font-bold text-on-surface mb-2" for="email">
                Email <span class="text-error">*</span>
            </label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email" placeholder="contoh@email.com"
                   class="w-full bg-surface-bright dark:bg-surface-dim border {{ $errors->has('email') ? 'border-error' : 'border-outline-variant dark:border-outline' }} rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            @error('email')
                <p class="text-error font-body-sm mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-6 mt-6 border-t border-outline-variant dark:border-outline">
            <h3 class="font-headline-sm font-bold text-on-surface mb-1">Ganti Password</h3>
            <p class="font-body-sm text-on-surface-variant mb-6">(opsional — kosongkan jika tidak ingin mengubah)</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-label-md font-bold text-on-surface mb-2" for="password">
                        Password Baru
                    </label>
                    <input id="password" type="password" name="password" autocomplete="new-password" placeholder="Min. 8 karakter"
                           class="w-full bg-surface-bright dark:bg-surface-dim border {{ $errors->has('password') ? 'border-error' : 'border-outline-variant dark:border-outline' }} rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    @error('password')
                        <p class="text-error font-body-sm mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-label-md font-bold text-on-surface mb-2" for="password_confirmation">
                        Konfirmasi Password Baru
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi password baru"
                           class="w-full bg-surface-bright dark:bg-surface-dim border border-outline-variant dark:border-outline rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
            </div>

            <p class="text-on-surface-variant font-body-sm mt-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">lock</span>
                Password akan dienkripsi dengan aman.
            </p>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-outline-variant dark:border-outline">
            <a href="{{ route('admin.users.index') }}" 
               class="bg-surface-container-high hover:bg-surface-dim text-on-surface font-label-md font-bold px-6 py-3 rounded-xl transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">close</span>
                Batal
            </a>
            <button type="submit" class="bg-primary hover:opacity-90 text-on-primary font-label-md font-bold px-6 py-3 rounded-xl transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">check</span>
                Update Pengguna
            </button>
        </div>
    </form>
</div>
@endsection
