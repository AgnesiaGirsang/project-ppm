@extends('layouts.app')

@section('title', 'Riwayat Pengajuan')
@section('crumbs', 'Menu Dosen / Riwayat Pengajuan')

@section('content')
<div class="card">
  <div style="display:flex; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
    @php
      $tabs = ['semua' => 'Semua', 'proses' => 'Dalam Proses', 'disetujui' => 'Disetujui', 'revisi' => 'Direvisi'];
    @endphp
    @foreach ($tabs as $key => $label)
      <a href="{{ route('riwayat', array_filter(['status' => $key, 'jenis' => $filterJenis, 'jalur' => $filterJalur, 'q' => $q])) }}"
         class="btn {{ $filterStatus === $key ? 'btn-primary' : 'btn-outline' }}" style="padding:8px 16px; font-size:12.5px;">
        {{ $label }} ({{ $counts[$key] }})
      </a>
    @endforeach
  </div>

  <form method="GET" action="{{ route('riwayat') }}" style="display:flex; gap:10px; margin-bottom:18px; flex-wrap:wrap;">
    <input type="hidden" name="status" value="{{ $filterStatus }}">
    <input type="text" name="q" placeholder="Cari judul / kode pengajuan..." value="{{ $q }}" style="flex:1; min-width:200px;">
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

  <table>
    <thead>
      <tr><th>Kode</th><th>Judul</th><th>Jenis</th><th>Skema</th><th>Jalur</th><th>Tahap</th><th>Tgl Pengajuan</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse ($daftar as $p)
        @php [$label, $class] = $p->statusLabel(); @endphp
        <tr class="clickable" onclick="window.location='{{ route('pengajuan.detail', $p) }}'">
          <td style="font-family:'JetBrains Mono', monospace; font-size:12px;">{{ $p->kode }}</td>
          <td>{{ $p->judul }}</td>
          <td>{{ ucfirst($p->jenis) }}</td>
          <td>{{ $p->skema->nama ?? '-' }}</td>
          <td><span class="badge {{ $p->jalur === 'mandiri' ? 'b-revisi' : 'b-disetujui' }}">{{ $p->jalur === 'mandiri' ? 'Mandiri' : 'Simlitabkes' }}</span></td>
          <td>{{ ucwords(str_replace('_', ' ', $p->tahap)) }}</td>
          <td>{{ $p->created_at->format('d/m/Y') }}</td>
          <td><span class="badge {{ $class }}">{{ $label }}</span></td>
          <td><a href="{{ route('pengajuan.detail', $p) }}" class="icon-btn" onclick="event.stopPropagation();">👁</a></td>
        </tr>
      @empty
        <tr><td colspan="9" style="text-align:center; color:var(--ink-500); padding:24px;">Belum ada pengajuan yang cocok.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top:16px;">{{ $daftar->links() }}</div>
</div>
@endsection
