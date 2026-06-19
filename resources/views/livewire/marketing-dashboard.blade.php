<div class="page-wrapper">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-header-inner">
            <div class="page-header-text">
                <h1 class="page-title">Dashboard Marketing</h1>
                <p class="page-subtitle">Pantau dan kelola status penjualan unit perumahan MABIPRO secara real-time.</p>
            </div>
            <div class="legend-card">
                <div class="legend-item">
                    <span class="legend-dot dot-red"></span>
                    <span>Belum Terjual</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot dot-yellow"></span>
                    <span>Sudah DP</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot dot-green"></span>
                    <span>Terjual</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Flash Message ── --}}
    @if (session()->has('message'))
        <div class="flash-container">
            <div x-data="{ show: true }" x-show="show"
                 x-init="setTimeout(() => show = false, 4000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="flash-alert" role="alert">
                <svg class="flash-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="flash-text">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    {{-- ── Blocks ── --}}
    <div class="blocks-container">
        @forelse($blocks as $block)
            <div wire:key="block-{{ $block->id }}" class="block-card" x-data="{ expanded: false }">

                {{-- Block Header --}}
                <div class="block-header" @click="expanded = !expanded" style="cursor: pointer;">
                    <div class="block-header-left">
                        <div class="block-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <h2 class="block-title">{{ $block->nama_blok }}</h2>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span class="block-badge">{{ $block->units->count() }} Unit</span>
                        <svg :class="{'rotate-180': expanded}" class="w-5 h-5 text-white transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Units Grid --}}
                <div class="units-grid-wrapper" x-show="expanded" x-collapse style="display: none;">
                    <div class="units-grid">
                        @foreach($block->units as $unit)
                            @php
                                $statusColor = match($unit->status_penjualan) {
                                    'Terjual'  => 'status-green',
                                    'Sudah DP' => 'status-yellow',
                                    default    => 'status-red',
                                };
                                $dotColor = match($unit->status_penjualan) {
                                    'Terjual'  => 'dot-green',
                                    'Sudah DP' => 'dot-yellow',
                                    default    => 'dot-red',
                                };
                                $borderAccent = match($unit->status_penjualan) {
                                    'Terjual'  => 'unit-card-green',
                                    'Sudah DP' => 'unit-card-yellow',
                                    default    => 'unit-card-red',
                                };
                            @endphp
                            <div wire:key="unit-{{ $unit->id }}" class="unit-card {{ $borderAccent }}">

                                {{-- Unit number + status badge --}}
                                <div class="unit-top">
                                    <div>
                                        <p class="unit-label">Nomor Unit</p>
                                        <h3 class="unit-number">{{ $unit->unit_number }}</h3>
                                    </div>
                                    <span class="status-badge {{ $statusColor }}">
                                        <span class="legend-dot {{ $dotColor }}"></span>
                                        {{ $unit->status_penjualan }}
                                    </span>
                                </div>

                                {{-- Payment info --}}
                                @if($unit->payment_method)
                                    <div class="payment-info">
                                        <div class="payment-row">
                                            <span class="payment-label">Tipe</span>
                                            <span class="payment-value">
                                                {{ $unit->payment_method }}
                                                @if($unit->payment_method === 'KPR')
                                                    <span class="payment-tenor">({{ $unit->kpr_duration_months }} Bln)</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="payment-row">
                                            <span class="payment-label">Masuk</span>
                                            <span class="payment-amount">Rp {{ number_format($unit->amount_paid, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endif

                                {{-- Actions --}}
                                <div class="unit-actions">
                                    <label class="action-label">Ubah Status</label>
                                    <select
                                        wire:change="updateStatus({{ $unit->id }}, $event.target.value)"
                                        class="status-select"
                                        id="status-select-{{ $unit->id }}"
                                    >
                                        <option value="Belum Terjual" {{ $unit->status_penjualan === 'Belum Terjual' ? 'selected' : '' }}>Belum Terjual</option>
                                        <option value="Sudah DP"     {{ $unit->status_penjualan === 'Sudah DP'     ? 'selected' : '' }}>Sudah DP</option>
                                        <option value="Terjual"      {{ $unit->status_penjualan === 'Terjual'      ? 'selected' : '' }}>Terjual</option>
                                    </select>

                                    @if(in_array($unit->status_penjualan, ['Sudah DP', 'Terjual']))
                                        <button
                                            wire:click="openPaymentModal({{ $unit->id }})"
                                            id="detail-btn-{{ $unit->id }}"
                                            class="detail-btn"
                                        >
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Detail Pembayaran
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon-wrap">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="empty-title">Belum ada data perumahan</h3>
                <p class="empty-desc">Data blok dan unit belum tersedia. Silakan hubungi Admin untuk menambahkan master data.</p>
            </div>
        @endforelse
    </div>

    {{-- ── Payment Modal ── --}}
    @if($isPaymentModalOpen)
    <div class="modal-overlay" id="payment-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-backdrop" wire:click="closePaymentModal"></div>
        <div class="modal-panel modal-panel-wide">
            <form wire:submit.prevent="savePaymentDetails">

                {{-- Modal Header --}}
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

                {{-- Modal Body --}}
                <div class="modal-body">

                    {{-- ── Harga Unit ── --}}
                    <div class="form-group">
                        <label class="form-label" for="harga-unit">Harga Unit (Rp) <span class="required-mark">*</span></label>
                        <div class="input-with-addon">
                            <span class="input-addon addon-left-label">Rp</span>
                            <input id="harga-unit" type="number" wire:model.live="hargaUnit"
                                   class="form-input" placeholder="500000000" min="1">
                        </div>
                        @error('hargaUnit') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- ── Metode Pembayaran ── --}}
                    <div class="form-group">
                        <label class="form-label" for="payment-method-select">Metode Pembayaran</label>
                        <select id="payment-method-select" wire:model.live="paymentMethod" class="form-select">
                            <option value="">— Pilih Metode —</option>
                            <option value="Cash">Cash / Tunai</option>
                            <option value="KPR">KPR (Kredit Pemilikan Rumah)</option>
                        </select>
                        @error('paymentMethod') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- ══════════════════════════════ CASH ══════════════════════════════ --}}
                    @if($paymentMethod === 'Cash')
                        <div class="form-section">
                            <div class="form-section-title">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Pembayaran Tunai
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="amount-paid">Jumlah Terbayar (Rp)</label>
                                <div class="input-with-addon">
                                    <span class="input-addon addon-left-label">Rp</span>
                                    <input id="amount-paid" type="number" wire:model.live="amountPaid"
                                           class="form-input" placeholder="0" min="0">
                                </div>
                                @error('amountPaid') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            @if($sisaTagihan > 0)
                            <div class="calc-info-row" style="margin-top: 1rem; display: flex; justify-content: space-between; padding: 0.75rem 1rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <span class="calc-info-label" style="font-weight: 600; color: #64748b;">Sisa Tagihan</span>
                                <span class="calc-info-value" style="font-weight: 800; color: #ef4444;">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                            </div>
                            @elseif($amountPaid > 0 && $sisaTagihan == 0)
                            <div class="calc-info-row" style="margin-top: 1rem; display: flex; justify-content: space-between; padding: 0.75rem 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
                                <span class="calc-info-label" style="font-weight: 600; color: #15803d;">Status</span>
                                <span class="calc-info-value" style="font-weight: 800; color: #16a34a;">Lunas</span>
                            </div>
                            @endif
                        </div>
                    @endif

                    {{-- ══════════════════════════════ KPR ══════════════════════════════ --}}
                    @if($paymentMethod === 'KPR')

                        {{-- Section 1: Info Unit --}}
                        <div class="form-section">
                            <div class="form-section-title">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Informasi Unit &amp; KPR
                            </div>

                            <div class="form-grid-2">
                                {{-- Jenis KPR --}}
                                <div class="form-group">
                                    <label class="form-label" for="kpr-type">Jenis KPR <span class="required-mark">*</span></label>
                                    <select id="kpr-type" wire:model.live="kprType" class="form-select">
                                        <option value="non_subsidi">Non-Subsidi (Komersial)</option>
                                        <option value="subsidi">Subsidi (BTN — FLPP)</option>
                                    </select>
                                    @error('kprType') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                {{-- Bank --}}
                                <div class="form-group">
                                    <label class="form-label" for="bank-name">Bank / Lembaga Pembiayaan <span class="required-mark">*</span></label>
                                    <input id="bank-name" type="text" wire:model="bankName"
                                           class="form-input" placeholder="Contoh: Bank BTN, BRI, Mandiri...">
                                    @error('bankName') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                {{-- Tanggal Akad --}}
                                <div class="form-group">
                                    <label class="form-label" for="akad-date">Tanggal Akad Kredit</label>
                                    <input id="akad-date" type="date" wire:model="akadDate" class="form-input">
                                    @error('akadDate') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Down Payment --}}
                        <div class="form-section">
                            <div class="form-section-title">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Down Payment (DP)
                                <span class="section-note">Nominal ↔ Persentase dihitung otomatis</span>
                            </div>

                            <div class="form-grid-2">
                                {{-- Nominal DP --}}
                                <div class="form-group">
                                    <label class="form-label" for="dp-amount">Nominal DP (Rp) <span class="required-mark">*</span></label>
                                    <div class="input-with-addon">
                                        <span class="input-addon addon-left-label">Rp</span>
                                        <input id="dp-amount" type="number" wire:model.live="dpAmount"
                                               class="form-input" placeholder="0" min="0">
                                    </div>
                                    @error('dpAmount') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                {{-- Persentase DP --}}
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

                            {{-- Pokok Kredit (read-only) --}}
                            @if($pokokKredit > 0)
                            <div class="calc-info-row">
                                <span class="calc-info-label">Pokok Kredit (Harga − DP)</span>
                                <span class="calc-info-value">Rp {{ number_format($pokokKredit, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Section 3: Kredit & Bunga --}}
                        <div class="form-section">
                            <div class="form-section-title">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Kredit &amp; Bunga
                            </div>

                            <div class="form-grid-2">
                                {{-- Tenor --}}
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

                                {{-- Suku Bunga --}}
                                <div class="form-group">
                                    <label class="form-label" for="interest-rate">Suku Bunga p.a. (%) <span class="required-mark">*</span></label>
                                    <div class="input-with-addon">
                                        <input id="interest-rate" type="number" wire:model.live="interestRate"
                                               class="form-input" placeholder="7.5" min="0.1" max="30" step="0.01">
                                        <span class="input-addon">%/Thn</span>
                                    </div>
                                    @error('interestRate') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                {{-- Jenis Bunga --}}
                                <div class="form-group" style="grid-column: 1 / -1">
                                    <label class="form-label">Jenis Perhitungan Bunga <span class="required-mark">*</span></label>
                                    <div class="radio-group">
                                        <label class="radio-card {{ $interestType === 'anuitas' ? 'radio-card-active' : '' }}" for="type-anuitas">
                                            <input id="type-anuitas" type="radio" wire:model.live="interestType" value="anuitas" class="sr-only">
                                            <div class="radio-card-icon">📊</div>
                                            <div>
                                                <p class="radio-card-title">Anuitas (Efektif)</p>
                                                <p class="radio-card-desc">Cicilan tetap, porsi bunga menurun tiap bulan. Umum digunakan bank.</p>
                                            </div>
                                        </label>
                                        <label class="radio-card {{ $interestType === 'flat' ? 'radio-card-active' : '' }}" for="type-flat">
                                            <input id="type-flat" type="radio" wire:model.live="interestType" value="flat" class="sr-only">
                                            <div class="radio-card-icon">📈</div>
                                            <div>
                                                <p class="radio-card-title">Flat</p>
                                                <p class="radio-card-desc">Bunga dihitung dari pokok awal, cicilan lebih besar di awal.</p>
                                            </div>
                                        </label>
                                    </div>
                                    @error('interestType') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 4: Hasil Simulasi (read-only) --}}
                        @if($monthlyInstallment > 0)
                        <div class="simulation-result">
                            <div class="simulation-header">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Hasil Simulasi KPR
                            </div>
                            <div class="simulation-grid">
                                <div class="sim-item sim-main">
                                    <p class="sim-label">Cicilan / Bulan</p>
                                    <p class="sim-value sim-value-main">Rp {{ number_format($monthlyInstallment, 0, ',', '.') }}</p>
                                </div>
                                <div class="sim-item">
                                    <p class="sim-label">Pokok Kredit</p>
                                    <p class="sim-value">Rp {{ number_format($pokokKredit, 0, ',', '.') }}</p>
                                </div>
                                <div class="sim-item">
                                    <p class="sim-label">Total Pembayaran</p>
                                    <p class="sim-value">Rp {{ number_format($totalPayment, 0, ',', '.') }}</p>
                                </div>
                                <div class="sim-item">
                                    <p class="sim-label">Total Bunga</p>
                                    <p class="sim-value sim-value-interest">Rp {{ number_format($totalInterest, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <p class="sim-note">
                                * Simulasi menggunakan metode <strong>{{ $interestType === 'anuitas' ? 'Anuitas (Efektif)' : 'Flat' }}</strong>, suku bunga {{ $interestRate }}% per tahun, tenor {{ $kprDurationMonths }} bulan.
                            </p>
                        </div>
                        @endif

                    @endif

                    {{-- ── Upload Bukti (selalu tampil jika ada metode dipilih) ── --}}
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
                                <a href="{{ Storage::url($unitTemp->payment_proof_path) }}" target="_blank" class="existing-file-link">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat Dokumen
                                </a>
                            </div>
                        @endif
                    </div>
                    @endif

                </div>

                {{-- Modal Footer --}}
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

<style>
/* ── Page Layout ── */
.page-wrapper {
    max-width: 1280px;
    margin: 0 auto;
    padding: 2rem 1.25rem 3rem;
}

/* ── Page Header ── */
.page-header { margin-bottom: 2rem; }
.page-header-inner {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
@media (min-width: 768px) {
    .page-header-inner {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}
.page-title {
    font-size: 1.875rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.5px;
    line-height: 1.2;
}
.page-subtitle { margin-top: 0.375rem; font-size: 0.875rem; color: #64748b; }

.legend-card {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    box-shadow: 0 1px 6px rgba(0,0,0,0.05);
    font-size: 0.8125rem;
    font-weight: 600;
    color: #475569;
    flex-shrink: 0;
}
.legend-item { display: flex; align-items: center; gap: 0.5rem; }

/* Dots */
.legend-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.dot-red    { background: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.15); }
.dot-yellow { background: #eab308; box-shadow: 0 0 0 3px rgba(234,179,8,0.15); }
.dot-green  { background: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,0.15); }

/* ── Flash ── */
.flash-container { margin-bottom: 1.5rem; }
.flash-alert {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    background: #f0fdf4;
    border: 1px solid #86efac;
    border-left: 4px solid #22c55e;
    padding: 1rem 1.25rem;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(34,197,94,0.1);
}
.flash-icon { width: 20px; height: 20px; color: #16a34a; flex-shrink: 0; margin-top: 1px; }
.flash-text { font-size: 0.875rem; font-weight: 600; color: #15803d; }

/* ── Blocks ── */
.blocks-container { display: flex; flex-direction: column; gap: 2rem; }

.block-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
    transition: box-shadow 0.3s ease;
}
.block-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.09); }

.block-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.125rem 1.5rem;
    background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%);
    gap: 1rem;
}
.block-header-left { display: flex; align-items: center; gap: 0.75rem; min-width: 0; }
.block-icon {
    width: 38px;
    height: 38px;
    background: rgba(255,255,255,0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid rgba(255,255,255,0.2);
}
.block-icon svg { width: 18px; height: 18px; color: #fff; }
.block-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.block-badge {
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.3rem 0.75rem;
    border-radius: 999px;
    white-space: nowrap;
    letter-spacing: 0.5px;
    flex-shrink: 0;
    backdrop-filter: blur(8px);
}

.units-grid-wrapper { padding: 1.25rem; background: #f8fafc; }
.units-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
}
@media (max-width: 480px) {
    .units-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .units-grid-wrapper { padding: 0.875rem; }
}

/* ── Unit Card ── */
.unit-card {
    background: #fff;
    border-radius: 14px;
    border: 1.5px solid #e2e8f0;
    padding: 1.125rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    transition: all 0.25s ease;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    position: relative;
}
.unit-card:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.unit-card-green { border-top: 3px solid #22c55e; }
.unit-card-yellow { border-top: 3px solid #eab308; }
.unit-card-red { border-top: 3px solid #ef4444; }

.unit-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; }
.unit-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; margin-bottom: 0.25rem; }
.unit-number { font-size: 2rem; font-weight: 900; color: #0f172a; letter-spacing: -1px; line-height: 1; }
@media (max-width: 480px) {
    .unit-number { font-size: 1.5rem; }
}

/* Status badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.3rem 0.625rem;
    border-radius: 8px;
    font-size: 0.68rem;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
    letter-spacing: 0.2px;
}
.status-green  { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.status-yellow { background: #fefce8; color: #a16207; border: 1px solid #fde68a; }
.status-red    { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

/* Payment info */
.payment-info {
    background: #eff6ff;
    border: 1px solid #dbeafe;
    border-radius: 10px;
    padding: 0.625rem 0.875rem;
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}
.payment-row { display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; }
.payment-label { font-size: 0.75rem; color: #64748b; }
.payment-value { font-size: 0.8rem; font-weight: 700; color: #1e293b; }
.payment-tenor { font-size: 0.7rem; font-weight: 600; color: #64748b; }
.payment-amount { font-size: 0.825rem; font-weight: 800; color: #1d4ed8; }

/* Actions */
.unit-actions { border-top: 1px solid #f1f5f9; padding-top: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem; margin-top: auto; }
.action-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; }
.status-select {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #374151;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    appearance: auto;
}
.status-select:focus {
    outline: none;
    border-color: #3b82f6;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}

.detail-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    width: 100%;
    padding: 0.5rem;
    border-radius: 8px;
    background: #eff6ff;
    border: 1.5px solid #dbeafe;
    color: #2563eb;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}
.detail-btn svg { width: 14px; height: 14px; }
.detail-btn:hover { background: #dbeafe; border-color: #93c5fd; }

/* ── Empty State ── */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 4rem 2rem;
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
}
.empty-icon-wrap {
    width: 72px; height: 72px;
    background: #f1f5f9;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}
.empty-icon-wrap svg { width: 36px; height: 36px; color: #94a3b8; }
.empty-title { font-size: 1.125rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem; }
.empty-desc { font-size: 0.875rem; color: #64748b; max-width: 32rem; line-height: 1.6; }

/* ── Modal ── */
.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 200;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.6);
    backdrop-filter: blur(4px);
}
.modal-panel {
    position: relative;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.22);
    width: 100%;
    max-width: 520px;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalIn 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.94) translateY(10px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.375rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    gap: 1rem;
}
.modal-header-left { display: flex; align-items: center; gap: 0.875rem; }
.modal-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid #dbeafe;
}
.modal-icon svg { width: 22px; height: 22px; color: #2563eb; }
.modal-title { font-size: 1.0625rem; font-weight: 800; color: #0f172a; letter-spacing: -0.2px; }
.modal-subtitle { font-size: 0.78rem; color: #64748b; margin-top: 0.125rem; }
.modal-close {
    width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.modal-close svg { width: 16px; height: 16px; }
.modal-close:hover { background: #fee2e2; border-color: #fecaca; color: #dc2626; }

.modal-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }

/* Form */
.form-group { display: flex; flex-direction: column; gap: 0.375rem; }
.form-label { font-size: 0.8125rem; font-weight: 700; color: #374151; }
.form-select, .form-input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    font-size: 0.875rem;
    color: #1e293b;
    transition: all 0.2s ease;
}
.form-select:focus, .form-input:focus {
    outline: none;
    border-color: #3b82f6;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}
.form-error { font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem; font-weight: 600; }

/* Input addons — pure flexbox, no absolute positioning */
.input-with-addon {
    display: flex;
    align-items: stretch;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    background: #f8fafc;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.input-with-addon:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
    background: #fff;
}
/* Shared addon pill style */
.input-addon,
.addon-left-label {
    display: flex;
    align-items: center;
    padding: 0 0.875rem;
    background: #f1f5f9;
    font-size: 0.8rem;
    font-weight: 700;
    color: #475569;
    white-space: nowrap;
    flex-shrink: 0;
    pointer-events: none;
    user-select: none;
}
/* Rp prefix — left side */
.addon-left-label { border-right: 1px solid #e2e8f0; }
/* Bulan suffix — right side */
.input-addon       { border-left:  1px solid #e2e8f0; }
/* Input inside addon wrapper — remove individual border & radius */
.input-with-addon .form-input {
    flex: 1;
    border: none;
    border-radius: 0;
    background: transparent;
    padding: 0.625rem 0.875rem;
    box-shadow: none;
    min-width: 0;
    width: 100%;
}
.input-with-addon .form-input:focus {
    outline: none;
    box-shadow: none;
    background: transparent;
}

/* File upload */
.file-upload-area {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1.5rem 1rem;
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}
.file-upload-area:hover { border-color: #93c5fd; background: #eff6ff; }
.file-upload-icon { width: 40px; height: 40px; color: #94a3b8; margin-bottom: 0.25rem; }
.file-upload-hint { font-size: 0.8125rem; color: #64748b; }
.file-upload-link { color: #2563eb; font-weight: 700; }
.file-upload-meta { font-size: 0.7rem; color: #94a3b8; }
.file-upload-ready { font-size: 0.8125rem; font-weight: 700; color: #16a34a; }

.upload-loading {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: #3b82f6;
    font-weight: 600;
    margin-top: 0.5rem;
}

.existing-file {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 0.75rem;
    padding: 0.75rem 1rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
}
.existing-file-label { font-size: 0.75rem; font-weight: 600; color: #94a3b8; }
.existing-file-link {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8125rem;
    font-weight: 700;
    color: #2563eb;
    text-decoration: none;
    transition: color 0.2s;
}
.existing-file-link svg { width: 14px; height: 14px; }
.existing-file-link:hover { color: #1d4ed8; text-decoration: underline; }

/* Modal footer */
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1.125rem 1.5rem;
    border-top: 1px solid #f1f5f9;
    background: #f8fafc;
    border-radius: 0 0 20px 20px;
}
@media (max-width: 400px) {
    .modal-footer { flex-direction: column-reverse; }
    .modal-footer button { width: 100%; justify-content: center; }
}
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(37,99,235,0.3);
}
.btn-primary svg { width: 16px; height: 16px; }
.btn-primary:hover { background: linear-gradient(135deg, #1d4ed8, #1e40af); box-shadow: 0 6px 16px rgba(37,99,235,0.4); transform: translateY(-1px); }

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.125rem;
    background: #fff;
    color: #475569;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #1e293b; }

/* ── Wide modal for KPR form ── */
.modal-panel-wide { max-width: 640px; }

/* ── Form sections ── */
.form-section {
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
}
.form-section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.8125rem;
    font-weight: 700;
    color: #374151;
}
.form-section-title svg { width: 15px; height: 15px; color: #64748b; flex-shrink: 0; }
.form-section > .form-grid-2,
.form-section > .form-group,
.form-section > .calc-info-row { padding: 0.875rem 1rem; }
.form-section > .form-grid-2 { padding-bottom: 0; }
.form-section > .calc-info-row { border-top: 1px solid #f1f5f9; margin-top: 0; }

.section-note {
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-left: auto;
}
.required-mark { color: #ef4444; margin-left: 1px; }
.form-hint { font-size: 0.72rem; color: #64748b; margin-top: 0.25rem; font-weight: 500; }

/* ── 2-column grid in form ── */
.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.875rem;
    padding-bottom: 0.875rem;
}
@media (max-width: 480px) { .form-grid-2 { grid-template-columns: 1fr; } }

/* ── Calc info row (read-only result) ── */
.calc-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding: 0.625rem 1rem;
    background: #eff6ff;
    border-top: 1px solid #dbeafe;
    border-radius: 0 0 12px 12px;
}
.calc-info-label { font-size: 0.78rem; font-weight: 600; color: #3b82f6; }
.calc-info-value { font-size: 0.9rem; font-weight: 800; color: #1d4ed8; }

/* ── Radio cards for interest type ── */
.radio-group { display: flex; gap: 0.75rem; flex-wrap: wrap; }
.radio-card {
    flex: 1;
    min-width: 180px;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.875rem 1rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #f8fafc;
}
.radio-card:hover { border-color: #93c5fd; background: #eff6ff; }
.radio-card-active {
    border-color: #3b82f6 !important;
    background: #eff6ff !important;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}
.radio-card-icon { font-size: 1.25rem; flex-shrink: 0; }
.radio-card-title { font-size: 0.8125rem; font-weight: 700; color: #1e293b; margin-bottom: 0.25rem; }
.radio-card-desc  { font-size: 0.72rem; color: #64748b; line-height: 1.5; }

/* ── Simulation result panel ── */
.simulation-result {
    background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #2563eb 100%);
    border-radius: 14px;
    padding: 1.25rem;
    color: #fff;
}
.simulation-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: rgba(255,255,255,0.9);
}
.simulation-header svg { width: 16px; height: 16px; opacity: 0.8; }
.simulation-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-bottom: 0.875rem;
}
@media (max-width: 420px) { .simulation-grid { grid-template-columns: 1fr; } }
.sim-item {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 10px;
    padding: 0.75rem;
    backdrop-filter: blur(4px);
}
.sim-main {
    grid-column: 1 / -1;
    background: rgba(255,255,255,0.18);
    border-color: rgba(255,255,255,0.3);
}
.sim-label { font-size: 0.72rem; font-weight: 600; color: rgba(255,255,255,0.7); margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.5px; }
.sim-value { font-size: 0.9375rem; font-weight: 800; color: #fff; }
.sim-value-main { font-size: 1.375rem; }
.sim-value-interest { color: #fca5a5; }
.sim-note { font-size: 0.68rem; color: rgba(255,255,255,0.6); line-height: 1.5; }
</style>

