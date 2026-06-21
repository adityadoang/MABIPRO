@extends('layouts.admin')

@section('title', 'System Dashboard')

@section('content')
<!-- Page Title -->
<div>
    <h1 class="font-headline-lg text-headline-lg font-bold text-on-surface mb-2">System Dashboard</h1>
    <p class="font-body-md text-body-md text-on-surface-variant">Overview of operations and user administration.</p>
</div>

<!-- Bento Grid Overview -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
    <!-- Marketing Card (Using Users for now as proxy or real stats) -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-secondary-container/20 rounded-full group-hover:scale-110 transition-transform"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-secondary-container rounded-lg text-on-secondary-container">
                <span class="material-symbols-outlined">group</span>
            </div>
            <span class="bg-secondary-container text-on-secondary-container font-label-sm text-label-sm px-2 py-1 rounded-full">Users</span>
        </div>
        <h3 class="font-label-md text-label-md text-on-surface-variant mb-1">Total Pengguna</h3>
        <p class="font-headline-md text-headline-md font-bold text-on-surface">{{ $stats['users'] }}</p>
        <a href="{{ route('admin.users.index') }}" class="font-body-sm text-body-sm text-secondary hover:underline mt-2 inline-block">Kelola Pengguna →</a>
    </div>

    <!-- Production Card -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary-fixed/20 rounded-full group-hover:scale-110 transition-transform"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-primary-fixed rounded-lg text-on-primary-fixed">
                <span class="material-symbols-outlined">construction</span>
            </div>
            <span class="bg-surface-variant text-on-surface-variant font-label-sm text-label-sm px-2 py-1 rounded-full">Progres</span>
        </div>
        <h3 class="font-label-md text-label-md text-on-surface-variant mb-1">Blok & Unit</h3>
        <p class="font-headline-md text-headline-md font-bold text-on-surface">{{ $stats['units'] ?? 0 }} Unit</p>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-2">{{ $stats['blocks'] ?? 0 }} Blok tersedia</p>
    </div>

    <!-- Legality Card -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-error-container/20 rounded-full group-hover:scale-110 transition-transform"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-error-container rounded-lg text-on-error-container">
                <span class="material-symbols-outlined">description</span>
            </div>
            <span class="bg-error-container text-on-error-container font-label-sm text-label-sm px-2 py-1 rounded-full">Legalitas</span>
        </div>
        <h3 class="font-label-md text-label-md text-on-surface-variant mb-1">Dokumen Legalitas</h3>
        <p class="font-headline-md text-headline-md font-bold text-on-surface">{{ $stats['legal_documents'] ?? 0 }}</p>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-2">Dokumen terverifikasi</p>
    </div>
</section>

<!-- User Management & Logs Container -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter mt-8">
    <!-- User Management Module -->
    <section class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm flex flex-col">
        <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-container-low rounded-t-xl">
            <h2 class="font-headline-md text-headline-md font-bold text-on-surface">User Management</h2>
            <a href="{{ route('admin.users.create') }}" class="bg-secondary text-on-secondary font-label-md text-label-md py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-on-secondary-container transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm">add</span>
                Tambah
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface border-b border-outline-variant">
                        <th class="font-label-md text-label-md text-on-surface-variant py-4 px-6">Name</th>
                        <th class="font-label-md text-label-md text-on-surface-variant py-4 px-6">Role</th>
                        <th class="font-label-md text-label-md text-on-surface-variant py-4 px-6">Status</th>
                        <th class="font-label-md text-label-md text-on-surface-variant py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="font-body-sm text-body-sm text-on-surface divide-y divide-outline-variant">
                    @forelse($recentUsers as $user)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="py-4 px-6 flex items-center gap-3">
                                @php
                                    // Generate initial block color based on role
                                    $bgClass = 'bg-primary-fixed text-on-primary-fixed';
                                    if(strtolower($user->role) === 'marketing') $bgClass = 'bg-secondary-container text-on-secondary-container';
                                    if(strtolower($user->role) === 'produksi') $bgClass = 'bg-tertiary-fixed text-on-tertiary-fixed';
                                @endphp
                                <div class="w-8 h-8 rounded-full {{ $bgClass }} flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold">{{ $user->name }}</p>
                                    <p class="text-on-surface-variant text-xs">{{ $user->email }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">{{ ucfirst($user->role) }}</td>
                            <td class="py-4 px-6">
                                <span class="bg-secondary-container text-on-secondary-container px-2 py-1 rounded-full text-xs font-medium">Active</span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2 flex justify-end">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-primary hover:text-secondary transition-colors p-1 rounded-full hover:bg-surface-container inline-block">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-error hover:text-on-error-container transition-colors p-1 rounded-full hover:bg-error-container inline-block">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 px-6 text-center text-on-surface-variant">Belum ada pengguna</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- System Logs Module -->
    <section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm flex flex-col">
        <div class="p-6 border-b border-outline-variant bg-surface-container-low rounded-t-xl">
            <h2 class="font-headline-md text-headline-md font-bold text-on-surface">System Activity</h2>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto max-h-[400px]">
            <!-- Placeholder Logs (Belum ada tabel logs di database, menggunakan dummy statis) -->
            <div class="flex gap-4 relative">
                <div class="w-[2px] h-full bg-outline-variant absolute left-3 top-6 -z-10"></div>
                <div class="w-6 h-6 rounded-full bg-primary-fixed flex items-center justify-center flex-shrink-0 mt-1">
                    <span class="material-symbols-outlined text-[14px] text-on-primary-fixed">person_add</span>
                </div>
                <div>
                    <p class="font-body-sm text-body-sm text-on-surface">New user <span class="font-bold">Sarah Lee</span> added to Legal role.</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant mt-1">2 hours ago</p>
                </div>
            </div>

            <div class="flex gap-4 relative">
                <div class="w-[2px] h-full bg-outline-variant absolute left-3 top-6 -z-10"></div>
                <div class="w-6 h-6 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 mt-1">
                    <span class="material-symbols-outlined text-[14px] text-on-secondary-container">edit_document</span>
                </div>
                <div>
                    <p class="font-body-sm text-body-sm text-on-surface">Property <span class="font-bold">Block A-12</span> updated.</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant mt-1">5 hours ago</p>
                </div>
            </div>

            <div class="flex gap-4 relative">
                <div class="w-6 h-6 rounded-full bg-error-container flex items-center justify-center flex-shrink-0 mt-1">
                    <span class="material-symbols-outlined text-[14px] text-on-error-container">warning</span>
                </div>
                <div>
                    <p class="font-body-sm text-body-sm text-on-surface">Failed login attempt for admin account.</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant mt-1">Yesterday</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
