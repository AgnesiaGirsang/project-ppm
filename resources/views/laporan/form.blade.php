@extends('layouts.app')

@section('title', $judulHalaman)
@section('crumbs', 'Menu Dosen / '.$judulHalaman)

@section('content')
<link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

<div class="card wizard-card">
  <div style="margin-bottom:16px;">
    <h3 style="margin-bottom:2px;">{{ $pengajuan->judul }}</h3>
    <div style="font-family:'JetBrains Mono', monospace; font-size:12px; color:var(--ink-500);">{{ $pengajuan->kode }}</div>
  </div>

  @if ($errors->any())
    <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
  @endif

  @if ($laporan)
    <div class="field">
      <label>Dokumen Saat Ini</label>
      <div class="file-chip">
        <span>📄 <a href="{{ asset('storage/'.$laporan->file_path) }}" target="_blank" style="color:var(--green-700); font-weight:700;">{{ $laporan->file_nama_asli }}</a> &middot; {{ number_format($laporan->file_size / 1024, 0) }} KB</span>
        @php [$label, $class] = $laporan->statusLabel(); @endphp
        <span class="badge {{ $class }}">{{ $label }}</span>
      </div>
      @if ($laporan->status === 'revisi' && $laporan->catatan_validator)
        <div class="alert-box alert-amber" style="margin-top:10px;">⚠️ <b>Catatan revisi:</b> {{ $laporan->catatan_validator }}</div>
      @endif
    </div>
  @endif

  <form method="POST" action="{{ route('laporan.store', [$tipe, $pengajuan]) }}" enctype="multipart/form-data">
    @csrf

    <div class="field">
      <label>{{ $laporan ? 'Unggah Ulang Dokumen (Revisi)' : 'Unggah Dokumen' }}</label>
      <div class="sub" style="margin-bottom:10px; font-size:11.5px; color:var(--ink-500);">Format PDF, maksimal 5 MB. @if($laporan)Dokumen lama tetap tersimpan, tidak akan dihapus.@endif</div>

      <label for="fileInput" style="display:block;">
        <div class="dropzone">
          <div class="ic">☁️</div>
          <b>Upload File {{ $judulHalaman }} (PDF, maks. 5MB)</b>
          <span>Klik di sini untuk memilih file</span>
        </div>
      </label>
      <input type="file" id="fileInput" name="file" accept="application/pdf" style="display:none;" onchange="showFileName(this)" required>
      <div class="file-chip" id="fileChip" style="display:none;"></div>
    </div>

    @if ($tipe === 'kemajuan')
      <div class="field">
        <label>Persentase Kemajuan (%)</label>
        <input type="number" name="persentase" min="0" max="100" value="{{ $laporan->persentase ?? 0 }}" required>
      </div>
    @endif

    <div style="display:flex; justify-content:space-between; margin-top:20px;">
      <a href="{{ route('laporan.index', $tipe) }}" class="btn btn-outline">Kembali</a>
      <button class="btn btn-primary" type="submit">{{ $laporan ? 'Unggah Ulang' : 'Kirim Laporan' }}</button>
    </div>
  </form>
</div>

<script>
function showFileName(input){
  const chip = document.getElementById('fileChip');
  if (input.files && input.files[0]) {
    const f = input.files[0];
    chip.style.display = 'flex';
    chip.innerHTML = `<span>📄 ${f.name} &middot; ${Math.round(f.size/1024)} KB</span><span style="color:var(--green-700); font-weight:700;">Siap diunggah</span>`;
  }
}
</script>
@endsection
