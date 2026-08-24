@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <div class="card wizard-card">
        @include('pengajuan._stepper', ['current' => 5])

        <div class="review-section">
            <h4>Jalur & Skema</h4>
            <div class="review-row"><span class="k">Jenis</span><span class="v">{{ ucfirst($w['jenis']) }}</span>
            </div>
            <div class="review-row"><span class="k">Jalur</span><span
                    class="v">{{ $w['jalur'] === 'mandiri' ? 'Mandiri' : 'Simlitabkes' }}</span></div>
            <div class="review-row"><span class="k">Skema</span><span class="v">{{ $skema->nama ?? '-' }}</span>
            </div>
            <div class="review-row"><span class="k">Rumpun Ilmu</span><span
                    class="v">{{ $rumpunIlmu->nama ?? '-' }}</span></div>
            <div class="review-row"><span class="k">Judul</span><span class="v">{{ $w['judul'] }}</span></div>
            <div class="review-row"><span class="k">Tahun</span><span class="v">Anggaran
                    {{ $w['tahun_anggaran'] }} · Pengajuan {{ $w['tahun_pengajuan'] }} · Pelaksanaan Tahap
                    {{ $w['tahun_pelaksanaan'] }} · Capaian {{ $w['tahun_capaian'] }}</span></div>
        </div>

        <div class="review-section">
            <h4>Ketua & Tim</h4>
            <div class="review-row"><span class="k">Ketua</span><span class="v">{{ $ketua->nama }} (NIP
                    {{ $ketua->nip }})</span></div>
            @foreach ($anggotaTim as $a)
                <div class="review-row"><span class="k">Anggota</span><span class="v">{{ $a->nama }} (NIP
                        {{ $a->nip }})</span></div>
            @endforeach
            @foreach ($anggotaLuar as $luar)
                <div class="review-row"><span class="k">Anggota (luar sistem)</span><span
                        class="v">{{ $luar['nama'] }}{{ $luar['instansi'] ? ' — ' . $luar['instansi'] : '' }}</span>
                </div>
            @endforeach
            @if ($anggotaTim->isEmpty() && empty($anggotaLuar))
                <div class="review-row"><span class="k">Anggota</span><span class="v">Tidak ada</span></div>
            @endif
        </div>

        <div class="review-section">
            <h4>Proposal</h4>
            <div class="review-row"><span class="k">Dokumen</span><span class="v">{{ $w['proposal_nama_asli'] }}
                    ({{ number_format($w['proposal_size'] / 1024, 0) }} KB)</span></div>
            <div class="review-row"><span class="k">Total Biaya Usulan</span><span class="v">Rp
                    {{ number_format($w['total_biaya'], 0, ',', '.') }}</span></div>
        </div>

        <div class="review-section">
            <h4>Rencana Luaran</h4>
            @if ($luaranWajib)
                <div class="review-row"><span class="k">Luaran Wajib</span><span
                        class="v">{{ $luaranWajib->nama }}{{ $w['luaran_wajib_opsi'] ? ' — ' . $w['luaran_wajib_opsi'] : '' }}</span>
                </div>
            @endif
            @forelse ($luaranTambahanDipilih as $l)
                <div class="review-row"><span class="k">Tambahan</span><span
                        class="v">{{ $l->nama }}{{ ($w['luaran_tambahan'][$l->id] ?? null) && $w['luaran_tambahan'][$l->id] !== '1' ? ' — ' . $w['luaran_tambahan'][$l->id] : '' }}</span>
                </div>
            @empty
                <div class="review-row"><span class="k">Tambahan</span><span class="v">Tidak ada</span></div>
            @endforelse
            @if ($w['inovasi_produk'])
                <div class="review-row"><span class="k">Inovasi Produk</span><span
                        class="v">{{ $w['inovasi_produk'] }}</span></div>
            @endif
        </div>

        <div class="alert-box alert-info">ℹ️ Setelah dikirim, status pengajuan menjadi <b>Dalam Proses</b>.
            {{ $w['jalur'] === 'mandiri' ? 'Alur selanjutnya: Proposal → langsung Laporan Hasil (tanpa Laporan Kemajuan).' : 'Alur selanjutnya: Proposal → Laporan Kemajuan → Laporan Hasil, masing-masing divalidasi bertahap.' }}
        </div>

        <form method="POST" action="{{ route('pengajuan.submit') }}">
            @csrf
            <div style="display:flex; justify-content:space-between; margin-top:20px;">
                <a href="{{ route('pengajuan.step4') }}" class="btn btn-outline">Kembali</a>
                <button class="btn btn-primary" type="submit">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
@endsection
