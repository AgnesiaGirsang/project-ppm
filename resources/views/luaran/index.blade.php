@extends('layouts.app')

@section('title', 'Luaran')
@section('crumbs', 'Menu Dosen / Luaran')

@section('content')
<div class="card">
  @if (session('success'))
    <div class="alert-box alert-info" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
  @endif

  <h3 style="margin-bottom:4px;">Luaran</h3>
  <div class="sub" style="margin-bottom:18px; font-size:12.5px; color:var(--ink-500);">Catat realisasi/bukti luaran dari pengajuan yang Laporan Hasil-nya sudah disetujui admin.</div>

  @forelse ($daftar as $p)
    <div style="border:1px solid var(--line); border-radius:12px; padding:16px; margin-bottom:14px;">
      <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:10px;">
        <div>
          <b style="font-size:13.5px;">{{ $p->judul }}</b><br>
          <span style="font-family:'JetBrains Mono', monospace; font-size:11.5px; color:var(--ink-500);">{{ $p->kode }} &middot; {{ $p->skema->nama ?? '-' }}</span>
        </div>
      </div>

      <table>
        <thead><tr><th>Luaran</th><th>Kategori</th><th>Status Realisasi</th><th>Aksi</th></tr></thead>
        <tbody>
          @foreach ($p->luaran as $l)
            <tr>
              <td>{{ $l->luaranMaster->nama }} {{ $l->luaranMaster->wajib ? '(Wajib)' : '' }}</td>
              <td>{{ $l->opsi_dipilih && $l->opsi_dipilih !== '1' ? $l->opsi_dipilih : '-' }}</td>
              <td>
                @if ($l->realisasi)
                  @php [$label, $class] = $l->realisasi->statusLabel(); @endphp
                  <span class="badge {{ $class }}">{{ $label }}</span>
                @else
                  <span class="badge b-menunggu">Belum diisi</span>
                @endif
              </td>
              <td><a href="{{ route('luaran.form', $l) }}" class="btn btn-outline" style="padding:6px 14px; font-size:12px;">{{ $l->realisasi ? 'Lihat / Edit' : 'Isi Realisasi' }}</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @empty
    <div style="text-align:center; color:var(--ink-500); padding:32px; font-size:13px;">Belum ada pengajuan yang Laporan Hasil-nya disetujui. Luaran baru bisa diisi setelah pengajuan selesai divalidasi penuh oleh admin.</div>
  @endforelse
</div>
@endsection
