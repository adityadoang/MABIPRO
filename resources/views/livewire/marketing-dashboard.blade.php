<div class="dashboard-wrapper">
    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Sales Marketing</h1>
            <p class="page-subtitle">Manage unit statuses and track performance across blocks.</p>
        </div>
        <div class="page-header-right">
            <div class="date-badge" id="date-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="14" height="14">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span id="current-date-label">{{ now()->translatedFormat('M Y') }}</span>
            </div>
        </div>
    </div>
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
    <div class="stats-grid">
        <div class="stat-card stat-total" id="stat-total">
            <div class="stat-header">
                <div class="stat-icon-wrap stat-icon-gray">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="stat-label">TOTAL UNITS</span>
            </div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card stat-terjual" id="stat-terjual">
            <div class="stat-header">
                <div class="stat-icon-wrap stat-icon-green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="stat-label stat-label-green">TERJUAL</span>
            </div>
            <div class="stat-value stat-value-green">{{ $stats['terjual'] }}</div>
            <div class="stat-pct">{{ $stats['pct_terjual'] }}% of total</div>
        </div>
        <div class="stat-card stat-belum" id="stat-belum">
            <div class="stat-header">
                <div class="stat-icon-wrap stat-icon-blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="stat-label">BELUM TERJUAL</span>
            </div>
            <div class="stat-value">{{ $stats['belum'] }}</div>
            <div class="stat-pct">{{ $stats['pct_belum'] }}% of total</div>
        </div>
        <div class="stat-card stat-dp" id="stat-dp">
            <div class="stat-header">
                <div class="stat-icon-wrap stat-icon-amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <span class="stat-label">SUDAH DP</span>
            </div>
            <div class="stat-value">{{ $stats['sudah_dp'] }}</div>
            <div class="stat-pct">{{ $stats['pct_dp'] }}% of total</div>
        </div>
    </div>
    <div class="split-panel">
        <div class="panel-left" id="panel-blocks">
            <div class="panel-title-row">
                <div class="panel-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h2 class="panel-title">Housing Blocks</h2>
            </div>
            <div class="blocks-list">
                @forelse($blocks as $block)
                    @php
                        $blockTotal   = $block->units_count ?? 0;
                        $blockTerjual = $block->units_terjual_count ?? 0;
                        $blockDp      = $block->units_dp_count ?? 0;
                        $blockSold    = $blockTerjual + $blockDp;
                        $blockPct     = $blockTotal > 0 ? round(($blockTerjual / $blockTotal) * 100) : 0;
                        $isActive     = $selectedBlockId == $block->id;
                        // Generate a gradient color per block (rotate through palettes)
                        $gradients = [
                            'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
                            'linear-gradient(135deg, #064e3b 0%, #10b981 100%)',
                            'linear-gradient(135deg, #7c2d12 0%, #f97316 100%)',
                            'linear-gradient(135deg, #4c1d95 0%, #8b5cf6 100%)',
                            'linear-gradient(135deg, #831843 0%, #ec4899 100%)',
                            'linear-gradient(135deg, #1e3a5f 0%, #0ea5e9 100%)',
                        ];
                        $blockGradient = $gradients[($block->id - 1) % count($gradients)];
                    @endphp
                    <div wire:key="block-{{ $block->id }}"
                         wire:click="selectBlock({{ $block->id }})"
                         id="block-card-{{ $block->id }}"
                         class="block-card {{ $isActive ? 'block-card-active' : '' }}"
                         role="button"
                         tabindex="0"
                         aria-pressed="{{ $isActive ? 'true' : 'false' }}"
                         aria-label="Pilih {{ $block->nama_blok }}">
                        <div class="block-thumb" style="background: {{ $blockGradient }};">
                            <div class="block-thumb-pattern"></div>
                            <div class="block-thumb-buildings">
                                <svg fill="none" stroke="rgba(255,255,255,0.7)" viewBox="0 0 64 32" width="64" height="32">
                                    <rect x="2" y="8" width="12" height="24" rx="1" stroke-width="1.5"/>
                                    <rect x="5" y="12" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="5" y="18" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="9" y="12" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="9" y="18" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="18" y="4" width="14" height="28" rx="1" stroke-width="1.5"/>
                                    <rect x="21" y="8" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="21" y="14" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="21" y="20" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="27" y="8" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="27" y="14" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="36" y="10" width="10" height="22" rx="1" stroke-width="1.5"/>
                                    <rect x="39" y="14" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="39" y="20" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="50" y="6" width="12" height="26" rx="1" stroke-width="1.5"/>
                                    <rect x="53" y="10" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="53" y="16" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <rect x="53" y="22" width="3" height="3" rx="0.5" fill="rgba(255,255,255,0.4)" stroke="none"/>
                                    <line x1="0" y1="32" x2="64" y2="32" stroke-width="1"/>
                                </svg>
                            </div>
                            <div class="block-thumb-overlay">
                                <span class="block-thumb-name">{{ $block->nama_blok }}</span>
                            </div>
                        </div>
                        <div class="block-info">
                            <div class="block-progress-row">
                                <span class="block-progress-label">Progress</span>
                                <span class="block-progress-count">{{ $blockTerjual }} / {{ $blockTotal }} Units</span>
                            </div>
                            <div class="block-progress-bar-wrap">
                                <div class="block-progress-bar"
                                     style="width: {{ $blockPct }}%; background: {{ $isActive ? '#22c55e' : '#94a3b8' }};">
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-blocks">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <p>Belum ada data blok</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="panel-right" id="panel-units">
            <div class="unit-panel-header">
                <div>
                    <h2 class="unit-panel-title">Unit Management</h2>
                    @if($selectedBlock)
                        <p class="unit-panel-sub">
                            Currently managing:
                            <strong>{{ $selectedBlock->nama_blok }}</strong>
                        </p>
                    @endif
                </div>
                <div class="unit-search-wrap">
                    <svg class="unit-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        wire:model.live.debounce.300ms="searchUnit"
                        type="text"
                        placeholder="Search Unit ID..."
                        class="unit-search-input"
                        id="unit-search-input"
                        aria-label="Cari unit"
                    >
                </div>
            </div>
            <div class="unit-list-wrap">
                @if(!$selectedBlock)
                    <div class="unit-empty-state">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/>
                        </svg>
                        <p>Pilih blok di sebelah kiri untuk melihat unit</p>
                    </div>
                @elseif($filteredUnits->isEmpty())
                    <div class="unit-empty-state">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Tidak ada unit ditemukan</p>
                    </div>
                @else
                    <div class="unit-list">
                        @foreach($filteredUnits as $unit)
                            @php
                                // Menentukan warna badge (label status) & warna baris
                                $statusClass = 'badge-belum'; // Nilai default
                                $rowAccent   = '';            // Nilai default
                                if ($unit->status_penjualan === 'Terjual') {
                                    $statusClass = 'badge-terjual';
                                    $rowAccent   = 'unit-row-green';
                                } elseif ($unit->status_penjualan === 'Sudah DP') {
                                    $statusClass = 'badge-dp';
                                    $rowAccent   = 'unit-row-amber';
                                }
                            @endphp
                            <div wire:key="unit-{{ $unit->id }}" class="unit-row {{ $rowAccent }}" id="unit-row-{{ $unit->id }}">
                                <div class="unit-row-id">
                                    <span class="unit-row-number">{{ $unit->unit_number }}</span>
                                    @if($unit->harga_unit)
                                        <span class="unit-row-price">Rp {{ number_format($unit->harga_unit, 0, ',', '.') }}</span>
                                    @else
                                        <span class="unit-row-price-empty">Harga belum diset</span>
                                    @endif
                                </div>
                                @if($unit->payment_method)
                                    <div class="unit-row-meta">
                                        <span class="unit-row-method">{{ $unit->payment_method }}</span>
                                        @if($unit->payment_method === 'KPR' && $unit->kpr_duration_months)
                                            <span class="unit-row-tenor">{{ $unit->kpr_duration_months }} bln</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="unit-row-meta"></div>
                                @endif
                                <div class="unit-row-actions">
                                    <select
                                        wire:change="updateStatus({{ $unit->id }}, $event.target.value)"
                                        class="unit-status-select {{ $statusClass }}"
                                        id="status-select-{{ $unit->id }}"
                                        aria-label="Status unit {{ $unit->unit_number }}"
                                    >
                                        <option value="Belum Terjual" {{ $unit->status_penjualan === 'Belum Terjual' ? 'selected' : '' }}>
                                            Belum Terjual
                                        </option>
                                        <option value="Sudah DP" {{ $unit->status_penjualan === 'Sudah DP' ? 'selected' : '' }}>
                                            Sudah DP
                                        </option>
                                        <option value="Terjual" {{ $unit->status_penjualan === 'Terjual' ? 'selected' : '' }}>
                                            Terjual
                                        </option>
                                    </select>
                                    @if(in_array($unit->status_penjualan, ['Sudah DP', 'Terjual']))
                                        <button
                                            wire:click="openPaymentModal({{ $unit->id }})"
                                            id="detail-btn-{{ $unit->id }}"
                                            class="unit-detail-btn"
                                            title="Detail Pembayaran"
                                            aria-label="Detail pembayaran unit {{ $unit->unit_number }}"
                                        >
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if($isPaymentModalOpen)
    <div class="modal-overlay" id="payment-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-backdrop" wire:click="closePaymentModal"></div>
        <div class="modal-panel modal-panel-wide">
            <form wire:submit.prevent="savePaymentDetails">
                <div class="modal-header">
                    <div class="modal-header-left">
                        <div class="modal-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="modal-title" id="modal-title">Detail Pembayaran Unit</h3>
                            <p class="modal-subtitle">Lengkapi informasi metode dan simulasi pembayaran.</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closePaymentModal" class="modal-close" id="modal-close-btn" aria-label="Tutup modal">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" for="harga-unit">Harga Unit (Rp) @if(auth()->check() && auth()->user()->isAdmin())<span class="required-mark">*</span>@endif</label>
                        <div class="input-with-addon @if(!auth()->check() || !auth()->user()->isAdmin()) bg-surface-container opacity-70 @endif">
                            <span class="input-addon addon-left-label">Rp</span>
                            <input id="harga-unit" type="number" wire:model.live="hargaUnit"
                                   class="form-input" placeholder="500000000" min="1"
                                   @if(!auth()->check() || !auth()->user()->isAdmin()) disabled @endif>
                        </div>
                        @error('hargaUnit') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="payment-method-select">Metode Pembayaran</label>
                        <select id="payment-method-select" wire:model.live="paymentMethod" class="form-select">
                            <option value="">— Pilih Metode —</option>
                            <option value="Cash">Cash / Tunai</option>
                            <option value="KPR">KPR (Kredit Pemilikan Rumah)</option>
                        </select>
                        @error('paymentMethod') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    @if($paymentMethod === 'KPR')
                        <div class="form-section">
                            <div class="form-section-title">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Informasi Unit &amp; KPR
                            </div>
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label class="form-label" for="kpr-type">Jenis KPR <span class="required-mark">*</span></label>
                                    <select id="kpr-type" wire:model.live="kprType" class="form-select">
                                        <option value="non_subsidi">Non-Subsidi (Komersial)</option>
                                        <option value="subsidi">Subsidi (BTN — FLPP)</option>
                                    </select>
                                    @error('kprType') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="bank-name">Bank / Lembaga Pembiayaan <span class="required-mark">*</span></label>
                                    <input id="bank-name" type="text" wire:model="bankName"
                                           class="form-input" placeholder="Contoh: Bank BTN, BRI, Mandiri...">
                                    @error('bankName') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="akad-date">Tanggal Akad Kredit</label>
                                    <input id="akad-date" type="date" wire:model="akadDate" class="form-input">
                                    @error('akadDate') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-section">
                            <div class="form-section-title">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Down Payment (DP)
                                <span class="section-note">Nominal ↔ Persentase dihitung otomatis</span>
                            </div>
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label class="form-label" for="dp-amount">Nominal DP (Rp) <span class="required-mark">*</span></label>
                                    <div class="input-with-addon">
                                        <span class="input-addon addon-left-label">Rp</span>
                                        <input id="dp-amount" type="number" wire:model.live="dpAmount"
                                               class="form-input" placeholder="0" min="0">
                                    </div>
                                    @error('dpAmount') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="dp-percentage">Persentase DP (%) <span class="required-mark">*</span></label>
                                    <div class="input-with-addon">
                                        <input id="dp-percentage" type="number" wire:model.live="dpPercentage"
                                               class="form-input" placeholder="10" min="0" max="100" step="0.01">
                                        <span class="input-addon">%</span>
                                    </div>
                                    @error('dpPercentage') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            @if($pokokKredit > 0)
                            <div class="calc-info-row">
                                <span class="calc-info-label">Pokok Kredit (Harga − DP)</span>
                                <span class="calc-info-value">Rp {{ number_format($pokokKredit, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="form-section">
                            <div class="form-section-title">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Kredit
                            </div>
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label class="form-label" for="kpr-duration">Tenor <span class="required-mark">*</span></label>
                                    <div class="input-with-addon">
                                        <input id="kpr-duration" type="number" wire:model.live="kprDurationMonths"
                                               class="form-input" placeholder="120" min="12" max="360">
                                        <span class="input-addon">Bulan</span>
                                    </div>
                                    @if($kprDurationMonths >= 12)
                                        <p class="form-hint">= {{ round($kprDurationMonths / 12, 1) }} Tahun</p>
                                    @endif
                                    @error('kprDurationMonths') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Cicilan / Bulan</label>
                                    @if($monthlyInstallment > 0)
                                        <div class="calc-info-row">
                                            <span class="calc-info-label">Cicilan / Bulan (Pokok ÷ Tenor)</span>
                                            <span class="calc-info-value" style="font-size:1.1rem; color:#16a34a;">
                                                Rp {{ number_format($monthlyInstallment, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <p class="form-hint">Dihitung otomatis: Pokok Kredit ÷ Tenor bulan.</p>
                                    @else
                                        <p class="form-hint">Isi Harga Unit, DP, dan Tenor untuk melihat estimasi cicilan.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif($paymentMethod === 'Cash')
                        <div class="form-section mt-4">
                            <div class="form-section-title">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Informasi Pembayaran Cash
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="cash-amount">Jumlah Dibayar (Rp) <span class="required-mark">*</span></label>
                                <div class="input-with-addon bg-surface-container opacity-70">
                                    <span class="input-addon">Rp</span>
                                    <input id="cash-amount" type="number" wire:model.live="amountPaid" class="form-input" readonly disabled>
                                </div>
                                <p class="form-note">Untuk pembayaran Cash, jumlah dibayar otomatis disesuaikan dengan Harga Unit.</p>
                            </div>
                        </div>
                    @endif
                    @if($paymentMethod)
                    <div class="form-group">
                        <label class="form-label">Upload Bukti / Dokumen</label>
                        <label for="file-upload" class="file-upload-area">
                            <svg class="file-upload-icon" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            @if($paymentProof)
                                <p class="file-upload-ready">✓ {{ $paymentProof->getClientOriginalName() }}</p>
                            @else
                                <p class="file-upload-hint"><span class="file-upload-link">Pilih File</span> atau drag &amp; drop</p>
                                <p class="file-upload-meta">PNG, JPG, PDF — maks. 5 MB</p>
                            @endif
                            <input id="file-upload" wire:model="paymentProof" type="file" class="sr-only">
                        </label>
                        <div wire:loading wire:target="paymentProof" class="upload-loading">
                            <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Sedang mengunggah...
                        </div>
                        @error('paymentProof') <p class="form-error">{{ $message }}</p> @enderror
                        @php $unitTemp = \App\Models\Unit::find($selectedUnitId); @endphp
                        @if($unitTemp && $unitTemp->payment_proof_path)
                            <div class="existing-file">
                                <p class="existing-file-label">Bukti Terlampir:</p>
                                <a href="{{ asset('storage/' . $unitTemp->payment_proof_path) }}" target="_blank" class="existing-file-link">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat Dokumen
                                </a>
                            </div>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closePaymentModal" class="btn-secondary">Tutup</button>
                    <button type="submit" class="btn-primary" id="save-payment-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@push('styles')
<style>
/* ── Wrapper ── */
.dashboard-wrapper {
    padding: 1.75rem 1.5rem 3rem;
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
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
    line-height: 1.2;
}
.page-subtitle {
    margin-top: 0.25rem;
    font-size: 0.8125rem;
    color: #94a3b8;
    font-weight: 400;
}
.date-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.4rem 0.875rem;
    background: #fff;
    border: 1.5px solid #e8eaed;
    border-radius: 8px;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #475569;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
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
    box-shadow: 0 2px 8px rgba(34,197,94,0.08);
}
.flash-icon { width: 18px; height: 18px; color: #16a34a; flex-shrink: 0; }
.flash-text { font-size: 0.875rem; font-weight: 600; color: #15803d; }
/* ── Stats Grid ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}
@media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 500px) { .stats-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; } }
.stat-card {
    background: #fff;
    border: 1.5px solid #e8eaed;
    border-radius: 14px;
    padding: 1.125rem 1.25rem 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s, transform 0.2s;
}
.stat-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transform: translateY(-1px);
}
.stat-terjual {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-color: #bbf7d0;
}
.stat-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}
.stat-icon-wrap {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-icon-wrap svg { width: 14px; height: 14px; }
.stat-icon-gray  { background: #f1f5f9; color: #64748b; }
.stat-icon-green { background: #dcfce7; color: #16a34a; }
.stat-icon-blue  { background: #e0f2fe; color: #0284c7; }
.stat-icon-amber { background: #fef3c7; color: #d97706; }
.stat-label {
    font-size: 0.65rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}
.stat-label-green { color: #16a34a; }
.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    letter-spacing: -1px;
}
.stat-value-green { color: #16a34a; }
.stat-pct {
    font-size: 0.72rem;
    color: #94a3b8;
    font-weight: 500;
}
/* ── Split Panel ── */
.split-panel {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 1rem;
    align-items: start;
}
@media (max-width: 768px) {
    .split-panel {
        grid-template-columns: 1fr;
    }
}
/* ── Left Panel: Housing Blocks ── */
.panel-left {
    background: #fff;
    border: 1.5px solid #e8eaed;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}
.panel-title-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.125rem 0.875rem;
    border-bottom: 1px solid #f1f5f9;
}
.panel-icon-wrap {
    width: 28px; height: 28px;
    background: #f1f5f9;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.panel-icon-wrap svg { width: 14px; height: 14px; color: #64748b; }
.panel-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1a1d23;
}
.blocks-list {
    padding: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
    max-height: 520px;
    overflow-y: auto;
}
/* ── Block Card ── */
.block-card {
    border: 1.5px solid #e8eaed;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #fff;
}
.block-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.block-card-active {
    border-color: #22c55e !important;
    box-shadow: 0 0 0 3px rgba(34,197,94,0.15);
}
.block-thumb {
    position: relative;
    height: 80px;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
}
.block-thumb-pattern {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.08) 0%, transparent 50%),
                      radial-gradient(circle at 80% 20%, rgba(255,255,255,0.06) 0%, transparent 50%);
}
.block-thumb-buildings {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0.85;
}
.block-thumb-overlay {
    position: relative;
    z-index: 1;
    padding: 0 0.625rem 0.5rem;
    width: 100%;
}
.block-thumb-name {
    font-size: 0.9rem;
    font-weight: 800;
    color: #fff;
    text-shadow: 0 1px 4px rgba(0,0,0,0.3);
    letter-spacing: -0.2px;
}
.block-info {
    padding: 0.625rem 0.75rem;
    background: #fafbfc;
    border-top: 1px solid rgba(0,0,0,0.04);
}
.block-progress-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.375rem;
}
.block-progress-label {
    font-size: 0.68rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.block-progress-count {
    font-size: 0.7rem;
    font-weight: 700;
    color: #64748b;
}
.block-progress-bar-wrap {
    height: 5px;
    background: #e8eaed;
    border-radius: 99px;
    overflow: hidden;
}
.block-progress-bar {
    height: 100%;
    border-radius: 99px;
    transition: width 0.5s ease, background 0.3s;
    min-width: 4px;
}
.empty-blocks {
    text-align: center;
    padding: 2rem 1rem;
    color: #94a3b8;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}
.empty-blocks svg { width: 32px; height: 32px; opacity: 0.5; }
.empty-blocks p { font-size: 0.78rem; font-weight: 500; }
/* ── Right Panel: Unit Management ── */
.panel-right {
    background: #fff;
    border: 1.5px solid #e8eaed;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}
.unit-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.25rem 0.875rem;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
}
.unit-panel-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1a1d23;
}
.unit-panel-sub {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 0.125rem;
}
.unit-panel-sub strong { color: #475569; }
.unit-search-wrap {
    position: relative;
    flex-shrink: 0;
}
.unit-search-icon {
    position: absolute;
    left: 0.625rem;
    top: 50%;
    transform: translateY(-50%);
    width: 14px;
    height: 14px;
    color: #94a3b8;
    pointer-events: none;
}
.unit-search-input {
    padding: 0.4375rem 0.75rem 0.4375rem 2rem;
    border: 1.5px solid #e8eaed;
    border-radius: 8px;
    font-size: 0.8125rem;
    color: #374151;
    background: #f8fafc;
    transition: all 0.2s;
    width: 180px;
    font-family: 'Inter', sans-serif;
}
.unit-search-input::placeholder { color: #b0bac9; }
.unit-search-input:focus {
    outline: none;
    border-color: #22c55e;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(34,197,94,0.1);
}
.unit-list-wrap {
    max-height: 480px;
    overflow-y: auto;
}
.unit-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.625rem;
    padding: 3rem 2rem;
    color: #94a3b8;
    text-align: center;
}
.unit-empty-state svg { width: 36px; height: 36px; opacity: 0.4; }
.unit-empty-state p { font-size: 0.8rem; font-weight: 500; }
/* ── Unit Row ── */
.unit-list { display: flex; flex-direction: column; }
.unit-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.8125rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s;
}
.unit-row:last-child { border-bottom: none; }
.unit-row:hover { background: #f8fafc; }
.unit-row-green { border-left: 3px solid #22c55e; }
.unit-row-amber { border-left: 3px solid #f59e0b; }
.unit-row-id {
    flex: 0 0 auto;
    min-width: 120px;
}
.unit-row-number {
    display: block;
    font-size: 0.875rem;
    font-weight: 700;
    color: #1a1d23;
    letter-spacing: -0.2px;
}
.unit-row-price {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: #475569;
    margin-top: 0.125rem;
}
.unit-row-price-empty {
    display: block;
    font-size: 0.72rem;
    color: #b0bac9;
    margin-top: 0.125rem;
    font-style: italic;
}
.unit-row-meta {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 0.375rem;
}
.unit-row-method {
    font-size: 0.72rem;
    font-weight: 600;
    color: #64748b;
    background: #f1f5f9;
    padding: 0.1875rem 0.5rem;
    border-radius: 4px;
}
.unit-row-tenor {
    font-size: 0.68rem;
    color: #94a3b8;
    font-weight: 500;
}
.unit-row-actions {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    flex-shrink: 0;
}
.unit-status-select {
    padding: 0.375rem 0.5rem;
    border-radius: 7px;
    border: 1.5px solid #e8eaed;
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    font-family: 'Inter', sans-serif;
    appearance: auto;
    min-width: 120px;
}
.unit-status-select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(34,197,94,0.12);
}
.badge-terjual { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
.badge-dp      { background: #fefce8; color: #a16207; border-color: #fde68a; }
.badge-belum   { background: #f8fafc; color: #475569; border-color: #e2e8f0; }
.unit-detail-btn {
    width: 30px; height: 30px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 7px;
    background: #eff6ff;
    border: 1.5px solid #dbeafe;
    color: #2563eb;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}
.unit-detail-btn svg { width: 14px; height: 14px; }
.unit-detail-btn:hover { background: #dbeafe; border-color: #93c5fd; }
/* ──────────────────────────────────────────
   MODAL
────────────────────────────────────────── */
.modal-overlay {
    position: fixed; inset: 0; z-index: 200;
    display: flex; align-items: center; justify-content: center;
    padding: 1rem;
}
.modal-backdrop {
    position: fixed; inset: 0;
    background: rgba(15,23,42,0.55);
    backdrop-filter: blur(4px);
}
.modal-panel {
    position: relative;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.2);
    width: 100%; max-width: 520px;
    max-height: 90vh; overflow-y: auto;
    animation: modalIn 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.94) translateY(10px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.modal-panel-wide { max-width: 640px; }
.modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    gap: 1rem;
}
.modal-header-left { display: flex; align-items: center; gap: 0.875rem; }
.modal-icon {
    width: 42px; height: 42px;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid #dbeafe; flex-shrink: 0;
}
.modal-icon svg { width: 20px; height: 20px; color: #2563eb; }
.modal-title { font-size: 1rem; font-weight: 800; color: #0f172a; letter-spacing: -0.2px; }
.modal-subtitle { font-size: 0.75rem; color: #64748b; margin-top: 0.125rem; }
.modal-close {
    width: 34px; height: 34px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 8px; border: 1px solid #e2e8f0;
    background: #f8fafc; color: #64748b;
    cursor: pointer; transition: all 0.2s; flex-shrink: 0;
}
.modal-close svg { width: 15px; height: 15px; }
.modal-close:hover { background: #fee2e2; border-color: #fecaca; color: #dc2626; }
.modal-body { padding: 1.375rem 1.5rem; display: flex; flex-direction: column; gap: 1.125rem; }
/* Form */
.form-group { display: flex; flex-direction: column; gap: 0.3125rem; }
.form-label { font-size: 0.8rem; font-weight: 700; color: #374151; }
.form-select, .form-input {
    width: 100%; padding: 0.5625rem 0.875rem;
    border-radius: 9px; border: 1.5px solid #e2e8f0;
    background: #f8fafc; font-size: 0.875rem; color: #1e293b;
    transition: all 0.2s; font-family: 'Inter', sans-serif;
}
.form-select:focus, .form-input:focus {
    outline: none; border-color: #22c55e;
    background: #fff; box-shadow: 0 0 0 3px rgba(34,197,94,0.1);
}
.form-error { font-size: 0.72rem; color: #dc2626; margin-top: 0.2rem; font-weight: 600; }
.form-hint  { font-size: 0.7rem; color: #64748b; margin-top: 0.25rem; font-weight: 500; }
.required-mark { color: #ef4444; margin-left: 1px; }
.input-with-addon {
    display: flex; align-items: stretch;
    border: 1.5px solid #e2e8f0;
    border-radius: 9px; overflow: hidden;
    background: #f8fafc;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.input-with-addon:focus-within {
    border-color: #22c55e;
    box-shadow: 0 0 0 3px rgba(34,197,94,0.1);
    background: #fff;
}
.input-addon, .addon-left-label {
    display: flex; align-items: center;
    padding: 0 0.875rem;
    background: #f1f5f9;
    font-size: 0.8rem; font-weight: 700; color: #475569;
    white-space: nowrap; flex-shrink: 0;
    pointer-events: none; user-select: none;
}
.addon-left-label { border-right: 1px solid #e2e8f0; }
.input-addon { border-left: 1px solid #e2e8f0; }
.input-with-addon .form-input {
    flex: 1; border: none; border-radius: 0;
    background: transparent; padding: 0.5625rem 0.875rem;
    box-shadow: none; min-width: 0; width: 100%;
}
.input-with-addon .form-input:focus { outline: none; box-shadow: none; background: transparent; }
/* Form sections */
.form-section { border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
.form-section-title {
    display: flex; align-items: center; gap: 0.5rem;
    padding: 0.625rem 0.875rem;
    background: #f8fafc; border-bottom: 1px solid #f1f5f9;
    font-size: 0.78rem; font-weight: 700; color: #374151;
}
.form-section-title svg { width: 14px; height: 14px; color: #64748b; flex-shrink: 0; }
.form-section > .form-grid-2,
.form-section > .form-group { padding: 0.75rem 0.875rem; }
.form-section > .form-grid-2 { padding-bottom: 0; }
.form-section > .calc-info-row { border-top: 1px solid #f1f5f9; margin: 0; }
.section-note { font-size: 0.68rem; font-weight: 500; color: #94a3b8; margin-left: auto; }
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; padding-bottom: 0.75rem; }
@media (max-width: 480px) { .form-grid-2 { grid-template-columns: 1fr; } }
.calc-info-row {
    display: flex; justify-content: space-between; align-items: center;
    gap: 1rem; padding: 0.5625rem 0.875rem;
    background: #eff6ff; border-top: 1px solid #dbeafe;
    border-radius: 0 0 10px 10px;
}
.calc-info-label { font-size: 0.75rem; font-weight: 600; color: #3b82f6; }
.calc-info-value { font-size: 0.875rem; font-weight: 800; color: #1d4ed8; }
/* Radio cards */
.radio-group { display: flex; gap: 0.625rem; flex-wrap: wrap; }
.radio-card {
    flex: 1; min-width: 160px;
    display: flex; align-items: flex-start; gap: 0.625rem;
    padding: 0.75rem 0.875rem;
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    cursor: pointer; transition: all 0.2s; background: #f8fafc;
}
.radio-card:hover { border-color: #22c55e; background: #f0fdf4; }
.radio-card-active { border-color: #22c55e !important; background: #f0fdf4 !important; box-shadow: 0 0 0 3px rgba(34,197,94,0.1); }
.radio-card-icon { font-size: 1.125rem; flex-shrink: 0; }
.radio-card-title { font-size: 0.78rem; font-weight: 700; color: #1e293b; margin-bottom: 0.2rem; }
.radio-card-desc  { font-size: 0.68rem; color: #64748b; line-height: 1.5; }
/* Simulation */
.simulation-result {
    background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #2563eb 100%);
    border-radius: 14px; padding: 1.125rem; color: #fff;
}
.simulation-header {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.8125rem; font-weight: 700; margin-bottom: 0.875rem;
    color: rgba(255,255,255,0.9);
}
.simulation-header svg { width: 15px; height: 15px; opacity: 0.8; }
.simulation-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 0.625rem; margin-bottom: 0.75rem;
}
@media (max-width: 420px) { .simulation-grid { grid-template-columns: 1fr; } }
.sim-item {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 9px; padding: 0.625rem;
    backdrop-filter: blur(4px);
}
.sim-main { grid-column: 1 / -1; background: rgba(255,255,255,0.18); border-color: rgba(255,255,255,0.3); }
.sim-label { font-size: 0.68rem; font-weight: 600; color: rgba(255,255,255,0.7); margin-bottom: 0.2rem; text-transform: uppercase; letter-spacing: 0.5px; }
.sim-value { font-size: 0.875rem; font-weight: 800; color: #fff; }
.sim-value-main { font-size: 1.25rem; }
.sim-value-interest { color: #fca5a5; }
.sim-note { font-size: 0.65rem; color: rgba(255,255,255,0.6); line-height: 1.5; }
/* File upload */
.file-upload-area {
    display: flex; flex-direction: column; align-items: center;
    gap: 0.375rem; padding: 1.25rem 1rem;
    border: 2px dashed #cbd5e1; border-radius: 10px;
    background: #f8fafc; cursor: pointer;
    transition: all 0.2s; text-align: center;
}
.file-upload-area:hover { border-color: #22c55e; background: #f0fdf4; }
.file-upload-icon { width: 36px; height: 36px; color: #94a3b8; margin-bottom: 0.125rem; }
.file-upload-hint { font-size: 0.8rem; color: #64748b; }
.file-upload-link { color: #2563eb; font-weight: 700; }
.file-upload-meta { font-size: 0.68rem; color: #94a3b8; }
.file-upload-ready { font-size: 0.8rem; font-weight: 700; color: #16a34a; }
.upload-loading { display: flex; align-items: center; gap: 0.5rem; font-size: 0.78rem; color: #3b82f6; font-weight: 600; margin-top: 0.375rem; }
.existing-file { display: flex; align-items: center; justify-content: space-between; margin-top: 0.625rem; padding: 0.625rem 0.875rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; }
.existing-file-label { font-size: 0.72rem; font-weight: 600; color: #94a3b8; }
.existing-file-link { display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.78rem; font-weight: 700; color: #2563eb; text-decoration: none; }
.existing-file-link svg { width: 13px; height: 13px; }
.existing-file-link:hover { color: #1d4ed8; text-decoration: underline; }
/* Modal footer */
.modal-footer {
    display: flex; justify-content: flex-end; gap: 0.625rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid #f1f5f9;
    background: #f8fafc;
    border-radius: 0 0 20px 20px;
}
.btn-primary {
    display: inline-flex; align-items: center; gap: 0.375rem;
    padding: 0.5625rem 1.125rem;
    background: linear-gradient(135deg, #16a34a, #15803d);
    color: #fff; border: none; border-radius: 9px;
    font-size: 0.8125rem; font-weight: 700; cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(22,163,74,0.25);
    font-family: 'Inter', sans-serif;
}
.btn-primary svg { width: 14px; height: 14px; }
.btn-primary:hover { background: linear-gradient(135deg, #15803d, #166534); box-shadow: 0 6px 16px rgba(22,163,74,0.35); transform: translateY(-1px); }
.btn-secondary {
    display: inline-flex; align-items: center; gap: 0.375rem;
    padding: 0.5625rem 1rem;
    background: #fff; color: #475569;
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    font-size: 0.8125rem; font-weight: 600; cursor: pointer;
    transition: all 0.2s; font-family: 'Inter', sans-serif;
}
.btn-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #1e293b; }
/* Scrollbar styling */
.blocks-list::-webkit-scrollbar,
.unit-list-wrap::-webkit-scrollbar { width: 4px; }
.blocks-list::-webkit-scrollbar-track,
.unit-list-wrap::-webkit-scrollbar-track { background: transparent; }
.blocks-list::-webkit-scrollbar-thumb,
.unit-list-wrap::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
.blocks-list::-webkit-scrollbar-thumb:hover,
.unit-list-wrap::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
/* sr-only */
.sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border-width: 0; }
</style>
@endpush
