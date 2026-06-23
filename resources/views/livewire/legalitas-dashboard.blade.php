<div class="legal-wrapper">

    {{-- ══════════════════════════════════════════════════════
         PAGE HEADER
    ══════════════════════════════════════════════════════ --}}
    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Legal Compliance</h1>
            <p class="page-subtitle">Manage unit documents, certificates, and permits.</p>
        </div>
        <div class="page-header-right">
            {{--
                TIPS LIVEWIRE:
                wire:model.live menghubungkan input ini dengan properti $search di backend secara real-time.
                Setiap kali user mengetik, Livewire akan memfilter data tanpa reload halaman.
            --}}
            <div class="search-wrap">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live="search"
                       type="text"
                       id="legal-search"
                       class="search-input"
                       placeholder="Search units or documents...">
            </div>
        </div>
    </div>

    {{-- ── Flash Message ── --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show"
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="flash-alert" role="alert">
            <svg class="flash-icon" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="flash-text">{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="flash-alert flash-alert-error" role="alert">
            <svg class="flash-icon" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="flash-text">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         STATS CARDS
    ══════════════════════════════════════════════════════ --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon-wrap stat-icon-gray">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="stat-label">TOTAL UNITS</span>
            </div>
            <div class="stat-value">{{ $stats['totalUnits'] }}</div>
            <p class="stat-sub">{{ $groupedUnits->count() }} housing blocks</p>
        </div>

        <div class="stat-card stat-compliant">
            <div class="stat-header">
                <div class="stat-icon-wrap stat-icon-green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="stat-label stat-label-green">COMPLIANT</span>
            </div>
            <div class="stat-value stat-value-green">{{ $stats['compliant'] }}</div>
            <p class="stat-sub stat-sub-green">{{ $stats['completionRate'] }}% completion rate</p>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon-wrap stat-icon-amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="stat-label stat-label-amber">IN PROGRESS</span>
            </div>
            <div class="stat-value stat-value-amber">{{ $stats['inProgress'] }}</div>
            <p class="stat-sub stat-sub-amber">Partially uploaded</p>
        </div>

        <div class="stat-card stat-missing">
            <div class="stat-header">
                <div class="stat-icon-wrap stat-icon-red">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <span class="stat-label stat-label-red">MISSING DOCS</span>
            </div>
            <div class="stat-value stat-value-red">{{ $stats['missing'] }}</div>
            <p class="stat-sub stat-sub-red">Requires immediate action</p>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════
         UNIT LIST — Grouped by Block (Accordion)
    ══════════════════════════════════════════════════════ --}}

    {{-- Loading indicator saat Livewire sedang proses --}}
    <div wire:loading.delay class="loading-bar">
        <div class="loading-bar-inner"></div>
    </div>

    @forelse($groupedUnits as $blockName => $blockUnits)
        @php
            $blockCompliant = $blockUnits->where('status_legalitas', 'Lengkap')->count();
            $blockMissing   = $blockUnits->count() - $blockCompliant;
        @endphp

        {{-- Block Accordion --}}
        {{--
            TIPS ALPINE.JS (x-data):
            Alpine.js dipakai untuk animasi accordion lokal di browser (toggle buka/tutup)
            tanpa perlu request ke server.
        --}}
        <div class="accordion" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }"
             wire:key="block-{{ $loop->index }}">

            {{-- Accordion Header --}}
            <button type="button" class="accordion-header" @click="open = !open">
                <div class="accordion-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>

                <div class="accordion-info">
                    <h2 class="accordion-title">{{ $blockName }}</h2>
                    <p class="accordion-sub">
                        {{ $blockUnits->count() }} unit
                        &bull; <span class="text-green-600 font-semibold">{{ $blockCompliant }} complete</span>
                        @if($blockMissing > 0)
                            &bull; <span class="text-red-500 font-semibold">{{ $blockMissing }} missing</span>
                        @endif
                    </p>
                </div>

                <div class="accordion-badges">
                    @if($blockMissing > 0)
                        <span class="badge badge-red">
                            <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $blockMissing }} Missing
                        </span>
                    @else
                        <span class="badge badge-green">
                            <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            All Complete
                        </span>
                    @endif

                    <svg class="accordion-chevron" :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            {{-- Accordion Body --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="accordion-body">

                <div class="unit-grid">
                    @foreach($blockUnits as $unit)
                        @php
                            $isComplete  = $unit->status_legalitas === 'Lengkap';
                            $borderClass = $isComplete ? 'unit-card' : 'unit-card unit-card-missing';
                        @endphp

                        {{--
                            TIPS LIVEWIRE:
                            wire:key membantu Livewire melacak setiap kartu unit di dalam loop.
                            Ini penting agar DOM tidak kacau saat data di-refresh.
                        --}}
                        <div wire:key="unit-{{ $unit->id }}" class="{{ $borderClass }}">

                            {{-- Card Header --}}
                            <div class="unit-card-header">
                                <div class="unit-card-header-row">
                                    <h3 class="unit-number">Unit {{ $unit->unit_number }}</h3>
                                    @if($isComplete)
                                        <span class="badge badge-green badge-sm">
                                            <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Complete
                                        </span>
                                    @else
                                        <span class="badge badge-red badge-sm">
                                            <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Missing
                                        </span>
                                    @endif
                                </div>
                                <p class="unit-meta">Status: <span class="font-medium text-gray-700">{{ $unit->status_penjualan }}</span></p>
                            </div>

                            {{-- Documents List --}}
                            <div class="unit-card-body">
                                @foreach($unit->legalDocuments as $doc)
                                    <div class="doc-row">
                                        <div class="doc-info">
                                            <div class="doc-icon-wrap">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div class="doc-text">
                                                <p class="doc-name">{{ $doc->document_name }}</p>
                                                <p class="doc-number">No: {{ $doc->document_number ?? '-' }}</p>
                                                <p class="doc-date">{{ $doc->created_at?->format('d M Y') ?? '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="doc-actions">
                                            {{-- Preview (tetap pakai route HTTP biasa — tidak bisa via Livewire) --}}
                                            <a href="{{ route('legalitas.preview', $doc->id) }}"
                                               target="_blank"
                                               class="doc-btn doc-btn-blue"
                                               title="Preview PDF">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>

                                            {{-- Download (tetap pakai route HTTP biasa) --}}
                                            <a href="{{ route('legalitas.download', $doc->id) }}"
                                               class="doc-btn doc-btn-green"
                                               title="Download PDF">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                            </a>

                                            {{-- Hapus — hanya Admin, pakai wire:click --}}
                                            @if(auth()->user()->role === 'Admin')
                                                <button
                                                    wire:click="deleteDocument({{ $doc->id }})"
                                                    wire:confirm="Yakin ingin menghapus dokumen '{{ $doc->document_name }}'? Tindakan ini tidak dapat dibatalkan."
                                                    class="doc-btn doc-btn-red"
                                                    title="Hapus Dokumen">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Upload Form --}}
                                {{--
                                    TIPS LIVEWIRE:
                                    wire:submit.prevent mencegah reload halaman dan memanggil uploadDocument() di backend.
                                    wire:model menghubungkan setiap input dengan properti di komponen PHP.
                                --}}
                                <div class="upload-zone {{ $isComplete ? 'upload-zone-normal' : 'upload-zone-alert' }}">

                                    @if(!$isComplete)
                                        <div class="upload-alert-row">
                                            <div class="upload-alert-icon">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="upload-alert-title">Required document missing</p>
                                                <p class="upload-alert-sub">Please upload certificates or permits.</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="upload-alert-row">
                                            <div class="upload-alert-icon upload-alert-icon-green">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="upload-alert-title text-gray-700">Add more documents</p>
                                                <p class="upload-alert-sub">You can upload additional files.</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{--
                                        Tombol toggle form upload: wire:click memanggil openUploadForm() di backend
                                        untuk mengubah $uploadingUnitId. Alpine.js tidak diperlukan di sini karena
                                        Livewire mengelola state ini.
                                    --}}
                                    <button type="button"
                                            wire:click="openUploadForm({{ $unit->id }})"
                                            class="upload-toggle-btn {{ $uploadingUnitId === $unit->id ? 'upload-toggle-btn-active' : '' }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="{{ $uploadingUnitId === $unit->id ? 'M6 18L18 6M6 6l12 12' : 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12' }}"/>
                                        </svg>
                                        {{ $uploadingUnitId === $unit->id ? 'Batal' : 'Upload Dokumen' }}
                                    </button>

                                    @if($uploadingUnitId === $unit->id)
                                        <form wire:submit.prevent="uploadDocument"
                                              class="upload-form">

                                            <div class="form-group">
                                                <label class="form-label">Jenis Dokumen <span class="required-mark">*</span></label>
                                                <input type="text"
                                                       wire:model="uploadDocumentName"
                                                       class="form-input"
                                                       placeholder="Contoh: SHM / IMB / PBB"
                                                       value="Sertifikat Tanah / IMB">
                                                @error('uploadDocumentName')
                                                    <p class="form-error">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Nomor Dokumen <span class="required-mark">*</span></label>
                                                <input type="text"
                                                       wire:model="uploadDocumentNumber"
                                                       class="form-input"
                                                       placeholder="Masukkan No. Dokumen">
                                                @error('uploadDocumentNumber')
                                                    <p class="form-error">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">File PDF <span class="required-mark">*</span></label>
                                                <input type="file"
                                                       wire:model="uploadFile"
                                                       accept="application/pdf"
                                                       class="form-file-input">
                                                {{-- Loading indicator saat file sedang diupload ke Livewire --}}
                                                <div wire:loading wire:target="uploadFile" class="upload-loading">
                                                    <svg class="animate-spin h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                                    </svg>
                                                    Memproses file...
                                                </div>
                                                @error('uploadFile')
                                                    <p class="form-error">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <button type="submit"
                                                    class="upload-submit-btn {{ $isComplete ? 'upload-submit-gray' : 'upload-submit-green' }}"
                                                    wire:loading.attr="disabled">
                                                <svg wire:loading.remove wire:target="uploadDocument" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                                <svg wire:loading wire:target="uploadDocument" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                                </svg>
                                                <span wire:loading.remove wire:target="uploadDocument">Upload Dokumen</span>
                                                <span wire:loading wire:target="uploadDocument">Mengupload...</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Footer --}}
                            <div class="unit-card-footer">
                                @if($isComplete)
                                    <span class="footer-note">All docs verified</span>
                                @else
                                    <span class="footer-note">Awaiting completion</span>
                                    @if($unit->legalDocuments->count() > 0)
                                        {{--
                                            TIPS LIVEWIRE:
                                            wire:click memanggil markAsComplete() di PHP, mengubah status unit
                                            di database, lalu render() akan berjalan ulang secara otomatis.
                                        --}}
                                        <button
                                            wire:click="markAsComplete({{ $unit->id }})"
                                            wire:loading.attr="disabled"
                                            class="footer-complete-btn">
                                            Tandai Selesai
                                        </button>
                                    @endif
                                @endif
                            </div>

                        </div>{{-- /unit-card --}}
                    @endforeach
                </div>{{-- /unit-grid --}}

            </div>{{-- /accordion-body --}}
        </div>{{-- /accordion --}}

    @empty
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3>No properties found</h3>
            <p>There are currently no housing units in the database.</p>
        </div>
    @endforelse

</div>{{-- /legal-wrapper --}}


{{-- ═══════════════════════════════════════════════════════
     STYLES
═══════════════════════════════════════════════════════ --}}
<style>
/* ── Wrapper ── */
.legal-wrapper {
    padding: 1.75rem 1rem 4rem;
    max-width: 1280px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* ── Page Header ── */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.page-title {
    font-size: 1.625rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.5px;
}
.page-subtitle {
    margin-top: 0.2rem;
    font-size: 0.8125rem;
    color: #94a3b8;
}
.search-wrap {
    position: relative;
    width: 100%;
    max-width: 280px;
}
.search-icon {
    position: absolute;
    top: 50%;
    left: 0.75rem;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    color: #94a3b8;
}
.search-input {
    width: 100%;
    padding: 0.5rem 1rem 0.5rem 2.25rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.875rem;
    background: #f8fafc;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.search-input:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
    background: #fff;
}

/* ── Flash ── */
.flash-alert {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    background: #f0fdf4;
    border: 1px solid #86efac;
    border-left: 4px solid #22c55e;
    padding: 0.875rem 1.125rem;
    border-radius: 10px;
}
.flash-alert-error {
    background: #fef2f2;
    border-color: #fca5a5;
    border-left-color: #ef4444;
}
.flash-icon { width: 18px; height: 18px; color: #16a34a; flex-shrink: 0; }
.flash-alert-error .flash-icon { color: #dc2626; }
.flash-text { font-size: 0.875rem; font-weight: 600; color: #15803d; }
.flash-alert-error .flash-text { color: #b91c1c; }

/* ── Stats Grid ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}
@media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; } }

.stat-card {
    background: #fff;
    border: 1.5px solid #e8eaed;
    border-radius: 14px;
    padding: 1.125rem 1.25rem 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s, transform 0.2s;
}
.stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); transform: translateY(-1px); }
.stat-compliant { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-color: #bbf7d0; }
.stat-missing   { background: linear-gradient(135deg, #fff7f7 0%, #fee2e2 100%); border-color: #fecaca; }

.stat-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.125rem; }
.stat-icon-wrap {
    width: 28px; height: 28px;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.stat-icon-wrap svg { width: 14px; height: 14px; }
.stat-icon-gray  { background: #f1f5f9; color: #64748b; }
.stat-icon-green { background: #dcfce7; color: #16a34a; }
.stat-icon-amber { background: #fef3c7; color: #d97706; }
.stat-icon-red   { background: #fee2e2; color: #dc2626; }

.stat-label { font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.6px; }
.stat-label-green { color: #16a34a; }
.stat-label-amber { color: #d97706; }
.stat-label-red   { color: #dc2626; }

.stat-value { font-size: 2rem; font-weight: 800; color: #0f172a; line-height: 1; letter-spacing: -1px; }
.stat-value-green { color: #16a34a; }
.stat-value-amber { color: #d97706; }
.stat-value-red   { color: #dc2626; }

.stat-sub { font-size: 0.72rem; color: #94a3b8; font-weight: 500; margin-top: 0.125rem; }
.stat-sub-green { color: #16a34a; font-weight: 600; }
.stat-sub-amber { color: #d97706; }
.stat-sub-red   { color: #dc2626; font-weight: 600; }

/* ── Loading Bar ── */
.loading-bar {
    height: 3px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
}
.loading-bar-inner {
    height: 100%;
    width: 40%;
    background: linear-gradient(90deg, #10b981, #059669);
    border-radius: 999px;
    animation: loading-slide 1.2s ease-in-out infinite;
}
@keyframes loading-slide {
    0%   { transform: translateX(-100%); }
    100% { transform: translateX(350%); }
}

/* ── Accordion ── */
.accordion {
    background: #fff;
    border: 1.5px solid #e8eaed;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.accordion-header {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 1rem 1.25rem;
    text-align: left;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
}
.accordion-header:hover { background: #f8fafc; }
.accordion-icon-wrap {
    width: 36px; height: 36px;
    background: #ecfdf5;
    color: #059669;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.accordion-icon-wrap svg { width: 18px; height: 18px; }
.accordion-info { flex: 1; min-width: 0; }
.accordion-title { font-size: 0.9375rem; font-weight: 700; color: #0f172a; }
.accordion-sub   { font-size: 0.75rem; color: #64748b; margin-top: 0.125rem; }
.accordion-badges { display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0; }
.accordion-chevron { width: 18px; height: 18px; color: #94a3b8; transition: transform 0.2s; }

.accordion-body { border-top: 1.5px solid #f1f5f9; }

/* ── Badges ── */
.badge {
    display: inline-flex; align-items: center; gap: 0.25rem;
    font-size: 0.65rem; font-weight: 700;
    padding: 0.25rem 0.625rem;
    border-radius: 999px;
    text-transform: uppercase; letter-spacing: 0.5px;
}
.badge-sm { font-size: 0.6rem; padding: 0.2rem 0.5rem; }
.badge-green { background: #dcfce7; color: #15803d; }
.badge-red   { background: #fee2e2; color: #b91c1c; }
.badge-icon  { width: 10px; height: 10px; flex-shrink: 0; }

/* ── Unit Grid ── */
.unit-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 1rem;
    padding: 1rem;
}
@media (min-width: 640px)  { .unit-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 1024px) { .unit-grid { grid-template-columns: repeat(3, 1fr); } }
@media (min-width: 1280px) { .unit-grid { grid-template-columns: repeat(4, 1fr); } }

/* ── Unit Card ── */
.unit-card {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}
.unit-card-missing {
    border-left: 4px solid #ef4444;
    border-color: #fecaca;
}
.unit-card-header {
    padding: 1rem;
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
}
.unit-card-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.375rem;
}
.unit-number { font-size: 1rem; font-weight: 700; color: #0f172a; }
.unit-meta   { font-size: 0.72rem; color: #64748b; }

.unit-card-body {
    padding: 0.875rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
}

/* ── Document Row ── */
.doc-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.625rem 0.75rem;
    background: #fff;
    transition: border-color 0.15s;
}
.doc-row:hover { border-color: #a7f3d0; }
.doc-info { display: flex; align-items: flex-start; gap: 0.5rem; flex: 1; min-width: 0; }
.doc-icon-wrap {
    background: #ecfdf5;
    color: #059669;
    padding: 0.375rem;
    border-radius: 6px;
    flex-shrink: 0;
}
.doc-icon-wrap svg { width: 16px; height: 16px; }
.doc-text { min-width: 0; }
.doc-name   { font-size: 0.8125rem; font-weight: 600; color: #1e293b; line-height: 1.3; }
.doc-number { font-size: 0.7rem; color: #64748b; margin-top: 0.1rem; }
.doc-date   { font-size: 0.65rem; color: #94a3b8; margin-top: 0.1rem; }

.doc-actions { display: flex; gap: 0.25rem; flex-shrink: 0; }
.doc-btn {
    padding: 0.375rem;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: color 0.15s, background 0.15s;
    color: #94a3b8;
    background: transparent;
    display: flex; align-items: center;
}
.doc-btn svg { width: 16px; height: 16px; }
.doc-btn-blue:hover  { color: #2563eb; background: #eff6ff; }
.doc-btn-green:hover { color: #059669; background: #ecfdf5; }
.doc-btn-red:hover   { color: #dc2626; background: #fef2f2; }

/* ── Upload Zone ── */
.upload-zone {
    border: 1.5px dashed #cbd5e1;
    border-radius: 10px;
    padding: 0.875rem;
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
    margin-top: 0.25rem;
}
.upload-zone-alert { border-color: #fca5a5; background: #fff7f7; }
.upload-zone-normal { border-color: #cbd5e1; background: #f8fafc; }

.upload-alert-row { display: flex; align-items: flex-start; gap: 0.5rem; }
.upload-alert-icon {
    padding: 0.25rem;
    background: #fff;
    border: 1px solid #fecaca;
    border-radius: 5px;
    color: #ef4444;
    flex-shrink: 0;
}
.upload-alert-icon svg { width: 14px; height: 14px; }
.upload-alert-icon-green { border-color: #a7f3d0; color: #059669; }
.upload-alert-title { font-size: 0.75rem; font-weight: 700; color: #b91c1c; }
.upload-alert-sub   { font-size: 0.65rem; color: #ef4444; margin-top: 0.1rem; }

.upload-toggle-btn {
    display: inline-flex; align-items: center; gap: 0.375rem;
    font-size: 0.75rem; font-weight: 600;
    padding: 0.5rem 0.875rem;
    border-radius: 7px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s;
    align-self: flex-start;
}
.upload-toggle-btn:hover { background: #f1f5f9; border-color: #94a3b8; }
.upload-toggle-btn svg { width: 14px; height: 14px; }
.upload-toggle-btn-active { background: #fef2f2; border-color: #fca5a5; color: #b91c1c; }

/* ── Upload Form ── */
.upload-form {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.875rem;
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
}
.form-group { display: flex; flex-direction: column; gap: 0.25rem; }
.form-label { font-size: 0.65rem; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.5px; }
.required-mark { color: #ef4444; }
.form-input {
    width: 100%;
    padding: 0.4rem 0.625rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 6px;
    font-size: 0.8125rem;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.form-input:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 2px rgba(16,185,129,0.15);
}
.form-file-input {
    font-size: 0.75rem;
    color: #64748b;
    width: 100%;
}
.form-file-input::file-selector-button {
    margin-right: 0.5rem;
    padding: 0.25rem 0.5rem;
    border-radius: 5px;
    border: none;
    font-size: 0.7rem;
    font-weight: 600;
    background: #ecfdf5;
    color: #059669;
    cursor: pointer;
}
.form-file-input::file-selector-button:hover { background: #d1fae5; }
.form-error { font-size: 0.7rem; color: #dc2626; margin-top: 0.1rem; }

.upload-loading {
    display: flex; align-items: center; gap: 0.375rem;
    font-size: 0.75rem; color: #059669; font-weight: 500;
}
.upload-submit-btn {
    width: 100%;
    display: flex; align-items: center; justify-content: center; gap: 0.375rem;
    padding: 0.5rem;
    border: none;
    border-radius: 7px;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    transition: opacity 0.15s, transform 0.1s;
}
.upload-submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }
.upload-submit-btn svg { width: 14px; height: 14px; }
.upload-submit-green { background: #059669; }
.upload-submit-green:hover:not(:disabled) { background: #047857; }
.upload-submit-gray  { background: #1e293b; }
.upload-submit-gray:hover:not(:disabled)  { background: #0f172a; }

/* ── Card Footer ── */
.unit-card-footer {
    padding: 0.625rem 1rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.footer-note { font-size: 0.7rem; color: #94a3b8; }
.footer-complete-btn {
    font-size: 0.7rem;
    font-weight: 700;
    color: #fff;
    background: #059669;
    border: none;
    border-radius: 6px;
    padding: 0.3rem 0.75rem;
    cursor: pointer;
    transition: background 0.15s;
}
.footer-complete-btn:hover { background: #047857; }
.footer-complete-btn:disabled { opacity: 0.6; cursor: not-allowed; }

/* ── Empty State ── */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    text-align: center;
}
.empty-state svg { width: 56px; height: 56px; color: #cbd5e1; margin-bottom: 1rem; }
.empty-state h3  { font-size: 1rem; font-weight: 700; color: #1e293b; }
.empty-state p   { font-size: 0.875rem; color: #64748b; margin-top: 0.5rem; max-width: 320px; }
</style>
