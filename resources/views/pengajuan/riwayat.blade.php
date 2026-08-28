@extends('layouts.app')

@section('title', 'Riwayat Pengajuan')
@section('crumbs', 'Menu Dosen / Riwayat Pengajuan')

@section('content')
    <style>
        /* ==== Riwayat Pengajuan v2 — scoped styles (lebih cerah & hidup) ==== */
        .rp-card {
            position: relative;
        }

        /* --- Header banner --- */
        .rp-header {
            background: linear-gradient(120deg, #0b3d2e 0%, #0f5c44 45%, #00875A 100%);
            border-radius: 16px;
            padding: 22px 26px;
            margin-bottom: 20px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            box-shadow: 0 10px 24px -8px rgba(11, 61, 46, .5);
        }

        .rp-header h3 {
            color: #fff;
            margin-bottom: 4px;
            font-size: 19px;
        }

        .rp-header .desc {
            font-size: 12.5px;
            color: rgba(255, 255, 255, .85);
        }

        /* --- Stat chips --- */
        .rp-stats {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .rp-stat {
            background: rgba(255, 255, 255, .16);
            backdrop-filter: blur(2px);
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 12px;
            padding: 8px 16px;
            text-align: center;
            min-width: 78px;
            transition: transform .15s ease, background .15s ease;
        }

        .rp-stat:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, .24);
        }

        .rp-stat b {
            display: block;
            font-size: 18px;
            line-height: 1.1;
        }

        .rp-stat span {
            font-size: 10.5px;
            opacity: .9;
        }

        /* --- Tabs (pill, gradient aktif) --- */
        .rp-tabs {
            display: flex;
            gap: 6px;
            margin-bottom: 18px;
            flex-wrap: wrap;
            background: #eef7f2;
            padding: 5px;
            border-radius: 12px;
            width: fit-content;
        }

        .rp-tab {
            padding: 9px 18px;
            font-size: 12.5px;
            font-weight: 700;
            border-radius: 9px;
            border: none;
            background: transparent;
            color: #4b7263;
            text-decoration: none;
            transition: all .18s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .rp-tab:hover:not(.is-active) {
            background: #fff;
            color: #00875A;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
        }

        .rp-tab.is-active {
            background: linear-gradient(120deg, #0b3d2e, #00875A);
            color: #fff;
            box-shadow: 0 4px 12px -2px rgba(11, 61, 46, .5);
            transform: translateY(-1px);
        }

        .rp-tab .cnt {
            font-size: 10.5px;
            opacity: .9;
            background: rgba(0, 0, 0, .08);
            padding: 1px 7px;
            border-radius: 20px;
        }

        .rp-tab.is-active .cnt {
            background: rgba(255, 255, 255, .28);
        }

        /* --- Filter bar --- */
        .rp-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        .rp-filters input[type="text"] {
            flex: 1;
            min-width: 220px;
            padding: 11px 14px 11px 38px;
            border: 1.5px solid #e2ece7;
            border-radius: 10px;
            font-size: 13px;
            background: #fbfdfc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2300875A' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E") no-repeat 12px center;
            transition: border-color .15s, box-shadow .15s;
        }

        .rp-filters input[type="text"]:focus {
            outline: none;
            border-color: #00875A;
            box-shadow: 0 0 0 4px rgba(0, 135, 90, .14);
        }

        .rp-filters select {
            padding: 11px 14px;
            border: 1.5px solid #e2ece7;
            border-radius: 10px;
            font-size: 13px;
            background: #fbfdfc;
            color: #374151;
            cursor: pointer;
        }

        .rp-filters select:focus {
            outline: none;
            border-color: #00875A;
        }

        .rp-filters .btn-primary {
            border-radius: 10px;
            font-weight: 700;
            padding: 11px 20px;
            background: linear-gradient(120deg, #0b3d2e, #00875A);
            border: none;
            box-shadow: 0 4px 10px -2px rgba(0, 135, 90, .4);
            transition: transform .12s ease;
        }

        .rp-filters .btn-primary:hover {
            transform: translateY(-1px);
        }

        .rp-filters .btn-outline {
            border-radius: 10px;
            font-weight: 700;
            padding: 11px 20px;
        }

        /* --- Table --- */
        .rp-table-wrap {
            border: 1px solid #eef2f0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(16, 24, 40, .04);
        }

        .rp-table-wrap table {
            margin: 0;
            border-collapse: collapse;
            width: 100%;
        }

        .rp-table-wrap thead th {
            background: linear-gradient(180deg, #f3fbf7 0%, #eaf7f0 100%);
            color: #0b6b48;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 800;
            padding: 13px 16px;
            border-bottom: 1.5px solid #dcf0e6;
            text-align: left;
        }

        .rp-table-wrap tbody td {
            padding: 14px 16px;
            font-size: 13px;
            color: #1f2937;
            border-bottom: 1px solid #f3f5f4;
            vertical-align: middle;
        }

        .rp-table-wrap tbody tr:last-child td {
            border-bottom: none;
        }

        .rp-table-wrap tbody tr:nth-child(even) {
            background: #fafcfb;
        }

        .rp-table-wrap tbody tr.clickable {
            cursor: pointer;
            transition: background .15s ease, box-shadow .15s ease;
        }

        .rp-table-wrap tbody tr.clickable:hover {
            background: #eafbf3;
            box-shadow: inset 3px 0 0 #00875A;
        }

        .rp-kode {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px;
            font-weight: 600;
            color: #00875A;
            background: #e3f7ee;
            padding: 4px 9px;
            border-radius: 7px;
            display: inline-block;
        }

        .rp-judul {
            font-weight: 700;
            color: #111827;
        }

        /* --- Status badge, warna lebih jenuh --- */
        .rp-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 800;
        }

        .rp-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex: none;
        }

        .rp-badge.b-proses {
            background: #fef3c7;
            color: #b45309;
        }

        .rp-badge.b-proses .dot {
            background: #f59e0b;
        }

        .rp-badge.b-disetujui {
            background: #d1fae5;
            color: #047857;
        }

        .rp-badge.b-disetujui .dot {
            background: #10b981;
        }

        .rp-badge.b-revisi {
            background: #fee2e2;
            color: #b91c1c;
        }

        .rp-badge.b-revisi .dot {
            background: #ef4444;
        }

        .rp-badge.b-jalur-mandiri {
            background: #e0e7ff;
            color: #4338ca;
        }

        .rp-badge.b-jalur-mandiri .dot {
            background: #6366f1;
        }

        .rp-badge.b-jalur-simlitabkes {
            background: #cffafe;
            color: #0e7490;
        }

        .rp-badge.b-jalur-simlitabkes .dot {
            background: #06b6d4;
        }

        .rp-tahap {
            font-size: 12px;
            color: #4b5563;
            font-weight: 600;
        }

        .rp-tgl {
            font-size: 12.5px;
            color: #6b7280;
            white-space: nowrap;
        }

        .rp-view-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            color: #00875A;
            background: #e3f7ee;
            transition: all .15s ease;
            text-decoration: none;
            font-size: 14px;
        }

        .rp-view-btn:hover {
            background: linear-gradient(120deg, #0b3d2e, #00875A);
            color: #fff;
            transform: scale(1.06);
        }

        /* --- Empty state --- */
        .rp-empty {
            text-align: center;
            padding: 56px 24px;
            color: #6b7280;
        }

        .rp-empty .icon {
            font-size: 40px;
            margin-bottom: 10px;
            display: inline-flex;
            width: 68px;
            height: 68px;
            align-items: center;
            justify-content: center;
            background: #e3f7ee;
            border-radius: 50%;
        }

        .rp-empty .title {
            font-weight: 800;
            color: #111827;
            margin: 10px 0 4px;
            font-size: 14.5px;
        }

        .rp-empty .desc {
            font-size: 12.5px;
        }

        .rp-pagination {
            margin-top: 18px;
            font-size: 12.5px;
        }

        @media (max-width: 720px) {
            .rp-table-wrap {
                overflow-x: auto;
            }

            .rp-table-wrap table {
                min-width: 760px;
            }

            .rp-header {
                padding: 18px 20px;
            }
        }
    </style>

    <div class="card rp-card">
        @if (session('success'))
            <div class="alert-box alert-info" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-box alert-amber" style="margin-bottom:16px;">⚠️ {{ session('error') }}</div>
        @endif

        {{-- Header banner dengan ringkasan angka --}}
        <div class="rp-header">
            <div>
                <h3>📁 Riwayat Pengajuan</h3>
                <div class="desc">Semua pengajuan penelitian &amp; pengabdian yang pernah Anda ajukan.</div>
            </div>
            <div class="rp-stats">
                <div class="rp-stat"><b>{{ $counts['semua'] }}</b><span>Total</span></div>
                <div class="rp-stat"><b>{{ $counts['proses'] }}</b><span>Proses</span></div>
                <div class="rp-stat"><b>{{ $counts['disetujui'] }}</b><span>Disetujui</span></div>
                <div class="rp-stat"><b>{{ $counts['revisi'] }}</b><span>Revisi</span></div>
            </div>
        </div>

        {{-- Tabs --}}
        @php
            $tabs = [
                'semua' => 'Semua',
                'proses' => 'Dalam Proses',
                'disetujui' => 'Disetujui',
                'revisi' => 'Direvisi',
            ];
            $tabIcons = ['semua' => '📋', 'proses' => '⏳', 'disetujui' => '✅', 'revisi' => '✏️'];
        @endphp
        <div class="rp-tabs">
            @foreach ($tabs as $key => $label)
                <a href="{{ route('riwayat', array_filter(['status' => $key, 'jenis' => $filterJenis, 'jalur' => $filterJalur, 'q' => $q])) }}"
                    class="rp-tab {{ $filterStatus === $key ? 'is-active' : '' }}">
                    <span>{{ $tabIcons[$key] }}</span>
                    <span>{{ $label }}</span>
                    <span class="cnt">{{ $counts[$key] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('riwayat') }}" class="rp-filters">
            <input type="hidden" name="status" value="{{ $filterStatus }}">
            <input type="text" name="q" placeholder="Cari judul / kode pengajuan..." value="{{ $q }}">
            <select name="jenis" onchange="this.form.submit()">
                <option value="">Jenis (Semua)</option>
                <option value="penelitian" {{ $filterJenis === 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                <option value="pengabdian" {{ $filterJenis === 'pengabdian' ? 'selected' : '' }}>Pengabdian</option>
            </select>
            <select name="jalur" onchange="this.form.submit()">
                <option value="">Jalur (Semua)</option>
                <option value="simlitabkes" {{ $filterJalur === 'simlitabkes' ? 'selected' : '' }}>Simlitabkes</option>
                <option value="mandiri" {{ $filterJalur === 'mandiri' ? 'selected' : '' }}>Mandiri</option>
            </select>
            <button class="btn btn-primary" type="submit">Cari</button>
            <a href="{{ route('riwayat') }}" class="btn btn-outline">Reset</a>
        </form>

        {{-- Table --}}
        <div class="rp-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Judul</th>
                        <th>Jenis</th>
                        <th>Skema</th>
                        <th>Jalur</th>
                        <th>Tahap</th>
                        <th>Tgl Pengajuan</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $p)
                        @php
                            [$label, $class] = $p->statusLabel();
                            $rpStatusClass = match (true) {
                                str_contains($class, 'proses') => 'b-proses',
                                str_contains($class, 'disetujui') => 'b-disetujui',
                                str_contains($class, 'revisi') => 'b-revisi',
                                default => 'b-proses',
                            };
                            $jalurLabel = $p->jalur === 'mandiri' ? 'Mandiri' : 'Simlitabkes';
                            $jalurClass = $p->jalur === 'mandiri' ? 'b-jalur-mandiri' : 'b-jalur-simlitabkes';
                        @endphp
                        <tr class="clickable" onclick="window.location='{{ route('pengajuan.detail', $p) }}'">
                            <td><span class="rp-kode">{{ $p->kode }}</span></td>
                            <td class="rp-judul">{{ $p->judul }}</td>
                            <td>{{ ucfirst($p->jenis) }}</td>
                            <td>{{ $p->skema->nama ?? '-' }}</td>
                            <td><span class="rp-badge {{ $jalurClass }}"><span
                                        class="dot"></span>{{ $jalurLabel }}</span></td>
                            <td class="rp-tahap">{{ ucwords(str_replace('_', ' ', $p->tahap)) }}</td>
                            <td class="rp-tgl">{{ $p->created_at->format('d/m/Y') }}</td>
                            <td><span class="rp-badge {{ $rpStatusClass }}"><span
                                        class="dot"></span>{{ $label }}</span></td>
                            <td style="text-align:center;">
                                <a href="{{ route('pengajuan.detail', $p) }}" class="rp-view-btn" title="Lihat detail"
                                    onclick="event.stopPropagation();">👁</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="rp-empty">
                                    <div class="icon">🗂️</div>
                                    <div class="title">Belum ada pengajuan yang cocok</div>
                                    <div class="desc">Coba ubah kata kunci pencarian atau filter yang digunakan.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="rp-pagination">{{ $daftar->links() }}</div>
    </div>
@endsection
