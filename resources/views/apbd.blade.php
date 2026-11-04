@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Dashboard Container Styles */
    .dashboard-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .dashboard-title-box h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dashboard-title-box p {
        margin: 6px 0 0 0;
        font-size: 13.5px;
        color: #64748b;
    }

    .year-filter-box {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        padding: 8px 16px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }

    .year-filter-box label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin: 0;
    }

    .year-filter-box select {
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #94a3b8;
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        background-color: #f8fafc;
        cursor: pointer;
    }

    /* Section Headers */
    .module-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 28px 0 16px 0;
        padding-left: 12px;
        border-left: 4px solid;
    }

    .module-heading.apbd-theme {
        border-color: #0284c7; /* Sky Blue */
    }

    .module-heading.lkpd-theme {
        border-color: #059669; /* Emerald Green */
    }

    .module-heading.ratio-theme {
        border-color: #7c3aed; /* Violet */
    }

    .module-heading h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .module-badge {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .badge-apbd {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-lkpd {
        background: #d1fae5;
        color: #047857;
    }

    .badge-ratio {
        background: #ede9fe;
        color: #6d28d9;
    }

    /* KPI Grid & Cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .kpi-card {
        border-radius: 12px;
        padding: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 140px;
        border: 1px solid rgba(0,0,0,0.06);
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.09);
    }

    .kpi-icon-watermark {
        position: absolute;
        right: 14px;
        bottom: 10px;
        font-size: 58px;
        opacity: 0.12;
        pointer-events: none;
    }

    .kpi-title {
        font-size: 12.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .kpi-main-value {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .kpi-subtitle {
        font-size: 12px;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
        opacity: 0.9;
    }

    /* Distinct Vivid Colors for APBD Cards (Ocean & Sky Theme) */
    .card-apbd-pendapatan {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
    }
    .card-apbd-belanja {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
        color: #ffffff;
    }
    .card-apbd-pembiayaan {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        color: #ffffff;
    }
    .card-apbd-surplus {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        color: #ffffff;
    }

    /* Distinct Vivid Colors for LKPD Cards (Forest & Amber & Coral Theme) */
    .card-lkpd-pendapatan {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
    }
    .card-lkpd-belanja {
        background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
        color: #ffffff;
    }
    .card-lkpd-pad {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
    }
    .card-lkpd-silpa {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        color: #ffffff;
    }

    /* Distinct Cards for Financial Ratios (Purple, Indigo, Rose, Cyan) */
    .card-ratio-1 {
        background: #ffffff;
        border-top: 4px solid #3b82f6;
        color: #1e293b;
    }
    .card-ratio-1 .kpi-title { color: #2563eb; }
    .card-ratio-1 .kpi-main-value { color: #1d4ed8; }

    .card-ratio-2 {
        background: #ffffff;
        border-top: 4px solid #f59e0b;
        color: #1e293b;
    }
    .card-ratio-2 .kpi-title { color: #d97706; }
    .card-ratio-2 .kpi-main-value { color: #b45309; }

    .card-ratio-3 {
        background: #ffffff;
        border-top: 4px solid #10b981;
        color: #1e293b;
    }
    .card-ratio-3 .kpi-title { color: #059669; }
    .card-ratio-3 .kpi-main-value { color: #047857; }

    .card-ratio-4 {
        background: #ffffff;
        border-top: 4px solid #8b5cf6;
        color: #1e293b;
    }
    .card-ratio-4 .kpi-title { color: #7c3aed; }
    .card-ratio-4 .kpi-main-value { color: #6d28d9; }

    /* Charts Layout */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(460px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .chart-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 22px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .chart-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .chart-box-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-canvas-wrapper {
        position: relative;
        height: 290px;
        width: 100%;
    }

    /* Table Toggle Section */
    .table-section {
        margin-top: 30px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .table-section-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-section-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-toggle-table {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-toggle-table:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .table-container-collapsible {
        overflow-x: auto;
        padding: 16px;
    }
</style>

<!-- Dashboard Top Header -->
<div class="dashboard-header">
    <div class="dashboard-title-box">
        <h1>
            <i class="fas fa-chart-pie" style="color: #2563eb;"></i>
            Dashboard Analitik Keuangan Daerah
        </h1>
        <p>Ringkasan Eksekutif KPI Anggaran Pendapatan dan Belanja Daerah (APBD) & Laporan Keuangan Pemerintah Daerah (LKPD) Provinsi Riau</p>
    </div>

    <form method="GET" action="" class="year-filter-box">
        <label for="tahun"><i class="fas fa-calendar-alt"></i> Tahun:</label>
        <select name="tahun" id="tahun" onchange="this.form.submit()">
            @foreach ($available_years as $year)
                <option value="{{ $year }}" {{ $selected_year == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>
        <noscript><button type="submit">Filter</button></noscript>
    </form>
</div>

<!-- =================================================== -->
<!-- MODUL 1: KPI DATA APBD (Warna Kontras: Biru & Teal) -->
<!-- =================================================== -->
<div class="module-heading apbd-theme">
    <h2>
        <i class="fas fa-file-invoice-dollar" style="color: #0284c7;"></i>
        Modul KPI Data APBD — Tahun {{ $selected_year }}
    </h2>
    <span class="module-badge badge-apbd">
        <i class="fas fa-tag"></i> Data APBD Murni / Perubahan
    </span>
</div>

@php
    $apbd_pend_a = (float)($apbd_kpi->pendapatan_anggaran ?? 0);
    $apbd_pend_p = (float)($apbd_kpi->pendapatan_perubahan ?? 0);
    $apbd_pend_diff = $apbd_pend_p - $apbd_pend_a;

    $apbd_bel_a = (float)($apbd_kpi->belanja_anggaran ?? 0);
    $apbd_bel_p = (float)($apbd_kpi->belanja_perubahan ?? 0);
    $apbd_bel_diff = $apbd_bel_p - $apbd_bel_a;

    $apbd_surplus_a = $apbd_pend_a - $apbd_bel_a;
    $apbd_surplus_p = $apbd_pend_p - $apbd_bel_p;

    $apbd_pad_a = (float)($apbd_kpi->pad_anggaran ?? 0);
    $apbd_pad_p = (float)($apbd_kpi->pad_perubahan ?? 0);

    $apbd_transfer_a = (float)($apbd_kpi->transfer_anggaran ?? 0);
    $apbd_transfer_p = (float)($apbd_kpi->transfer_perubahan ?? 0);

    $apbd_pembiayaan_neto = ((float)($apbd_kpi->pembiayaan_masuk_perubahan ?? 0)) - ((float)($apbd_kpi->pembiayaan_keluar_perubahan ?? 0));
@endphp

<div class="kpi-grid">
    <!-- Card 1: Total Pendapatan APBD -->
    <div class="kpi-card card-apbd-pendapatan">
        <i class="fas fa-hand-holding-usd kpi-icon-watermark"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-wallet"></i> Total Pendapatan (APBD-P)</div>
            <div class="kpi-main-value">Rp {{ number_format($apbd_pend_p / 1e12, 2, ',', '.') }} T</div>
        </div>
        <div>
            <div class="kpi-subtitle">
                <span>Murni: Rp {{ number_format($apbd_pend_a / 1e12, 2, ',', '.') }} T</span>
                <span style="font-weight: 700; color: {{ $apbd_pend_diff >= 0 ? '#bbf7d0' : '#fecaca' }};">
                    ({{ $apbd_pend_diff >= 0 ? '+' : '' }}{{ number_format($apbd_pend_diff / 1e9, 0, ',', '.') }} M)
                </span>
            </div>
            <div style="font-size: 11px; opacity: 0.8; margin-top: 2px;">
                PAD: Rp {{ number_format($apbd_pad_p / 1e12, 2, ',', '.') }} T | Transfer: Rp {{ number_format($apbd_transfer_p / 1e12, 2, ',', '.') }} T
            </div>
        </div>
    </div>

    <!-- Card 2: Total Belanja APBD -->
    <div class="kpi-card card-apbd-belanja">
        <i class="fas fa-shopping-cart kpi-icon-watermark"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-money-check-alt"></i> Total Belanja (APBD-P)</div>
            <div class="kpi-main-value">Rp {{ number_format($apbd_bel_p / 1e12, 2, ',', '.') }} T</div>
        </div>
        <div>
            <div class="kpi-subtitle">
                <span>Murni: Rp {{ number_format($apbd_bel_a / 1e12, 2, ',', '.') }} T</span>
                <span style="font-weight: 700; color: {{ $apbd_bel_diff >= 0 ? '#fecaca' : '#bbf7d0' }};">
                    ({{ $apbd_bel_diff >= 0 ? '+' : '' }}{{ number_format($apbd_bel_diff / 1e9, 0, ',', '.') }} M)
                </span>
            </div>
            <div style="font-size: 11px; opacity: 0.8; margin-top: 2px;">
                Operasi: Rp {{ number_format(((float)($apbd_kpi->belanja_operasi_perubahan ?? 0)) / 1e12, 2, ',', '.') }} T | Modal: Rp {{ number_format(((float)($apbd_kpi->belanja_modal_perubahan ?? 0)) / 1e12, 2, ',', '.') }} T
            </div>
        </div>
    </div>

    <!-- Card 3: Surplus / Defisit APBD -->
    <div class="kpi-card card-apbd-surplus">
        <i class="fas fa-balance-scale kpi-icon-watermark"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-chart-line"></i> Surplus / (Defisit) APBD</div>
            <div class="kpi-main-value" style="{{ $apbd_surplus_p < 0 ? 'color: #fee2e2;' : '' }}">
                Rp {{ number_format($apbd_surplus_p / 1e12, 2, ',', '.') }} T
            </div>
        </div>
        <div>
            <div class="kpi-subtitle">
                <span>Status: <strong>{{ $apbd_surplus_p >= 0 ? 'Surplus Anggaran' : 'Defisit Anggaran' }}</strong></span>
            </div>
            <div style="font-size: 11px; opacity: 0.8; margin-top: 2px;">
                Murni: Rp {{ number_format($apbd_surplus_a / 1e12, 2, ',', '.') }} T
            </div>
        </div>
    </div>

    <!-- Card 4: Pembiayaan Neto APBD -->
    <div class="kpi-card card-apbd-pembiayaan">
        <i class="fas fa-coins kpi-icon-watermark"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-exchange-alt"></i> Pembiayaan Neto</div>
            <div class="kpi-main-value">Rp {{ number_format($apbd_pembiayaan_neto / 1e9, 2, ',', '.') }} M</div>
        </div>
        <div>
            <div class="kpi-subtitle">
                <span>Penerimaan: Rp {{ number_format(((float)($apbd_kpi->pembiayaan_masuk_perubahan ?? 0)) / 1e9, 1, ',', '.') }} M</span>
            </div>
            <div style="font-size: 11px; opacity: 0.8; margin-top: 2px;">
                SILPA Penutup Defisit: Rp {{ number_format(((float)($apbd_kpi->pembiayaan_masuk_perubahan ?? 0)) / 1e9, 1, ',', '.') }} M
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODUL 2: KPI DATA LKPD (Warna Kontras: Emerald & Violet) -->
<!-- ======================================================== -->
<div class="module-heading lkpd-theme">
    <h2>
        <i class="fas fa-file-signature" style="color: #059669;"></i>
        Modul KPI Data LKPD (Realisasi) — Tahun {{ $selected_year }}
    </h2>
    <span class="module-badge badge-lkpd">
        <i class="fas fa-check-circle"></i> Hasil Audit / Laporan Realisasi Anggaran (LRA)
    </span>
</div>

@php
    $lkpd_pend_ang = (float)($lkpd_kpi->pendapatan_anggaran ?? 0);
    $lkpd_pend_real = (float)($lkpd_kpi->pendapatan_realisasi ?? 0);
    $lkpd_pend_pct = $lkpd_pend_ang > 0 ? ($lkpd_pend_real / $lkpd_pend_ang) * 100 : 0;

    $lkpd_bel_ang = (float)($lkpd_kpi->belanja_anggaran ?? 0);
    $lkpd_bel_real = (float)($lkpd_kpi->belanja_realisasi ?? 0);
    $lkpd_bel_pct = $lkpd_bel_ang > 0 ? ($lkpd_bel_real / $lkpd_bel_ang) * 100 : 0;

    $lkpd_pad_ang = (float)($lkpd_kpi->pad_anggaran ?? 0);
    $lkpd_pad_real = (float)($lkpd_kpi->pad_realisasi ?? 0);
    $lkpd_pad_pct = $lkpd_pad_ang > 0 ? ($lkpd_pad_real / $lkpd_pad_ang) * 100 : 0;

    $lkpd_surplus_real = $lkpd_pend_real - $lkpd_bel_real;
    $lkpd_pembiayaan = (float)($lkpd_kpi->pembiayaan_masuk_realisasi ?? 0);
    $lkpd_silpa = $lkpd_surplus_real + $lkpd_pembiayaan;
@endphp

<div class="kpi-grid">
    <!-- Card 1: Realisasi Pendapatan LKPD -->
    <div class="kpi-card card-lkpd-pendapatan">
        <i class="fas fa-receipt kpi-icon-watermark"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-chart-bar"></i> Realisasi Pendapatan</div>
            <div class="kpi-main-value">Rp {{ number_format($lkpd_pend_real / 1e12, 2, ',', '.') }} T</div>
        </div>
        <div>
            <div class="kpi-subtitle">
                <span>Capaian: <strong>{{ number_format($lkpd_pend_pct, 2, ',', '.') }}%</strong> dari target</span>
            </div>
            <div style="font-size: 11px; opacity: 0.8; margin-top: 2px;">
                Target: Rp {{ number_format($lkpd_pend_ang / 1e12, 2, ',', '.') }} T (Selisih: Rp {{ number_format(($lkpd_pend_real - $lkpd_pend_ang) / 1e9, 0, ',', '.') }} M)
            </div>
        </div>
    </div>

    <!-- Card 2: Realisasi Belanja LKPD -->
    <div class="kpi-card card-lkpd-belanja">
        <i class="fas fa-file-invoice kpi-icon-watermark"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-credit-card"></i> Realisasi Belanja</div>
            <div class="kpi-main-value">Rp {{ number_format($lkpd_bel_real / 1e12, 2, ',', '.') }} T</div>
        </div>
        <div>
            <div class="kpi-subtitle">
                <span>Serapan: <strong>{{ number_format($lkpd_bel_pct, 2, ',', '.') }}%</strong> dari anggaran</span>
            </div>
            <div style="font-size: 11px; opacity: 0.8; margin-top: 2px;">
                Sisa Anggaran (SiPA): Rp {{ number_format(($lkpd_bel_ang - $lkpd_bel_real) / 1e12, 2, ',', '.') }} T
            </div>
        </div>
    </div>

    <!-- Card 3: Realisasi PAD LKPD -->
    <div class="kpi-card card-lkpd-pad">
        <i class="fas fa-landmark kpi-icon-watermark"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-city"></i> Realisasi PAD</div>
            <div class="kpi-main-value">Rp {{ number_format($lkpd_pad_real / 1e12, 2, ',', '.') }} T</div>
        </div>
        <div>
            <div class="kpi-subtitle">
                <span>Efektivitas PAD: <strong>{{ number_format($lkpd_pad_pct, 2, ',', '.') }}%</strong></span>
            </div>
            <div style="font-size: 11px; opacity: 0.8; margin-top: 2px;">
                Target PAD: Rp {{ number_format($lkpd_pad_ang / 1e12, 2, ',', '.') }} T
            </div>
        </div>
    </div>

    <!-- Card 4: Surplus & SiLPA LKPD -->
    <div class="kpi-card card-lkpd-silpa">
        <i class="fas fa-piggy-bank kpi-icon-watermark"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-balance-scale-right"></i> Surplus & SiLPA Akhir</div>
            <div class="kpi-main-value">Rp {{ number_format($lkpd_silpa / 1e12, 2, ',', '.') }} T</div>
        </div>
        <div>
            <div class="kpi-subtitle">
                <span>Surplus LRA: Rp {{ number_format($lkpd_surplus_real / 1e9, 0, ',', '.') }} M</span>
            </div>
            <div style="font-size: 11px; opacity: 0.8; margin-top: 2px;">
                Pembiayaan Neto: Rp {{ number_format($lkpd_pembiayaan / 1e9, 0, ',', '.') }} M
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODUL 3: KPI RASIO KINERJA KEUANGAN DAERAH                -->
<!-- ======================================================== -->
<div class="module-heading ratio-theme">
    <h2>
        <i class="fas fa-tachometer-alt" style="color: #7c3aed;"></i>
        Modul Rasio Kinerja Keuangan Daerah — Tahun {{ $selected_year }}
    </h2>
    <span class="module-badge badge-ratio">
        <i class="fas fa-percentage"></i> Indikator Analisis Keuangan
    </span>
</div>

<div class="kpi-grid">
    <!-- Rasio 1: Desentralisasi -->
    <div class="kpi-card card-ratio-1">
        <i class="fas fa-network-wired kpi-icon-watermark" style="color: #2563eb;"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-chart-pie"></i> Derajat Desentralisasi</div>
            <div class="kpi-main-value">{{ number_format((float)($ratios->desentralisasi ?? 0), 2, ',', '.') }}%</div>
        </div>
        <div>
            <div class="kpi-subtitle" style="color: #64748b;">
                Proporsi PAD terhadap Total Realisasi Pendapatan Daerah
            </div>
        </div>
    </div>

    <!-- Rasio 2: Ketergantungan Keuangan -->
    <div class="kpi-card card-ratio-2">
        <i class="fas fa-handshake kpi-icon-watermark" style="color: #d97706;"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-link"></i> Ketergantungan Keuangan</div>
            <div class="kpi-main-value">{{ number_format((float)($ratios->ketergantungan ?? 0), 2, ',', '.') }}%</div>
        </div>
        <div>
            <div class="kpi-subtitle" style="color: #64748b;">
                Porsi Dana Transfer Pemerintah Pusat terhadap Pendapatan
            </div>
        </div>
    </div>

    <!-- Rasio 3: Kemandirian Keuangan -->
    <div class="kpi-card card-ratio-3">
        <i class="fas fa-shield-alt kpi-icon-watermark" style="color: #059669;"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-star"></i> Kemandirian Keuangan</div>
            <div class="kpi-main-value">{{ number_format((float)($ratios->kemandirian ?? 0), 2, ',', '.') }}%</div>
        </div>
        <div>
            <div class="kpi-subtitle" style="color: #64748b;">
                Kemampuan PAD membiayai belanja dibandingkan transfer
            </div>
        </div>
    </div>

    <!-- Rasio 4: Efektivitas PAD -->
    <div class="kpi-card card-ratio-4">
        <i class="fas fa-bullseye kpi-icon-watermark" style="color: #7c3aed;"></i>
        <div>
            <div class="kpi-title"><i class="fas fa-crosshairs"></i> Efektivitas PAD</div>
            <div class="kpi-main-value">{{ number_format((float)($ratios->efektivitas_pad ?? 0), 2, ',', '.') }}%</div>
        </div>
        <div>
            <div class="kpi-subtitle" style="color: #64748b;">
                Rasio Realisasi PAD terhadap Target Anggaran PAD
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- GRAFIK VISUALISASI TREN MULTI-TAHUN                      -->
<!-- ======================================================== -->
<div class="charts-grid">
    <!-- Chart 1: Tren APBD -->
    <div class="chart-box">
        <div class="chart-box-header">
            <div class="chart-box-title">
                <i class="fas fa-chart-line" style="color: #0284c7;"></i>
                Tren Anggaran & Perubahan APBD (Triliun Rp)
            </div>
            <span style="font-size: 11.5px; color: #64748b; font-weight: 500;">Multi-Tahun</span>
        </div>
        <div class="chart-canvas-wrapper">
            <canvas id="apbdChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Tren LKPD -->
    <div class="chart-box">
        <div class="chart-box-header">
            <div class="chart-box-title">
                <i class="fas fa-chart-bar" style="color: #059669;"></i>
                Tren Realisasi Pendapatan & Belanja LKPD (Triliun Rp)
            </div>
            <span style="font-size: 11.5px; color: #64748b; font-weight: 500;">Multi-Tahun</span>
        </div>
        <div class="chart-canvas-wrapper">
            <canvas id="lkpdChart"></canvas>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- TABEL DETAIL DATA (LAMPIRAN I APBD)                      -->
<!-- ======================================================== -->
<div class="table-section">
    <div class="table-section-header">
        <div class="table-section-title">
            <i class="fas fa-table" style="color: #2563eb;"></i>
            Rincian Data Lampiran I - APBD Tahun {{ $selected_year }}
        </div>
        <button type="button" class="btn-toggle-table" onclick="toggleTableDetails()">
            <i class="fas fa-chevron-down" id="toggleTableIcon"></i>
            <span id="toggleTableText">Sembunyikan Tabel</span>
        </button>
    </div>

    <div class="table-container-collapsible" id="tableContainer">
        @if(!empty($result))
            <table>
                <thead>
                    <tr>
                        <th style="display: none;">ID</th>
                        <th style="text-align: center;">Tahun</th>
                        <th style="text-align: center;">Kode</th>
                        <th style="text-align: center;">Uraian</th>
                        <th style="text-align: center;">Anggaran</th>
                        <th style="text-align: center;">Perubahan</th>
                        <th style="text-align: center;">Bertambah/Berkurang</th>
                        <th style="text-align: center;">Persentase</th>
                        <th style="text-align: center;">Level</th>
                        <th style="text-align: center;">Jenis APBD</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $row)
                        @php
                            $is_level_1_or_2 = ($row->id_kode_level == 1 || $row->id_kode_level == 2);
                            $tagOpen = $is_level_1_or_2 ? '<strong>' : '';
                            $tagClose = $is_level_1_or_2 ? '</strong>' : '';
                        @endphp
                        <tr>
                            <td style="display: none;">{{ $row->id_kode_level }}</td>
                            <td>{!! $tagOpen . $row->tahun . $tagClose !!}</td>
                            <td>{!! $tagOpen . $row->kode . $tagClose !!}</td>
                            <td>{!! $tagOpen . $row->uraian . $tagClose !!}</td>
                            <td style="text-align:right; {{ (float)($row->anggaran ?? 0) < 0 ? 'color: #dc2626;' : '' }}">{!! $tagOpen . number_format($row->anggaran ?? 0, 2, ',', '.') . $tagClose !!}</td>
                            <td style="text-align:right; {{ (float)($row->perubahan ?? 0) < 0 ? 'color: #dc2626;' : '' }}">{!! $tagOpen . number_format($row->perubahan ?? 0, 2, ',', '.') . $tagClose !!}</td>
                            <td style="text-align:right; {{ (float)($row->bertambah_berkurang ?? 0) < 0 ? 'color: #dc2626;' : '' }}">{!! $tagOpen . number_format($row->bertambah_berkurang ?? 0, 2, ',', '.') . $tagClose !!}</td>
                            <td style="text-align:right; {{ (float)($row->persentase ?? 0) < 0 ? 'color: #dc2626;' : '' }}">{!! $tagOpen . number_format($row->persentase ?? 0, 2, ',', '.') . '%' . $tagClose !!}</td>
                            <td style="text-align:left">{!! $tagOpen . $row->nama_level . $tagClose !!}</td>
                            <td style="text-align:right">{!! $tagOpen . $row->jenis_apbd . $tagClose !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="padding: 15px; color: #64748b; margin: 0;">Belum ada data untuk tahun {{ $selected_year }}.</p>
        @endif
    </div>
</div>

<script>
    // --- 1. Script Grafik APBD ---
    const apbdTrendData = @json($trend_apbd);
    const apbdLabels = apbdTrendData.map(item => item.tahun);
    const apbdPendapatanPerubahan = apbdTrendData.map(item => parseFloat(item.pendapatan_perubahan));
    const apbdBelanjaPerubahan = apbdTrendData.map(item => parseFloat(item.belanja_perubahan));

    const ctxApbd = document.getElementById('apbdChart').getContext('2d');
    new Chart(ctxApbd, {
        type: 'bar',
        data: {
            labels: apbdLabels,
            datasets: [
                {
                    label: 'Pendapatan APBD-P (Triliun)',
                    data: apbdPendapatanPerubahan,
                    backgroundColor: 'rgba(2, 132, 199, 0.85)', // Sky blue
                    borderColor: '#0284c7',
                    borderWidth: 1,
                    borderRadius: 5
                },
                {
                    label: 'Belanja APBD-P (Triliun)',
                    data: apbdBelanjaPerubahan,
                    backgroundColor: 'rgba(13, 148, 136, 0.85)', // Teal
                    borderColor: '#0d9488',
                    borderWidth: 1,
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { boxWidth: 12, font: { size: 12, weight: '600' } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID') + ' T';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return 'Rp ' + value + ' T'; },
                        font: { size: 11 }
                    },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: '600' } }
                }
            }
        }
    });

    // --- 2. Script Grafik LKPD ---
    const lkpdTrendData = @json($trend_lkpd).slice(-8); // Tampilkan 8 tahun terakhir agar proporsional
    const lkpdLabels = lkpdTrendData.map(item => item.tahun);
    const lkpdPendapatanReal = lkpdTrendData.map(item => parseFloat(item.pendapatan_realisasi));
    const lkpdBelanjaReal = lkpdTrendData.map(item => parseFloat(item.belanja_realisasi));

    const ctxLkpd = document.getElementById('lkpdChart').getContext('2d');
    new Chart(ctxLkpd, {
        type: 'line',
        data: {
            labels: lkpdLabels,
            datasets: [
                {
                    label: 'Realisasi Pendapatan (Triliun)',
                    data: lkpdPendapatanReal,
                    borderColor: '#059669', // Emerald
                    backgroundColor: 'rgba(5, 150, 105, 0.12)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 3,
                    pointBackgroundColor: '#059669',
                    pointRadius: 4
                },
                {
                    label: 'Realisasi Belanja (Triliun)',
                    data: lkpdBelanjaReal,
                    borderColor: '#e11d48', // Rose Red
                    backgroundColor: 'rgba(225, 29, 72, 0.08)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 3,
                    pointBackgroundColor: '#e11d48',
                    pointRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { boxWidth: 12, font: { size: 12, weight: '600' } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID') + ' T';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) { return 'Rp ' + value + ' T'; },
                        font: { size: 11 }
                    },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: '600' } }
                }
            }
        }
    });

    // --- 3. Toggle Table Collapsible ---
    function toggleTableDetails() {
        const table = document.getElementById('tableContainer');
        const icon = document.getElementById('toggleTableIcon');
        const text = document.getElementById('toggleTableText');

        if (table.style.display === 'none') {
            table.style.display = 'block';
            icon.className = 'fas fa-chevron-down';
            text.textContent = 'Sembunyikan Tabel';
        } else {
            table.style.display = 'none';
            icon.className = 'fas fa-chevron-up';
            text.textContent = 'Tampilkan Tabel';
        }
    }
</script>
@endsection
