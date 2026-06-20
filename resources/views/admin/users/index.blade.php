@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')
@section('breadcrumb', 'Admin / Pengguna')

@section('topbar-action')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary" id="btn-tambah-user">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Pengguna
    </a>
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <span class="card-title">Daftar Pengguna ({{ $users->total() }})</span>
    </div>

    <div class="table-wrap">
        @if ($users->isEmpty())
            <div class="empty-state">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                </svg>
                <p>Belum ada pengguna terdaftar.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td style="color:#94a3b8;font-size:13px;">
                                {{ $users->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="
                                        width:32px;height:32px;border-radius:50%;
                                        background:linear-gradient(135deg,#60a5fa,#a78bfa);
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:13px;font-weight:700;color:#fff;flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;color:#1e293b;">{{ $user->name }}</div>
                                        @if ($user->id === Auth::id())
                                            <div style="font-size:11px;color:#94a3b8;">(Anda)</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="color:#475569;">{{ $user->email }}</td>
                            <td>
                                @php
                                    $roleClass = match($user->role) {
                                        'Admin'     => 'badge-admin',
                                        'Marketing' => 'badge-marketing',
                                        'Produksi'  => 'badge-produksi',
                                        'Legalitas' => 'badge-legalitas',
                                        default     => 'badge-marketing',
                                    };
                                @endphp
                                <span class="badge {{ $roleClass }}">{{ $user->role }}</span>
                            </td>
                            <td style="font-size:13px;color:#94a3b8;">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td style="text-align:right;">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="btn btn-secondary btn-sm"
                                       id="btn-edit-user-{{ $user->id }}">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    @if ($user->id !== Auth::id())
                                        <form method="POST"
                                              action="{{ route('admin.users.destroy', $user) }}"
                                              class="delete-form"
                                              onsubmit="return confirm('Hapus pengguna {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    id="btn-hapus-user-{{ $user->id }}">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if ($users->hasPages())
        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">
            {{ $users->links() }}
        </div>
    @endif
</div>

@endsection
