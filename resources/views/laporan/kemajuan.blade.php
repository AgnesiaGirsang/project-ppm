@extends('layouts.app')

@section('title', 'Laporan Kemajuan')
@section('crumbs', 'Menu Dosen / Laporan Kemajuan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

@if (session('success'))
  <div class="alert-box alert-info" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
@endif

@if (isset($tanpaKegiatan) && $tanpaKegiatan)
  <div class="card">
    <div style="text-align:center; color:var(--ink-500); padding:24px; font-size:13px;">Belum ada pengajuan yang berada di tahap Laporan Kemajuan / Laporan Hasil.</div>
  </div>
@else

  @php
    $selectHTML = function() use ($daftarKegiatan, $pengajuan) {
      return true;
    };
  @endphp

  <form method="GET" action="{{ route('laporan.kemajuan') }}" id="formPilihKegiatan">
    <div class="field" style="max-width:520px; margin-bottom:16px;">
      <label>Pilih Kegiatan</label>
      <select name="pengajuan_id" onchange="document.getElementById('formPilihKegiatan').submit()">
        @foreach ($daftarKegiatan as $keg)
          <option value="{{ $keg->id }}" {{ $keg->id === $pengajuan->id ? 'selected' : '' }}>{{ $keg->kode }} — {{ $keg->judul }}</option>
        @endforeach
      </select>
    </div>
  </form>

  @if ($mandiri)
    <div class="card">
      <div class="alert-box alert-amber">🔒 <div><b>Laporan Kemajuan tidak diperlukan.</b><br>Kegiatan "{{ $pengajuan->judul }}" menggunakan jalur <b>Mandiri</b>, sehingga cukup melalui 2 tahap: Proposal dan Laporan Hasil — tanpa Laporan Kemajuan.</div></div>
      <a href="{{ route('laporan.index', 'hasil') }}" class="btn btn-primary">Lanjut ke Laporan Hasil →</a>
    </div>
  @else
    @if ($errors->any())
      <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('laporan.kemajuan.store', $pengajuan) }}" enctype="multipart/form-data" id="formLaporan">
      @csrf
      <input type="hidden" name="action" id="actionInput" value="draft">

      <div class="grid g12" style="align-items:start; grid-template-columns: 1.1fr 1fr; gap:16px;">
        <div class="card">
          <h3>Laporan Kemajuan</h3>

          <div class="field"><label>Skema</label><div>{{ $pengajuan->skema->nama ?? '-' }} &middot; <span class="badge b-disetujui">Simlitabkes</span></div></div>
          <div class="field"><label>Tahun</label><div>{{ $pengajuan->tahun }}</div></div>
          <div class="field">
            <label>Status Tahap</label>
            <div>@php [$sl, $sc] = $laporan ? $laporan->statusLabel() : ['Belum Diisi', 'b-menunggu']; @endphp<span class="badge {{ $sc }}">{{ $sl }}</span></div>
          </div>

          <div class="field">
            <label>Persentase Kemajuan (%)</label>
            <input type="range" name="persentase" id="persentaseRange" min="0" max="100" value="{{ old('persentase', $laporan->persentase ?? 0) }}" oninput="document.getElementById('persentaseLabel').textContent = this.value + '%'; document.getElementById('progressBar').style.width = this.value + '%';">
            <div style="height:8px; background:var(--line); border-radius:99px; margin-top:8px; overflow:hidden;">
              <div id="progressBar" style="height:100%; background:var(--green-600); width:{{ $laporan->persentase ?? 0 }}%; transition:.2s;"></div>
            </div>
            <div class="hint" id="persentaseLabel" style="margin-top:4px; font-weight:700;">{{ $laporan->persentase ?? 0 }}%</div>
          </div>

          <div class="field">
            <label>Upload Dokumen Kemajuan (PDF, maks. 2MB)</label>
            @if ($laporan && $laporan->file_path)
              <div class="file-chip">
                <span>📄 <a href="{{ asset('storage/'.$laporan->file_path) }}" target="_blank" style="color:var(--green-700); font-weight:700;">{{ $laporan->file_nama_asli }}</a> &middot; {{ number_format($laporan->file_size / 1024, 0) }} KB</span>
                <span style="color:var(--green-700); font-weight:700;">Tersimpan</span>
              </div>
            @endif
            <input type="file" name="file" accept="application/pdf" style="margin-top:8px;">
          </div>

          <div class="field">
            <label>Dokumentasi Kegiatan (foto, boleh lebih dari satu)</label>
            @if ($laporan && !empty($laporan->dokumentasi))
              <div class="file-chip"><span>🖼 {{ count($laporan->dokumentasi) }} file tersimpan</span></div>
            @endif
            <input type="file" name="dokumentasi[]" accept="image/*" multiple style="margin-top:8px;">
          </div>
        </div>

        <div>
          <div class="card" style="margin-bottom:16px;">
            <h3>Capaian Kegiatan</h3>
            <div class="field"><label>Kegiatan yang telah dilakukan</label><textarea name="kegiatan_dilakukan" rows="4" placeholder="1. ...&#10;2. ...">{{ old('kegiatan_dilakukan', $laporan->kegiatan_dilakukan ?? '') }}</textarea></div>
            <div class="field"><label>Kendala</label><textarea name="kendala" rows="2">{{ old('kendala', $laporan->kendala ?? '') }}</textarea></div>
            <div class="field"><label>Rencana Berikutnya</label><textarea name="rencana_berikutnya" rows="2">{{ old('rencana_berikutnya', $laporan->rencana_berikutnya ?? '') }}</textarea></div>
          </div>

          <div class="card">
            <h3>Status Luaran Wajib &amp; Tambahan</h3>
            <div class="sub" style="font-size:11.5px; color:var(--ink-500); margin-bottom:10px;">Centang luaran yang sudah tercapai pada tahap ini.</div>
            @php $tercapai = $laporan->luaran_tercapai ?? []; @endphp
            @foreach ($pengajuan->luaran as $l)
              <label class="luaran-item" style="cursor:pointer;">
                <span style="display:flex; align-items:center; gap:8px;">
                  <input type="checkbox" name="luaran_tercapai[]" value="{{ $l->id }}" {{ in_array($l->id, $tercapai) ? 'checked' : '' }}>
                  {{ $l->luaranMaster->nama }}
                </span>
                <span class="tag-wajib" style="{{ $l->luaranMaster->wajib ? '' : 'background:var(--line); color:var(--ink-500);' }}">{{ $l->luaranMaster->wajib ? 'WAJIB' : 'TAMBAHAN' }}</span>
              </label>
            @endforeach

            <div style="display:flex; gap:10px; margin-top:14px;">
              <button type="submit" class="btn btn-outline" onclick="document.getElementById('actionInput').value='draft'">Simpan Draft</button>
              <button type="submit" class="btn btn-primary" onclick="document.getElementById('actionInput').value='kirim'">Kirim Laporan</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  @endif
@endif
@endsection
