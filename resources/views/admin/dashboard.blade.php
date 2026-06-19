@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Admin / Dashboard')

@section('content')

{{-- Statistik Ringkasan --}}
<div class="stats-grid">

    {{-- Users --}}
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#eff6ff;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
            </svg>
        </div>
        <div class="stat-card-value">{{ $stats['users'] }}</div>
        <div class="stat-card-label">Total Pengguna</div>
        <a href="{{ route('admin.users.index') }}"
           style="font-size:12px;color:#3b82f6;font-weight:500;margin-top:4px;">
            Kelola →
        </a>
    </div>

    {{-- Blocks --}}
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#fef3c7;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div class="stat-card-value">{{ $stats['blocks'] ?? 0 }}</div>
        <div class="stat-card-label">Blok Perumahan</div>
        @if ($stats['blocks'] === null)
            <span class="stat-card-badge badge-pending">Belum tersedia</span>
        @endif
    </div>

    {{-- Units --}}
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#f0fdf4;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#22c55e" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>
        <div class="stat-card-value">{{ $stats['units'] ?? 0 }}</div>
        <div class="stat-card-label">Unit Rumah</div>
        @if ($stats['units'] === null)
            <span class="stat-card-badge badge-pending">Belum tersedia</span>
        @endif
    </div>

    {{-- Progress Photos --}}
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#fdf4ff;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#a855f7" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="stat-card-value">{{ $stats['progress_photos'] ?? 0 }}</div>
        <div class="stat-card-label">Foto Progres</div>
        @if ($stats['progress_photos'] === null)
            <span class="stat-card-badge badge-pending">Belum tersedia</span>
        @endif
    </div>

    {{-- Legal Documents --}}
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#fff7ed;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#f97316" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div class="stat-card-value">{{ $stats['legal_documents'] ?? 0 }}</div>
        <div class="stat-card-label">Dokumen Legalitas</div>
        @if ($stats['legal_documents'] === null)
            <span class="stat-card-badge badge-pending">Belum tersedia</span>
        @endif
    </div>

</div>

{{-- Info panel --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Informasi Sistem</span>
    </div>
    <div class="card-body">
        <p style="font-size:14px;color:#64748b;line-height:1.7;">
            Selamat datang, <strong style="color:#1e293b;">{{ Auth::user()->name }}</strong>.
            Anda masuk sebagai <strong style="color:#1e293b;">{{ Auth::user()->role }}</strong>.
            Gunakan menu di sidebar untuk mengelola data pengguna dan memantau sistem MABIPRO.
        </p>

        <div style="margin-top:16px;display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;">
            <div style="background:#f8fafc;border-radius:8px;padding:12px 14px;border:1px solid #e2e8f0;">
                <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Versi Laravel</div>
                <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ app()->version() }}</div>
            </div>
            <div style="background:#f8fafc;border-radius:8px;padding:12px 14px;border:1px solid #e2e8f0;">
                <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Environment</div>
                <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ app()->environment() }}</div>
            </div>
            <div style="background:#f8fafc;border-radius:8px;padding:12px 14px;border:1px solid #e2e8f0;">
                <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Waktu Server</div>
                <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ now()->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>
</div>

@endsection
