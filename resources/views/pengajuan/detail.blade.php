@extends('layouts.app')

@section('title', 'Detail Pengajuan')
@section('crumbs', 'Menu Dosen / Riwayat Pengajuan / Detail')

@section('content')
<link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

@php [$label, $class] = $p->statusLabel(); @endphp

<div class="card wizard-card">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
    <div>
      <h3 style="margin-bottom:2px;">{{ $p->judul }}</h3>
      <div style="font-family:'JetBrains Mono', monospace; font-size:12px; color:var(--ink-500);">{{ $p->kode }}</div>
    </div>
    <span class="badge {{ $class }}">{{ $label }}</span>
  </div>

  @if ($p->status === 'revisi' && $p->catatan_validator)
    <div class="alert-box alert-amber">⚠️ <b>Catatan revisi dari admin:</b><br>{{ $p->catatan_validator }}</div>
  @endif

  <div class="review-section">
    <h4>Informasi Umum</h4>
    <div class="review-row"><span class="k">Jenis</span><span class="v">{{ ucfirst($p->jenis) }}</span></div>
    <div class="review-row"><span class="k">Jalur</span><span class="v">{{ $p->jalur === 'mandiri' ? 'Mandiri' : 'Simlitabkes' }}</span></div>
    <div class="review-row"><span class="k">Skema</span><span class="v">{{ $p->skema->nama ?? '-' }}</span></div>
    <div class="review-row"><span class="k">Rumpun Ilmu</span><span class="v">{{ $p->rumpunIlmu->nama ?? '-' }}</span></div>
    <div class="review-row"><span class="k">Tahun</span><span class="v">{{ $p->tahun }}</span></div>
    <div class="review-row"><span class="k">Tahap Saat Ini</span><span class="v">{{ ucwords(str_replace('_', ' ', $p->tahap)) }}</span></div>
    <div class="review-row"><span class="k">Tanggal Pengajuan</span><span class="v">{{ $p->created_at->format('d/m/Y H:i') }}</span></div>
  </div>

  <div class="review-section">
    <h4>Ketua & Tim</h4>
    @foreach ($p->tim as $t)
      <div class="review-row">
        <span class="k">{{ $t->peran === 'ketua' ? 'Ketua' : 'Anggota' }}</span>
        <span class="v">{{ $t->namaTampil() }}{{ $t->nipTampil() ? ' ('.$t->nipTampil().')' : '' }}{{ !$t->isDariSistem() ? ' — luar sistem' : '' }}</span>
      </div>
    @endforeach
  </div>

  <div class="review-section">
    <h4>Proposal</h4>
    <div class="review-row">
      <span class="k">Dokumen</span>
      <span class="v">
        @if ($p->proposal_path)
          <a href="{{ asset('storage/'.$p->proposal_path) }}" target="_blank" style="color:var(--green-700); font-weight:700;">{{ $p->proposal_nama_asli }}</a>
          ({{ number_format($p->proposal_size / 1024, 0) }} KB)
        @else
          Belum ada dokumen
        @endif
      </span>
    </div>
    <div class="review-row"><span class="k">Total Biaya Usulan</span><span class="v">Rp {{ number_format($p->total_biaya, 0, ',', '.') }}</span></div>
  </div>

  <div class="review-section">
    <h4>Rencana Luaran</h4>
    @forelse ($p->luaran as $l)
      <div class="review-row">
        <span class="k">{{ $l->luaranMaster->wajib ? 'Wajib' : 'Tambahan' }}</span>
        <span class="v">{{ $l->luaranMaster->nama }}{{ $l->opsi_dipilih && $l->opsi_dipilih !== '1' ? ' — '.$l->opsi_dipilih : '' }}</span>
      </div>
    @empty
      <div class="review-row"><span class="k">Luaran</span><span class="v">Belum diisi</span></div>
    @endforelse
    @if ($p->inovasi_produk)
      <div class="review-row"><span class="k">Inovasi Produk</span><span class="v">{{ $p->inovasi_produk }}</span></div>
    @endif
  </div>

  <a href="{{ route('riwayat') }}" class="btn btn-outline">← Kembali ke Riwayat</a>
</div>
@endsection
