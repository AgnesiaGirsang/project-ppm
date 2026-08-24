@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

@section('content')
<link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

<div class="card wizard-card">
  @include('pengajuan._stepper', ['current' => 3])

  @if ($errors->any())
    <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('pengajuan.step3.post') }}" enctype="multipart/form-data">
    @csrf

    <div class="field">
      <label>Dokumen Proposal</label>
      <div class="sub" style="margin-bottom:10px; font-size:11.5px; color:var(--ink-500);">Unggah proposal dalam format PDF, ukuran maksimal 2 MB.</div>

      <label for="proposalInput" style="display:block;">
        <div class="dropzone">
          <div class="ic">☁️</div>
          <b>Upload File Proposal (PDF, maks. 2MB)</b>
          <span>Klik di sini untuk memilih file</span>
        </div>
      </label>
      <input type="file" id="proposalInput" name="proposal" accept="application/pdf" style="display:none;" onchange="showFileName(this)">

      @if ($w['proposal_path'])
        <div class="file-chip" id="fileChip">
          <span>📄 {{ $w['proposal_nama_asli'] }} &middot; {{ number_format($w['proposal_size'] / 1024, 0) }} KB</span>
          <span style="color:var(--green-700); font-weight:700;">Tersimpan</span>
        </div>
      @else
        <div class="file-chip" id="fileChip" style="display:none;"></div>
      @endif

      <div class="alert-box alert-amber" style="margin-top:14px;">⚠️ Jika proposal diminta revisi oleh admin, dokumen lama <b>tidak akan dihapus</b> dan tetap tersimpan. Anda cukup mengunggah dokumen proposal versi terbaru sebagai tambahan.</div>
    </div>

    <div class="field">
      <label>Total Biaya Usulan (Rp)</label>
      <input type="number" name="total_biaya" placeholder="10000000" value="{{ $w['total_biaya'] }}" min="0" step="1000" required>
    </div>

    <div style="display:flex; justify-content:space-between; margin-top:20px;">
      <a href="{{ route('pengajuan.step2') }}" class="btn btn-outline">Kembali</a>
      <button class="btn btn-primary" type="submit">Selanjutnya</button>
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
