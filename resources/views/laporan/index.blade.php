@extends('layouts.app')

@section('title', $judulHalaman)
@section('crumbs', 'Menu Dosen / ' . $judulHalaman)

@section('content')
    <div class="card">
        @if (session('success'))
            <div class="alert-box alert-info" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-box alert-amber" style="margin-bottom:16px;">⚠️ {{ session('error') }}</div>
        @endif

        <h3 style="margin-bottom:4px;">{{ $judulHalaman }}</h3>
        <div class="sub" style="margin-bottom:18px; font-size:12.5px; color:var(--ink-500);">
            Daftar pengajuan yang sudah berada di tahap
            {{ $judulHalaman }}{{ $tipe === 'kemajuan' ? ' (khusus jalur Simlitabkes)' : '' }}.
        </div>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Skema</th>
                    <th>Status Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftar as $p)
                    @php
                        $laporan = $tipe === 'kemajuan' ? $p->laporanKemajuan : $p->laporanHasil;
                    @endphp
                    <tr>
                        <td style="font-family:'JetBrains Mono', monospace; font-size:12px;">{{ $p->kode }}</td>
                        <td>{{ $p->judul }}</td>
                        <td>{{ $p->skema->nama ?? '-' }}</td>
                        <td>
                            @if ($laporan)
                                @php [$label, $class] = $laporan->statusLabel(); @endphp
                                <span class="badge {{ $class }}">{{ $label }}</span>
                            @else
                                <span class="badge b-menunggu">Belum diunggah</span>
                            @endif
                        </td>
                        <td><a href="{{ route('laporan.form', [$tipe, $p]) }}" class="btn btn-outline"
                                style="padding:6px 14px; font-size:12px;">{{ $laporan ? 'Lihat / Unggah Ulang' : 'Unggah' }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:var(--ink-500); padding:24px;">Belum ada
                            pengajuan yang berada di tahap ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
