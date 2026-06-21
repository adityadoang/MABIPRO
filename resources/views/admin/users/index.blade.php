@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="font-headline-md text-headline-md font-bold text-primary">Kelola Pengguna</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Daftar pengguna ({{ $users->total() }})</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="bg-primary text-on-primary hover:bg-primary/90 font-label-md font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
        <span class="material-symbols-outlined">person_add</span>
        Tambah Pengguna
    </a>
</div>

<div class="bg-surface-container-low rounded-2xl border border-outline-variant overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left font-body-sm text-on-surface">
            <thead class="bg-surface-container border-b border-outline-variant text-on-surface-variant font-label-md">
                <tr>
                    <th class="py-3 px-4 font-bold">#</th>
                    <th class="py-3 px-4 font-bold">NAMA PENGGUNA</th>
                    <th class="py-3 px-4 font-bold">EMAIL</th>
                    <th class="py-3 px-4 font-bold text-center">ROLE</th>
                    <th class="py-3 px-4 font-bold">BERGABUNG</th>
                    <th class="py-3 px-4 font-bold text-right">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse ($users as $index => $user)
                <tr class="hover:bg-surface-container-high transition-colors">
                    <td class="py-3 px-4 text-on-surface-variant text-xs">{{ $users->firstItem() + $loop->index }}</td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-tertiary flex items-center justify-center font-bold text-on-primary text-xs flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-primary">{{ $user->name }}</div>
                                @if ($user->id === Auth::id())
                                    <div class="text-[10px] text-on-surface-variant">(Anda)</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4">{{ $user->email }}</td>
                    <td class="py-3 px-4 text-center">
                        @php
                            $roleClass = match($user->role) {
                                'Admin'     => 'bg-primary text-on-primary',
                                'Marketing' => 'bg-secondary-container text-on-secondary-container',
                                'Produksi'  => 'bg-tertiary-container text-on-tertiary-container',
                                'Legalitas' => 'bg-error-container text-on-error-container',
                                default     => 'bg-surface-container-highest text-on-surface-variant',
                            };
                        @endphp
                        <span class="py-1 px-2 rounded-md font-bold text-[10px] uppercase tracking-wider {{ $roleClass }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-xs text-on-surface-variant">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-secondary hover:text-primary transition-colors p-1" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            @if ($user->id !== Auth::id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-error hover:text-error/80 transition-colors p-1" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 px-4 text-center text-on-surface-variant flex flex-col items-center gap-2 justify-center">
                        <span class="material-symbols-outlined text-4xl text-outline">group_off</span>
                        <span>Belum ada pengguna terdaftar</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
