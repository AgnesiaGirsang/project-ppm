@extends('layouts.app')

@section('title', 'Dashboard')
@section('crumbs', 'Dashboard')

@section('content')

<div class="grid g4" style="margin-bottom:16px;">
  <div class="card stat blue">
    <div class="top"><span class="label">Total Pengajuan</span><div class="ic">&#9638;</div></div>
    <div class="num">{{ $stats['total'] }}</div>
  </div>
  <div class="card stat amber">
    <div class="top"><span class="label">Dalam Proses</span><div class="ic">&#9711;</div></div>
    <div class="num">{{ $stats['proses'] }}</div>
  </div>
  <div class="card stat green">
    <div class="top"><span class="label">Disetujui</span><div class="ic">&#10003;</div></div>
    <div class="num">{{ $stats['disetujui'] }}</div>
  </div>
  <div class="card stat red">
    <div class="top"><span class="label">Perlu Revisi</span><div class="ic">&#8630;</div></div>
    <div class="num">{{ $stats['revisi'] }}</div>
  </div>
</div>

<div class="grid g3">
  <div class="card">
    <h3>Pengajuan Terbaru</h3>
    <table>
      <thead><tr><th>Judul</th><th>Jenis</th><th>Status</th></tr></thead>
      <tbody>
        @foreach ($riwayatTerbaru as $r)
          @php
            $badgeMap = [
              'proses' => ['Dalam Proses', 'b-menunggu'],
              'disetujui' => ['Disetujui', 'b-disetujui'],
              'revisi' => ['Direvisi', 'b-revisi'],
            ];
            [$label, $class] = $badgeMap[$r['status']];
          @endphp
          <tr class="clickable">
            <td>{{ $r['judul'] }}</td>
            <td>{{ $r['jenis'] }}</td>
            <td><span class="badge {{ $class }}">{{ $label }}</span></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="card">
    <h3>Status Proses Pengajuan</h3>
    <ul class="timeline">
      <li><b>Pengajuan</b><div class="t">Terkirim</div></li>
      <li><b>Validasi Admin</b><div class="t">Sedang diproses</div></li>
      <li class="muted"><b>Pelaksanaan Kegiatan</b></li>
      <li class="muted"><b>Laporan Kemajuan</b></li>
      <li class="muted"><b>Laporan Hasil</b></li>
      <li class="muted"><b>Luaran</b></li>
    </ul>
  </div>

  <div class="card">
    <h3>Pengumuman</h3>
    <ul class="timeline">
      <li><b>Pengajuan "Sistem Informasi Klinik" diterima</b><div class="t">16 Mei 2026 &middot; 10:30</div></li>
      <li><b>Laporan kemajuan "AI untuk Kesehatan" sudah divalidasi</b><div class="t">15 Mei 2026 &middot; 09:48</div></li>
      <li class="muted"><b>Laporan hasil "Pemberdayaan UMKM" disetujui</b><div class="t">10 Mei 2026 &middot; 13:20</div></li>
    </ul>
  </div>
</div>

<div class="card" style="margin-top:16px;">
  <h3>Shortcut</h3>
  <div class="grid g4">
    <button class="btn btn-outline" style="flex-direction:column; padding:18px; height:auto;">&#9998; Buat Pengajuan Baru</button>
    <button class="btn btn-outline" style="flex-direction:column; padding:18px; height:auto;">&#9636; Upload Laporan Kemajuan</button>
    <button class="btn btn-outline" style="flex-direction:column; padding:18px; height:auto;">&#9636; Upload Laporan Hasil</button>
    <button class="btn btn-outline" style="flex-direction:column; padding:18px; height:auto;">&#9733; Input Luaran</button>
  </div>
</div>
@endsection
