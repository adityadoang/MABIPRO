<div class="page-wrapper">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-header-inner">
            <div>
                <h1 class="page-title">Laporan Pembayaran</h1>
                <p class="page-subtitle">Rekapitulasi total uang masuk dan detail KPR seluruh unit.</p>
            </div>
            <a href="{{ route('marketing.dashboard') }}" class="back-btn" id="back-to-dashboard">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- ── Summary Cards ── --}}
    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-card-icon summary-icon-blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="summary-card-body">
                <p class="summary-label">Total Pendapatan</p>
                <p class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="summary-note">DP &amp; Cash terkumpul</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-card-icon summary-icon-green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="summary-card-body">
                <p class="summary-label">Pembayaran Cash</p>
                <p class="summary-value">{{ $totalCashUnits }} <span class="summary-unit">Unit</span></p>
                <p class="summary-note">Dibayar tunai penuh</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-card-icon summary-icon-purple">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="summary-card-body">
                <p class="summary-label">Pengajuan KPR</p>
                <p class="summary-value">{{ $totalKprUnits }} <span class="summary-unit">Unit</span></p>
                <p class="summary-note">Proses cicilan bank</p>
            </div>
        </div>

        @if($totalMonthlyInstallment > 0)
        <div class="summary-card">
            <div class="summary-card-icon summary-icon-orange">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="summary-card-body">
                <p class="summary-label">Total Cicilan/Bulan (KPR)</p>
                <p class="summary-value">Rp {{ number_format($totalMonthlyInstallment, 0, ',', '.') }}</p>
                <p class="summary-note">Agregat cicilan semua unit KPR</p>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Data Table ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <h2 class="table-card-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Rincian Pembayaran per Unit
            </h2>
        </div>

        {{-- Desktop Table --}}
        <div class="table-scroll">
            <table class="data-table" id="payment-table">
                <thead>
                    <tr>
                        <th>Unit / Blok</th>
                        <th>Status</th>
                        <th>Metode</th>
                        <th>Harga Unit</th>
                        <th>DP / Terbayar</th>
                        <th>Pokok Kredit</th>
                        <th>Tenor &amp; Bunga</th>
                        <th>Cicilan/Bulan</th>
                        <th class="text-center">Bukti</th>
                        <th class="text-center">Cicilan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                        @php
                            $isKpr = $unit->payment_method === 'KPR';
                            $isActiveTracker = $activeTrackerUnitId === $unit->id;
                            $statusColor = match($unit->status_penjualan) {
                                'Terjual'  => 'status-green',
                                'Sudah DP' => 'status-yellow',
                                default    => 'status-gray',
                            };

                            // Quick stats untuk badge di kolom Cicilan
                            $unitPaidCount = 0;
                            $unitTotalTenor = 0;
                            if ($isKpr && $unit->akad_date && $unit->kpr_duration_months) {
                                $unitTotalTenor = $unit->kpr_duration_months;
                                $unitPaidCount = $unit->installmentPayments->where('is_paid', true)->count();
                            }
                        @endphp
                        <tr class="table-row {{ $isActiveTracker ? 'row-active' : '' }}">
                            <td>
                                <div class="unit-cell-number">{{ $unit->unit_number }}</div>
                                <div class="unit-cell-block">{{ $unit->block->nama_blok }}</div>
                            </td>
                            <td>
                                <span class="status-badge {{ $statusColor }}">{{ $unit->status_penjualan }}</span>
                            </td>
                            <td>
                                @if($isKpr)
                                    <span class="method-badge method-kpr">KPR</span>
                                    @if($unit->bank_name)
                                        <div class="bank-name">{{ $unit->bank_name }}</div>
                                    @endif
                                    @if($unit->kpr_type)
                                        <div class="kpr-type-badge">{{ $unit->kpr_type === 'subsidi' ? 'Subsidi' : 'Non-Subsidi' }}</div>
                                    @endif
                                @else
                                    <span class="method-badge method-cash">Cash</span>
                                @endif
                            </td>
                            <td>
                                @if($unit->harga_unit)
                                    <span class="amount-cell">Rp {{ number_format($unit->harga_unit, 0, ',', '.') }}</span>
                                @else
                                    <span class="no-data">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="amount-cell">Rp {{ number_format($unit->amount_paid ?? 0, 0, ',', '.') }}</span>
                                @if($isKpr && $unit->dp_percentage)
                                    <div class="dp-pct">{{ $unit->dp_percentage }}% DP</div>
                                @endif
                            </td>
                            <td>
                                @if($isKpr && $unit->pokok_kredit)
                                    <span class="pokok-cell">Rp {{ number_format($unit->pokok_kredit, 0, ',', '.') }}</span>
                                @else
                                    <span class="no-data">—</span>
                                @endif
                            </td>
                            <td>
                                @if($isKpr)
                                    @if($unit->kpr_duration_months)
                                        <div class="tenor-cell">{{ $unit->kpr_duration_months }} Bln
                                            <span class="tenor-year">({{ round($unit->kpr_duration_months / 12, 0) }} Thn)</span>
                                        </div>
                                    @endif
                                    @if($unit->interest_rate)
                                        <div class="rate-cell">{{ $unit->interest_rate }}%
                                            <span class="rate-type">{{ $unit->interest_type === 'anuitas' ? 'Anuitas' : 'Flat' }}</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="no-data">—</span>
                                @endif
                            </td>
                            <td>
                                @if($isKpr && $unit->monthly_installment)
                                    <span class="cicilan-cell">Rp {{ number_format($unit->monthly_installment, 0, ',', '.') }}</span>
                                    <div class="cicilan-label">/bulan</div>
                                @else
                                    <span class="no-data">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($unit->payment_proof_path)
                                    <a href="{{ Storage::url($unit->payment_proof_path) }}"
                                       target="_blank"
                                       class="proof-btn"
                                       title="Lihat Bukti Pembayaran">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat
                                    </a>
                                @else
                                    <span class="no-file">Belum ada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($isKpr && $unit->akad_date && $unit->kpr_duration_months)
                                    <button wire:click="openTracker({{ $unit->id }})"
                                            id="tracker-btn-{{ $unit->id }}"
                                            class="tracker-btn {{ $isActiveTracker ? 'tracker-btn-active' : '' }}">
                                        @if($unitTotalTenor > 0)
                                            <span class="tracker-pill {{ $unitPaidCount === $unitTotalTenor ? 'pill-done' : ($unitPaidCount > 0 ? 'pill-partial' : 'pill-none') }}">
                                                {{ $unitPaidCount }}/{{ $unitTotalTenor }}
                                            </span>
                                        @endif
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="tracker-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                        Cicilan
                                    </button>
                                @elseif($isKpr)
                                    <span class="no-file" title="Set tanggal akad &amp; tenor dulu">Belum siap</span>
                                @else
                                    <span class="no-data">—</span>
                                @endif
                            </td>
                        </tr>

                        {{-- ── Tracker Panel (accordion, muncul di bawah baris unit KPR) ── --}}
                        @if($isActiveTracker && $activeUnit && $trackerSummary)
                        <tr class="tracker-row">
                            <td colspan="10" class="tracker-td">
                                <div class="tracker-panel">

                                    {{-- Header summary --}}
                                    <div class="tracker-header">
                                        <div class="tracker-header-left">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="tracker-title">Riwayat Cicilan — Unit {{ $activeUnit->unit_number }}</span>
                                        </div>
                                        <button wire:click="openTracker({{ $unit->id }})" class="tracker-close">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Stats bar --}}
                                    <div class="tracker-stats">
                                        <div class="tstat tstat-green">
                                            <span class="tstat-val">{{ $trackerSummary['paid_count'] }}</span>
                                            <span class="tstat-lbl">Bulan Lunas</span>
                                        </div>
                                        <div class="tstat tstat-red">
                                            <span class="tstat-val">{{ $trackerSummary['remaining'] }}</span>
                                            <span class="tstat-lbl">Sisa Bulan</span>
                                        </div>
                                        <div class="tstat tstat-blue">
                                            <span class="tstat-val">Rp {{ number_format($trackerSummary['total_paid'], 0, ',', '.') }}</span>
                                            <span class="tstat-lbl">Total Masuk</span>
                                        </div>
                                        <div class="tstat tstat-orange">
                                            <span class="tstat-val">Rp {{ number_format($trackerSummary['sisa_nominal'], 0, ',', '.') }}</span>
                                            <span class="tstat-lbl">Sisa Tagihan</span>
                                        </div>
                                    </div>

                                    {{-- Progress bar --}}
                                    <div class="tracker-progress-wrap">
                                        <div class="tracker-progress-bar">
                                            <div class="tracker-progress-fill" style="width: {{ $trackerSummary['pct'] }}%"></div>
                                        </div>
                                        <span class="tracker-progress-pct">{{ $trackerSummary['pct'] }}% lunas</span>
                                    </div>

                                    {{-- Monthly grid --}}
                                    <div class="month-grid">
                                        @foreach($installmentMonths as $idx => $monthStr)
                                            @php
                                                $rec     = $paidMap[$monthStr] ?? null;
                                                $isPaid  = $rec ? (bool) $rec['is_paid'] : false;
                                                $amt     = $rec['amount_paid'] ?? null;
                                                $note    = $rec['note'] ?? null;
                                                $isNow   = \Carbon\Carbon::parse($monthStr)->isCurrentMonth();
                                                $isPast  = \Carbon\Carbon::parse($monthStr)->lt(now()->startOfMonth());
                                                $isFuture = \Carbon\Carbon::parse($monthStr)->gt(now()->startOfMonth());
                                            @endphp
                                            <div class="month-card {{ $isPaid ? 'mc-paid' : ($isPast ? 'mc-overdue' : ($isNow ? 'mc-current' : 'mc-future')) }}">
                                                <div class="mc-top">
                                                    <span class="mc-num">{{ $idx + 1 }}</span>
                                                    <span class="mc-date">{{ \Carbon\Carbon::parse($monthStr)->translatedFormat('M Y') }}</span>
                                                    @if($isNow)<span class="mc-now-badge">Bulan Ini</span>@endif
                                                </div>
                                                <div class="mc-status">
                                                    @if($isPaid)
                                                        <span class="mc-status-icon mc-icon-paid">✓</span>
                                                        <span class="mc-status-label">Lunas</span>
                                                    @elseif($isPast)
                                                        <span class="mc-status-icon mc-icon-overdue">!</span>
                                                        <span class="mc-status-label mc-label-red">Terlambat</span>
                                                    @else
                                                        <span class="mc-status-icon mc-icon-upcoming">○</span>
                                                        <span class="mc-status-label">Menunggu</span>
                                                    @endif
                                                </div>
                                                @if($amt)
                                                    <div class="mc-amount">Rp {{ number_format($amt, 0, ',', '.') }}</div>
                                                @endif
                                                @if($note)
                                                    <div class="mc-note" title="{{ $note }}">{{ Str::limit($note, 30) }}</div>
                                                @endif
                                                <div class="mc-actions">
                                                    <button wire:click="togglePaid({{ $unit->id }}, '{{ $monthStr }}')"
                                                            id="toggle-{{ $unit->id }}-{{ $idx }}"
                                                            class="mc-btn mc-btn-toggle {{ $isPaid ? 'mc-btn-unmark' : 'mc-btn-mark' }}"
                                                            title="{{ $isPaid ? 'Tandai Belum Bayar' : 'Tandai Lunas' }}">
                                                        {{ $isPaid ? '✗ Batal' : '✓ Lunas' }}
                                                    </button>
                                                    <button wire:click="openEditModal({{ $unit->id }}, '{{ $monthStr }}')"
                                                            id="edit-{{ $unit->id }}-{{ $idx }}"
                                                            class="mc-btn mc-btn-edit"
                                                            title="Edit Detail">
                                                        ✎
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </td>
                        </tr>
                        @endif

                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-table-state">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p>Belum ada data pembayaran yang tersimpan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="mobile-cards">
            @forelse($units as $unit)
                @php
                    $isKpr = $unit->payment_method === 'KPR';
                    $statusColor = match($unit->status_penjualan) {
                        'Terjual'  => 'status-green',
                        'Sudah DP' => 'status-yellow',
                        default    => 'status-gray',
                    };
                    $borderClass = match($unit->status_penjualan) {
                        'Terjual'  => 'mobile-card-green',
                        'Sudah DP' => 'mobile-card-yellow',
                        default    => 'mobile-card-gray',
                    };
                    $unitPaidCount = $isKpr ? $unit->installmentPayments->where('is_paid', true)->count() : 0;
                    $unitTotalTenor = $isKpr ? ($unit->kpr_duration_months ?? 0) : 0;
                    $isActiveMobile = $activeTrackerUnitId === $unit->id;
                @endphp
                <div class="mobile-card {{ $borderClass }}">
                    <div class="mobile-card-top">
                        <div>
                            <p class="mobile-card-number">{{ $unit->unit_number }}</p>
                            <p class="mobile-card-block">{{ $unit->block->nama_blok }}</p>
                        </div>
                        <div class="mobile-card-badges">
                            <span class="status-badge {{ $statusColor }}">{{ $unit->status_penjualan }}</span>
                            @if($isKpr)
                                <span class="method-badge method-kpr">KPR</span>
                            @else
                                <span class="method-badge method-cash">Cash</span>
                            @endif
                        </div>
                    </div>
                    <div class="mobile-card-rows">
                        @if($unit->harga_unit)
                        <div class="mobile-card-row">
                            <span class="mobile-card-label">Harga Unit</span>
                            <span class="mobile-card-val">Rp {{ number_format($unit->harga_unit, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="mobile-card-row">
                            <span class="mobile-card-label">{{ $isKpr ? 'DP Terbayar' : 'Terbayar' }}</span>
                            <span class="mobile-amount">Rp {{ number_format($unit->amount_paid ?? 0, 0, ',', '.') }}</span>
                        </div>
                        @if($isKpr)
                            @if($unit->pokok_kredit)
                            <div class="mobile-card-row">
                                <span class="mobile-card-label">Pokok Kredit</span>
                                <span class="mobile-card-val">Rp {{ number_format($unit->pokok_kredit, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            @if($unit->bank_name)
                            <div class="mobile-card-row">
                                <span class="mobile-card-label">Bank</span>
                                <span class="mobile-card-val">{{ $unit->bank_name }}</span>
                            </div>
                            @endif
                            @if($unit->kpr_duration_months && $unit->interest_rate)
                            <div class="mobile-card-row">
                                <span class="mobile-card-label">Tenor &amp; Bunga</span>
                                <span class="mobile-card-val">{{ $unit->kpr_duration_months }} Bln · {{ $unit->interest_rate }}%</span>
                            </div>
                            @endif
                            @if($unit->monthly_installment)
                            <div class="mobile-card-row mobile-card-row-highlight">
                                <span class="mobile-card-label">Cicilan/Bulan</span>
                                <span class="mobile-amount">Rp {{ number_format($unit->monthly_installment, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            @if($unit->akad_date && $unit->kpr_duration_months)
                            <div class="mobile-card-row">
                                <span class="mobile-card-label">Progress Cicilan</span>
                                <button wire:click="openTracker({{ $unit->id }})"
                                        class="tracker-btn-sm {{ $isActiveMobile ? 'tracker-btn-sm-active' : '' }}">
                                    <span class="tracker-pill {{ $unitPaidCount === $unitTotalTenor && $unitTotalTenor > 0 ? 'pill-done' : ($unitPaidCount > 0 ? 'pill-partial' : 'pill-none') }}">
                                        {{ $unitPaidCount }}/{{ $unitTotalTenor }}
                                    </span>
                                    Lihat Cicilan
                                </button>
                            </div>
                            @endif
                        @endif
                        @if($unit->payment_proof_path)
                            <div class="mobile-card-row">
                                <span class="mobile-card-label">Bukti</span>
                                <a href="{{ Storage::url($unit->payment_proof_path) }}" target="_blank" class="proof-btn-sm">
                                    Lihat Dokumen →
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Mobile tracker panel --}}
                    @if($isActiveMobile && $activeUnit && $trackerSummary)
                    <div class="mobile-tracker-panel">
                        <div class="tracker-stats tracker-stats-mobile">
                            <div class="tstat tstat-green">
                                <span class="tstat-val">{{ $trackerSummary['paid_count'] }}</span>
                                <span class="tstat-lbl">Lunas</span>
                            </div>
                            <div class="tstat tstat-red">
                                <span class="tstat-val">{{ $trackerSummary['remaining'] }}</span>
                                <span class="tstat-lbl">Sisa</span>
                            </div>
                            <div class="tstat tstat-orange">
                                <span class="tstat-val" style="font-size:0.8rem">Rp {{ number_format($trackerSummary['sisa_nominal'], 0, ',', '.') }}</span>
                                <span class="tstat-lbl">Sisa Tagihan</span>
                            </div>
                        </div>
                        <div class="tracker-progress-wrap" style="padding: 0 1rem 0.75rem;">
                            <div class="tracker-progress-bar">
                                <div class="tracker-progress-fill" style="width: {{ $trackerSummary['pct'] }}%"></div>
                            </div>
                            <span class="tracker-progress-pct">{{ $trackerSummary['pct'] }}% lunas</span>
                        </div>
                        <div class="month-grid month-grid-mobile">
                            @foreach($installmentMonths as $idx => $monthStr)
                                @php
                                    $rec     = $paidMap[$monthStr] ?? null;
                                    $isPaid  = $rec ? (bool) $rec['is_paid'] : false;
                                    $amt     = $rec['amount_paid'] ?? null;
                                    $isPast  = \Carbon\Carbon::parse($monthStr)->lt(now()->startOfMonth());
                                    $isNow   = \Carbon\Carbon::parse($monthStr)->isCurrentMonth();
                                @endphp
                                <div class="month-card {{ $isPaid ? 'mc-paid' : ($isPast ? 'mc-overdue' : ($isNow ? 'mc-current' : 'mc-future')) }}">
                                    <div class="mc-top">
                                        <span class="mc-num">{{ $idx + 1 }}</span>
                                        <span class="mc-date">{{ \Carbon\Carbon::parse($monthStr)->translatedFormat('M Y') }}</span>
                                    </div>
                                    <div class="mc-status">
                                        @if($isPaid)
                                            <span class="mc-status-icon mc-icon-paid">✓</span>
                                        @elseif($isPast)
                                            <span class="mc-status-icon mc-icon-overdue">!</span>
                                        @else
                                            <span class="mc-status-icon mc-icon-upcoming">○</span>
                                        @endif
                                    </div>
                                    @if($amt)
                                        <div class="mc-amount" style="font-size:0.65rem">Rp {{ number_format($amt, 0, ',', '.') }}</div>
                                    @endif
                                    <div class="mc-actions">
                                        <button wire:click="togglePaid({{ $unit->id }}, '{{ $monthStr }}')"
                                                class="mc-btn mc-btn-toggle {{ $isPaid ? 'mc-btn-unmark' : 'mc-btn-mark' }}">
                                            {{ $isPaid ? '✗' : '✓' }}
                                        </button>
                                        <button wire:click="openEditModal({{ $unit->id }}, '{{ $monthStr }}')"
                                                class="mc-btn mc-btn-edit">✎</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            @empty
                <div class="empty-table-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p>Belum ada data pembayaran.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         Modal Edit Cicilan
         ═══════════════════════════════════════════════════════════ --}}
    @if($isEditModalOpen)
    <div class="modal-overlay" wire:click.self="closeEditModal" id="edit-installment-modal">
        <div class="modal-box">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title">Edit Cicilan</h3>
                    <p class="modal-subtitle">{{ $editMonthLabel }} — Unit {{ $activeUnit?->unit_number }}</p>
                </div>
                <button wire:click="closeEditModal" class="modal-close-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="modal-body">
                {{-- Status paid --}}
                <div class="form-group">
                    <label class="form-label">Status Pembayaran</label>
                    <div class="toggle-row">
                        <button type="button"
                                wire:click="$set('editIsPaid', false)"
                                id="status-belum"
                                class="toggle-opt {{ !$editIsPaid ? 'toggle-opt-active-red' : '' }}">
                            ✗ Belum Lunas
                        </button>
                        <button type="button"
                                wire:click="$set('editIsPaid', true)"
                                id="status-lunas"
                                class="toggle-opt {{ $editIsPaid ? 'toggle-opt-active-green' : '' }}">
                            ✓ Lunas
                        </button>
                    </div>
                </div>

                {{-- Nominal --}}
                @if($editIsPaid)
                <div class="form-group">
                    <label for="edit-amount" class="form-label">
                        Nominal Diterima
                        @if($activeUnit?->monthly_installment)
                            <span class="form-hint">(Cicilan: Rp {{ number_format($activeUnit->monthly_installment, 0, ',', '.') }})</span>
                        @endif
                    </label>
                    <div class="input-prefix-wrap">
                        <span class="input-prefix">Rp</span>
                        <input type="number"
                               id="edit-amount"
                               wire:model.live="editAmount"
                               class="form-input input-with-prefix"
                               placeholder="Masukkan nominal"
                               min="0"
                               step="1000">
                    </div>
                    @error('editAmount') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                @endif

                {{-- Catatan --}}
                <div class="form-group">
                    <label for="edit-note" class="form-label">Catatan <span class="form-optional">(opsional)</span></label>
                    <textarea id="edit-note"
                              wire:model="editNote"
                              class="form-input form-textarea"
                              rows="3"
                              placeholder="Misal: Bayar via transfer BCA, ada denda keterlambatan..."></textarea>
                    @error('editNote') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="modal-footer">
                <button wire:click="closeEditModal" class="btn-cancel">Batal</button>
                <button wire:click="saveInstallment" id="save-installment-btn" class="btn-save">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
    @endif

</div>

<style>
/* ── Layout ── */
.page-wrapper { max-width: 1400px; margin: 0 auto; padding: 2rem 1.25rem 3rem; }

/* ── Page Header ── */
.page-header { margin-bottom: 2rem; }
.page-header-inner { display: flex; flex-direction: column; gap: 1rem; }
@media (min-width: 640px) { .page-header-inner { flex-direction: row; align-items: center; justify-content: space-between; } }
.page-title { font-size: 1.875rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; line-height: 1.2; }
.page-subtitle { margin-top: 0.375rem; font-size: 0.875rem; color: #64748b; }
.back-btn {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.625rem 1rem; background: #fff; border: 1.5px solid #e2e8f0;
    border-radius: 10px; font-size: 0.875rem; font-weight: 600; color: #475569;
    text-decoration: none; transition: all 0.2s ease; white-space: nowrap; flex-shrink: 0;
}
.back-btn svg { width: 16px; height: 16px; }
.back-btn:hover { background: #f1f5f9; border-color: #cbd5e1; color: #1e293b; }

/* ── Summary Grid ── */
.summary-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.75rem; }
@media (min-width: 480px) { .summary-grid { grid-template-columns: 1fr 1fr; } }
@media (min-width: 900px) { .summary-grid { grid-template-columns: repeat(4, 1fr); } }

.summary-card {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.25rem;
    display: flex; align-items: center; gap: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.25s ease;
}
.summary-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.09); transform: translateY(-2px); }
.summary-card-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.summary-card-icon svg { width: 24px; height: 24px; }
.summary-icon-blue   { background: #eff6ff; }
.summary-icon-blue svg   { color: #2563eb; }
.summary-icon-green  { background: #f0fdf4; }
.summary-icon-green svg  { color: #16a34a; }
.summary-icon-purple { background: #faf5ff; }
.summary-icon-purple svg { color: #9333ea; }
.summary-icon-orange { background: #fff7ed; }
.summary-icon-orange svg { color: #ea580c; }
.summary-card-body { min-width: 0; flex: 1; }
.summary-label { font-size: 0.72rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.5px; }
.summary-value { font-size: 1.375rem; font-weight: 900; color: #0f172a; letter-spacing: -0.5px; line-height: 1.1; word-break: break-all; }
.summary-unit  { font-size: 0.875rem; font-weight: 600; color: #64748b; }
.summary-note  { font-size: 0.7rem; color: #94a3b8; margin-top: 0.25rem; font-weight: 500; }

/* ── Table Card ── */
.table-card { background: #fff; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 2px 12px rgba(0,0,0,0.05); overflow: hidden; }
.table-card-header { padding: 1.125rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #f8fafc; }
.table-card-title { display: flex; align-items: center; gap: 0.625rem; font-size: 1rem; font-weight: 800; color: #1e293b; }
.table-card-title svg { width: 18px; height: 18px; color: #64748b; }

/* Desktop table */
.table-scroll { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table thead { background: #f8fafc; }
.data-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.68rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid #f1f5f9; white-space: nowrap; }
.text-center { text-align: center !important; }
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid #f8fafc; vertical-align: top; }
.table-row:last-child td { border-bottom: none; }
.table-row:hover td { background: #f8fafc; }
.table-row.row-active td { background: #f0f9ff; border-bottom-color: #bae6fd; }

/* Cell types */
.unit-cell-number { font-size: 0.9375rem; font-weight: 800; color: #0f172a; }
.unit-cell-block  { font-size: 0.75rem; color: #94a3b8; font-weight: 500; margin-top: 2px; }
.amount-cell  { font-size: 0.875rem; font-weight: 800; color: #1d4ed8; }
.pokok-cell   { font-size: 0.8125rem; font-weight: 700; color: #374151; }
.cicilan-cell { font-size: 0.9rem; font-weight: 800; color: #16a34a; }
.cicilan-label { font-size: 0.68rem; color: #94a3b8; }
.dp-pct { font-size: 0.7rem; color: #64748b; margin-top: 2px; }
.tenor-cell { font-size: 0.8125rem; font-weight: 700; color: #374151; }
.tenor-year { font-size: 0.7rem; color: #94a3b8; }
.rate-cell  { font-size: 0.8125rem; font-weight: 700; color: #374151; margin-top: 2px; }
.rate-type  { font-size: 0.68rem; background: #f1f5f9; padding: 1px 5px; border-radius: 4px; color: #64748b; margin-left: 3px; }
.bank-name  { font-size: 0.72rem; color: #64748b; margin-top: 2px; }
.kpr-type-badge { display: inline-block; font-size: 0.65rem; font-weight: 600; color: #7c3aed; background: #f5f3ff; border: 1px solid #ede9fe; padding: 1px 6px; border-radius: 4px; margin-top: 3px; }
.no-data    { color: #cbd5e1; font-size: 0.8125rem; }

/* Method badges */
.method-badge { display: inline-flex; align-items: center; padding: 0.2rem 0.625rem; border-radius: 6px; font-size: 0.72rem; font-weight: 700; }
.method-kpr  { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
.method-cash { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }

/* Status badges */
.status-badge { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.625rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; white-space: nowrap; }
.status-green  { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.status-yellow { background: #fefce8; color: #a16207; border: 1px solid #fde68a; }
.status-gray   { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

/* Proof btn */
.proof-btn { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; background: #eff6ff; border: 1px solid #dbeafe; color: #2563eb; border-radius: 8px; font-size: 0.78rem; font-weight: 700; text-decoration: none; transition: all 0.2s ease; }
.proof-btn svg { width: 14px; height: 14px; }
.proof-btn:hover { background: #dbeafe; border-color: #93c5fd; }
.no-file { font-size: 0.75rem; color: #cbd5e1; font-style: italic; }

/* Empty */
.empty-table-state { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.75rem; padding: 3rem 1rem; color: #94a3b8; font-size: 0.875rem; text-align: center; }
.empty-table-state svg { width: 40px; height: 40px; color: #cbd5e1; }

/* ── Tracker Button (di kolom tabel) ── */
.tracker-btn {
    display: inline-flex; align-items: center; gap: 0.375rem;
    padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.78rem; font-weight: 700;
    background: #f0fdf4; color: #15803d; border: 1.5px solid #bbf7d0;
    cursor: pointer; transition: all 0.2s ease; white-space: nowrap;
}
.tracker-btn:hover { background: #dcfce7; border-color: #86efac; }
.tracker-btn-active { background: #15803d; color: #fff; border-color: #15803d; }
.tracker-btn-active:hover { background: #166534; }
.tracker-icon { width: 13px; height: 13px; }

.tracker-btn-sm {
    display: inline-flex; align-items: center; gap: 0.375rem;
    padding: 0.3rem 0.625rem; border-radius: 7px; font-size: 0.75rem; font-weight: 700;
    background: #f0fdf4; color: #15803d; border: 1.5px solid #bbf7d0; cursor: pointer;
}
.tracker-btn-sm-active { background: #15803d; color: #fff; border-color: #15803d; }

/* Tracker pill (X/Y lunas) */
.tracker-pill { display: inline-flex; align-items: center; padding: 0.1rem 0.45rem; border-radius: 999px; font-size: 0.68rem; font-weight: 800; }
.pill-done    { background: #bbf7d0; color: #14532d; }
.pill-partial { background: #fde68a; color: #78350f; }
.pill-none    { background: #f1f5f9; color: #94a3b8; }

/* ── Tracker Row & Panel ── */
.tracker-row td { padding: 0 !important; border-bottom: 3px solid #0ea5e9 !important; }
.tracker-td { background: #f0f9ff !important; }

.tracker-panel {
    padding: 1.25rem 1.5rem 1.5rem;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    animation: slideDown 0.25s ease;
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.tracker-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.tracker-header-left { display: flex; align-items: center; gap: 0.5rem; }
.tracker-header-left svg { width: 18px; height: 18px; color: #0284c7; }
.tracker-title { font-size: 0.9375rem; font-weight: 800; color: #0c4a6e; }
.tracker-close {
    width: 30px; height: 30px; border-radius: 8px; background: rgba(255,255,255,0.7);
    border: 1px solid #bae6fd; display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.15s ease; color: #0369a1;
}
.tracker-close:hover { background: #fff; }
.tracker-close svg { width: 14px; height: 14px; }

/* Stats bar */
.tracker-stats {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 1rem;
}
.tracker-stats-mobile { grid-template-columns: repeat(3, 1fr); margin: 0.75rem 1rem; }
.tstat { background: #fff; border-radius: 12px; padding: 0.75rem 1rem; text-align: center; border: 1px solid; }
.tstat-green  { border-color: #bbf7d0; }
.tstat-red    { border-color: #fecaca; }
.tstat-blue   { border-color: #bae6fd; }
.tstat-orange { border-color: #fed7aa; }
.tstat-val { display: block; font-size: 1.25rem; font-weight: 900; color: #0f172a; letter-spacing: -0.5px; line-height: 1; }
.tstat-lbl { display: block; font-size: 0.65rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 0.3rem; }

/* Progress bar */
.tracker-progress-wrap { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; }
.tracker-progress-bar { flex: 1; height: 10px; background: #e0f2fe; border-radius: 999px; overflow: hidden; }
.tracker-progress-fill { height: 100%; background: linear-gradient(90deg, #0ea5e9, #22c55e); border-radius: 999px; transition: width 0.5s ease; }
.tracker-progress-pct { font-size: 0.78rem; font-weight: 700; color: #0369a1; white-space: nowrap; }

/* ── Month Grid ── */
.month-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.625rem; }
.month-grid-mobile { grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); padding: 0 1rem 1rem; }

.month-card {
    border-radius: 12px; padding: 0.75rem; border: 1.5px solid;
    display: flex; flex-direction: column; gap: 0.3rem;
    transition: all 0.2s ease;
}
.month-card:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

.mc-paid    { background: #f0fdf4; border-color: #86efac; }
.mc-overdue { background: #fff1f2; border-color: #fca5a5; }
.mc-current { background: #fefce8; border-color: #fde047; }
.mc-future  { background: #fff; border-color: #e2e8f0; }

.mc-top { display: flex; align-items: center; gap: 0.375rem; }
.mc-num { font-size: 0.65rem; font-weight: 700; color: #94a3b8; background: #f1f5f9; padding: 1px 5px; border-radius: 4px; }
.mc-date { font-size: 0.72rem; font-weight: 700; color: #374151; }
.mc-now-badge { font-size: 0.58rem; font-weight: 700; background: #fef08a; color: #78350f; padding: 1px 5px; border-radius: 4px; margin-left: auto; }

.mc-status { display: flex; align-items: center; gap: 0.3rem; }
.mc-status-icon { font-size: 0.8rem; font-weight: 900; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.mc-icon-paid    { background: #bbf7d0; color: #14532d; }
.mc-icon-overdue { background: #fecaca; color: #991b1b; }
.mc-icon-upcoming { background: #e2e8f0; color: #94a3b8; font-size: 0.7rem; }
.mc-status-label { font-size: 0.68rem; font-weight: 600; color: #64748b; }
.mc-label-red { color: #dc2626; }

.mc-amount { font-size: 0.68rem; font-weight: 700; color: #15803d; }
.mc-note { font-size: 0.62rem; color: #64748b; font-style: italic; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.mc-actions { display: flex; gap: 0.25rem; margin-top: 0.25rem; }
.mc-btn {
    flex: 1; padding: 0.25rem 0.25rem; border-radius: 6px; font-size: 0.65rem; font-weight: 700;
    border: 1px solid; cursor: pointer; transition: all 0.15s ease; text-align: center;
}
.mc-btn-mark   { background: #f0fdf4; border-color: #86efac; color: #15803d; }
.mc-btn-mark:hover { background: #dcfce7; }
.mc-btn-unmark { background: #fff1f2; border-color: #fca5a5; color: #dc2626; }
.mc-btn-unmark:hover { background: #ffe4e6; }
.mc-btn-edit   { flex: 0; padding: 0.25rem 0.5rem; background: #f8fafc; border-color: #e2e8f0; color: #64748b; }
.mc-btn-edit:hover { background: #f1f5f9; color: #374151; }

/* ── Mobile Tracker Panel ── */
.mobile-tracker-panel { border-top: 2px solid #bae6fd; background: #f0f9ff; padding-top: 0.75rem; }

/* ── Mobile Cards ── */
.mobile-cards { display: none; flex-direction: column; gap: 0.875rem; padding: 1rem; }
@media (max-width: 768px) { .table-scroll { display: none; } .mobile-cards { display: flex; } }

.mobile-card { background: #fff; border: 1.5px solid #e2e8f0; border-radius: 14px; overflow: hidden; }
.mobile-card-green  { border-top: 3px solid #22c55e; }
.mobile-card-yellow { border-top: 3px solid #eab308; }
.mobile-card-gray   { border-top: 3px solid #cbd5e1; }

.mobile-card-top { display: flex; justify-content: space-between; align-items: flex-start; padding: 1rem; border-bottom: 1px solid #f1f5f9; gap: 0.75rem; }
.mobile-card-number { font-size: 1.25rem; font-weight: 900; color: #0f172a; }
.mobile-card-block  { font-size: 0.75rem; color: #94a3b8; font-weight: 500; margin-top: 2px; }
.mobile-card-badges { display: flex; flex-direction: column; align-items: flex-end; gap: 0.375rem; }
.mobile-card-rows { display: flex; flex-direction: column; }
.mobile-card-row { display: flex; justify-content: space-between; align-items: center; padding: 0.625rem 1rem; border-bottom: 1px solid #f8fafc; gap: 1rem; }
.mobile-card-row:last-child { border-bottom: none; }
.mobile-card-row-highlight { background: #f0fdf4; }
.mobile-card-label { font-size: 0.75rem; font-weight: 600; color: #94a3b8; flex-shrink: 0; }
.mobile-card-val   { font-size: 0.8125rem; font-weight: 700; color: #1e293b; text-align: right; }
.mobile-amount     { font-size: 0.875rem; font-weight: 800; color: #1d4ed8; }
.proof-btn-sm { font-size: 0.8125rem; font-weight: 700; color: #2563eb; text-decoration: none; }
.proof-btn-sm:hover { text-decoration: underline; }

/* ── Edit Modal ── */
.modal-overlay {
    position: fixed; inset: 0; background: rgba(15,23,42,0.45); backdrop-filter: blur(4px);
    z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1rem;
    animation: fadeIn 0.15s ease;
}
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

.modal-box {
    background: #fff; border-radius: 20px; width: 100%; max-width: 480px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden;
    animation: popIn 0.2s cubic-bezier(0.34,1.56,0.64,1);
}
@keyframes popIn {
    from { opacity: 0; transform: scale(0.92) translateY(12px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; }
.modal-title { font-size: 1.125rem; font-weight: 800; color: #0f172a; }
.modal-subtitle { font-size: 0.78rem; color: #64748b; margin-top: 2px; font-weight: 500; }
.modal-close-btn { width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; flex-shrink: 0; transition: all 0.15s ease; }
.modal-close-btn:hover { background: #f1f5f9; color: #0f172a; }
.modal-close-btn svg { width: 16px; height: 16px; }

.modal-body { padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1.125rem; }
.modal-footer { padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; gap: 0.75rem; justify-content: flex-end; }

.form-group { display: flex; flex-direction: column; gap: 0.375rem; }
.form-label { font-size: 0.8125rem; font-weight: 700; color: #374151; }
.form-hint { font-size: 0.72rem; font-weight: 500; color: #94a3b8; }
.form-optional { font-size: 0.72rem; font-weight: 500; color: #94a3b8; }
.form-error { font-size: 0.72rem; color: #dc2626; margin-top: 2px; }

.form-input {
    width: 100%; padding: 0.625rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 10px;
    font-size: 0.875rem; color: #0f172a; outline: none; transition: border-color 0.2s ease;
    background: #fff; box-sizing: border-box;
}
.form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.form-textarea { resize: vertical; min-height: 80px; font-family: inherit; }

.input-prefix-wrap { position: relative; display: flex; align-items: center; }
.input-prefix { position: absolute; left: 0.875rem; font-size: 0.875rem; font-weight: 600; color: #64748b; pointer-events: none; }
.input-with-prefix { padding-left: 2.5rem !important; }

.toggle-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; }
.toggle-opt {
    padding: 0.625rem 1rem; border-radius: 10px; font-size: 0.8125rem; font-weight: 700;
    border: 1.5px solid #e2e8f0; background: #f8fafc; color: #64748b; cursor: pointer;
    transition: all 0.15s ease; text-align: center;
}
.toggle-opt:hover { border-color: #cbd5e1; background: #f1f5f9; }
.toggle-opt-active-green { background: #f0fdf4; border-color: #86efac; color: #15803d; }
.toggle-opt-active-red   { background: #fff1f2; border-color: #fca5a5; color: #dc2626; }

.btn-cancel {
    padding: 0.625rem 1.25rem; border-radius: 10px; font-size: 0.875rem; font-weight: 600;
    border: 1.5px solid #e2e8f0; background: #fff; color: #64748b; cursor: pointer;
    transition: all 0.15s ease;
}
.btn-cancel:hover { background: #f8fafc; }
.btn-save {
    display: inline-flex; align-items: center; gap: 0.375rem;
    padding: 0.625rem 1.5rem; border-radius: 10px; font-size: 0.875rem; font-weight: 700;
    border: none; background: linear-gradient(135deg, #10b981, #059669); color: #fff; cursor: pointer;
    transition: all 0.2s ease; box-shadow: 0 2px 8px rgba(16,185,129,0.3);
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16,185,129,0.4); }
.btn-save svg { width: 16px; height: 16px; }
</style>
