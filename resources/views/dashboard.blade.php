@extends('layouts.app')

@section('title', 'Dashboard')
@section('crumbs', 'Dashboard')

@section('content')
    <style>
        /* ==== Dashboard — scoped styles ==== */

        /* --- Stat cards --- */
        .db-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 18px;
        }

        .db-stat {
            background: #fff;
            border: 1px solid #eef2f0;
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            box-shadow: 0 1px 3px rgba(16, 24, 40, .04);
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .db-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px -10px rgba(16, 24, 40, .18);
        }

        .db-stat .top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .db-stat .label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7280;
        }

        .db-stat .num {
            font-size: 30px;
            font-weight: 800;
            color: #111827;
            line-height: 1;
        }

        .db-stat .ic {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex: none;
        }

        .db-stat.total .ic {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .db-stat.proses .ic {
            background: #fef3c7;
            color: #b45309;
        }

        .db-stat.disetujui .ic {
            background: #d1fae5;
            color: #047857;
        }

        .db-stat.revisi .ic {
            background: #fee2e2;
            color: #b91c1c;
        }

        .db-stat.total {
            border-top: 3px solid #3b82f6;
            background: linear-gradient(180deg, #eff6ff 0%, #ffffff 65%);
        }

        .db-stat.proses {
            border-top: 3px solid #f59e0b;
            background: linear-gradient(180deg, #fffbeb 0%, #ffffff 65%);
        }

        .db-stat.disetujui {
            border-top: 3px solid #10b981;
            background: linear-gradient(180deg, #ecfdf5 0%, #ffffff 65%);
        }

        .db-stat.revisi {
            border-top: 3px solid #ef4444;
            background: linear-gradient(180deg, #fef2f2 0%, #ffffff 65%);
        }

        .db-stat.total:hover {
            box-shadow: 0 10px 22px -10px rgba(59, 130, 246, .35);
        }

        .db-stat.proses:hover {
            box-shadow: 0 10px 22px -10px rgba(245, 158, 11, .35);
        }

        .db-stat.disetujui:hover {
            box-shadow: 0 10px 22px -10px rgba(16, 185, 129, .35);
        }

        .db-stat.revisi:hover {
            box-shadow: 0 10px 22px -10px rgba(239, 68, 68, .35);
        }

        /* --- Panels grid --- */
        .db-panels {
            display: grid;
            grid-template-columns: 1.3fr 1fr 1fr;
            gap: 16px;
            align-items: stretch;
        }

        .db-panel {
            background: #fff;
            border: 1px solid #eef2f0;
            border-radius: 14px;
            padding: 18px 20px 20px;
            box-shadow: 0 1px 3px rgba(16, 24, 40, .04);
            display: flex;
            flex-direction: column;
        }

        .db-panel h3 {
            font-size: 14px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #111827;
        }

        .db-panel h3 .ic {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: #e3f7ee;
            color: #00875A;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        /* --- Pengajuan terbaru table --- */
        .db-mini-table {
            width: 100%;
            border-collapse: collapse;
        }

        .db-mini-table thead th {
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #9ca3af;
            font-weight: 800;
            text-align: left;
            padding: 0 8px 8px;
            border-bottom: 1px solid #f0f2f1;
        }

        .db-mini-table tbody td {
            padding: 10px 8px;
            font-size: 12.5px;
            border-bottom: 1px solid #f5f6f5;
            color: #1f2937;
        }

        .db-mini-table tbody tr:last-child td {
            border-bottom: none;
        }

        .db-mini-table tbody tr {
            cursor: pointer;
            transition: background .15s ease;
        }

        .db-mini-table tbody tr:hover {
            background: #f4fbf7;
        }

        .db-mini-table .judul {
            font-weight: 700;
            color: #111827;
        }

        .db-mini-table .jenis {
            color: #6b7280;
        }

        .db-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 800;
        }

        .db-badge .dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
        }

        .db-badge.b-proses {
            background: #fef3c7;
            color: #b45309;
        }

        .db-badge.b-proses .dot {
            background: #f59e0b;
        }

        .db-badge.b-disetujui {
            background: #d1fae5;
            color: #047857;
        }

        .db-badge.b-disetujui .dot {
            background: #10b981;
        }

        .db-badge.b-revisi {
            background: #fee2e2;
            color: #b91c1c;
        }

        .db-badge.b-revisi .dot {
            background: #ef4444;
        }

        /* --- Timeline (Status Proses Pengajuan) --- */
        .db-timeline {
            list-style: none;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .db-timeline li {
            position: relative;
            padding-left: 26px;
            padding-bottom: 20px;
        }

        .db-timeline li:last-child {
            padding-bottom: 0;
        }

        .db-timeline li::before {
            content: '';
            position: absolute;
            left: 5px;
            top: 4px;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: #fff;
            border: 2.5px solid #d1d5db;
            z-index: 1;
        }

        .db-timeline li::after {
            content: '';
            position: absolute;
            left: 10px;
            top: 15px;
            bottom: -4px;
            width: 2px;
            background: #eef0ef;
        }

        .db-timeline li:last-child::after {
            display: none;
        }

        .db-timeline li.done::before {
            border-color: #00875A;
            background: #00875A;
        }

        .db-timeline li.done::after {
            background: #00875A;
        }

        .db-timeline li.active::before {
            border-color: #00875A;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(0, 135, 90, .18);
        }

        .db-timeline b {
            font-size: 13px;
            color: #111827;
            display: block;
        }

        .db-timeline li.muted b {
            color: #9ca3af;
            font-weight: 600;
        }

        .db-timeline .t {
            font-size: 11.5px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* --- Pengumuman --- */
        .db-announce {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .db-announce li {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 12px 0;
            border-bottom: 1px solid #f5f6f5;
        }

        .db-announce li:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .db-announce li:first-child {
            padding-top: 0;
        }

        .db-announce .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 5px;
            flex: none;
            background: #d1d5db;
        }

        .db-announce li.unread .dot {
            background: #00875A;
            box-shadow: 0 0 0 3px rgba(0, 135, 90, .16);
        }

        .db-announce b {
            font-size: 12.5px;
            color: #1f2937;
            display: block;
            line-height: 1.4;
        }

        .db-announce li.muted b {
            color: #6b7280;
            font-weight: 600;
        }

        .db-announce .t {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 3px;
        }

        /* --- Quick actions --- */
        .db-quick-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .db-quick-title h3 {
            font-size: 14px;
            color: #111827;
            margin: 0;
        }

        .db-quick {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .db-quick-btn {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 18px;
            border-radius: 12px;
            background: linear-gradient(120deg, #0b3d2e, #00875A);
            color: #fff;
            text-decoration: none;
            box-shadow: 0 6px 16px -6px rgba(11, 61, 46, .45);
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .db-quick-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px -6px rgba(11, 61, 46, .55);
        }

        .db-quick-btn .qic {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: rgba(255, 255, 255, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .db-quick-btn .qlabel {
            font-size: 13px;
            font-weight: 700;
        }

        @media (max-width: 1080px) {
            .db-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .db-panels {
                grid-template-columns: 1fr;
            }

            .db-quick {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    {{-- Stat cards --}}
    <div class="db-stats">
        <div class="db-stat total">
            <div class="top">
                <span class="label">Total Pengajuan</span>
                <div class="ic">📊</div>
            </div>
            <div class="num">{{ $stats['total'] }}</div>
        </div>
        <div class="db-stat proses">
            <div class="top">
                <span class="label">Dalam Proses</span>
                <div class="ic">⏳</div>
            </div>
            <div class="num">{{ $stats['proses'] }}</div>
        </div>
        <div class="db-stat disetujui">
            <div class="top">
                <span class="label">Disetujui</span>
                <div class="ic">✅</div>
            </div>
            <div class="num">{{ $stats['disetujui'] }}</div>
        </div>
        <div class="db-stat revisi">
            <div class="top">
                <span class="label">Perlu Revisi</span>
                <div class="ic">✏️</div>
            </div>
            <div class="num">{{ $stats['revisi'] }}</div>
        </div>
    </div>

    {{-- Panels --}}
    <div class="db-panels">
        <div class="db-panel">
            <h3><span class="ic">📁</span> Pengajuan Terbaru</h3>
            <table class="db-mini-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Jenis</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riwayatTerbaru as $r)
                        @php
                            $badgeMap = [
                                'proses' => ['Dalam Proses', 'b-proses'],
                                'disetujui' => ['Disetujui', 'b-disetujui'],
                                'revisi' => ['Direvisi', 'b-revisi'],
                            ];
                            [$label, $class] = $badgeMap[$r['status']];
                        @endphp
                        <tr>
                            <td class="judul">{{ $r['judul'] }}</td>
                            <td class="jenis">{{ $r['jenis'] }}</td>
                            <td><span class="db-badge {{ $class }}"><span
                                        class="dot"></span>{{ $label }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="db-panel">
            <h3><span class="ic">🧭</span> Status Proses Pengajuan</h3>
            <ul class="db-timeline">
                <li class="done"><b>Pengajuan</b>
                    <div class="t">Terkirim</div>
                </li>
                <li class="active"><b>Validasi Admin</b>
                    <div class="t">Sedang diproses</div>
                </li>
                <li class="muted"><b>Pelaksanaan Kegiatan</b></li>
                <li class="muted"><b>Laporan Kemajuan</b></li>
                <li class="muted"><b>Laporan Hasil</b></li>
                <li class="muted"><b>Luaran</b></li>
            </ul>
        </div>

        <div class="db-panel">
            <h3><span class="ic">📢</span> Pengumuman</h3>
            <ul class="db-announce">
                <li class="unread">
                    <div class="dot"></div>
                    <div>
                        <b>Pengajuan "Sistem Informasi Klinik" diterima</b>
                        <div class="t">16 Mei 2026 &middot; 10:30</div>
                    </div>
                </li>
                <li class="unread">
                    <div class="dot"></div>
                    <div>
                        <b>Laporan kemajuan "AI untuk Kesehatan" sudah divalidasi</b>
                        <div class="t">15 Mei 2026 &middot; 09:48</div>
                    </div>
                </li>
                <li class="muted">
                    <div class="dot"></div>
                    <div>
                        <b>Laporan hasil "Pemberdayaan UMKM" disetujui</b>
                        <div class="t">10 Mei 2026 &middot; 13:20</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="card" style="margin-top:18px; border-radius:14px;">
        <div class="db-quick-title">
            <h3>⚡ Aksi Cepat</h3>
        </div>
        <div class="db-quick">
            <a href="{{ route('pengajuan.step1') }}" class="db-quick-btn">
                <span class="qic">✎</span>
                <span class="qlabel">Buat Pengajuan Baru</span>
            </a>
            <a href="{{ route('laporan.kemajuan') }}" class="db-quick-btn">
                <span class="qic">▤</span>
                <span class="qlabel">Upload Laporan Kemajuan</span>
            </a>
            <a href="{{ route('laporan.index', 'hasil') }}" class="db-quick-btn">
                <span class="qic">▤</span>
                <span class="qlabel">Upload Laporan Hasil</span>
            </a>
            <a href="{{ route('luaran.index') }}" class="db-quick-btn">
                <span class="qic">★</span>
                <span class="qlabel">Input Luaran</span>
            </a>
        </div>
    </div>
@endsection
