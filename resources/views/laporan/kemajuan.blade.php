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
            <div style="text-align:center; color:var(--ink-500); padding:24px; font-size:13px;">Belum ada pengajuan yang
                berada di tahap Laporan Kemajuan / Laporan Hasil.</div>
        </div>
    @else
        @if ($mandiri)
            <div class="field" style="max-width:520px; margin-bottom:16px;">
                <label>Pilih Kegiatan</label>
                <select onchange="window.location.href='{{ route('laporan.kemajuan') }}?pengajuan_id=' + this.value">
                    @foreach ($daftarKegiatan as $keg)
                        <option value="{{ $keg->id }}" {{ $keg->id === $pengajuan->id ? 'selected' : '' }}>
                            {{ $keg->kode }} — {{ $keg->judul }}</option>
                    @endforeach
                </select>
            </div>

            <div class="card">
                <div class="alert-box alert-amber">🔒 <div><b>Laporan Kemajuan tidak diperlukan.</b><br>Kegiatan
                        "{{ $pengajuan->judul }}" menggunakan jalur <b>Mandiri</b>, sehingga cukup melalui 2 tahap: Proposal
                        dan Laporan Hasil — tanpa Laporan Kemajuan.</div>
                </div>
                <a href="{{ route('laporan.index', 'hasil') }}" class="btn btn-primary">Lanjut ke Laporan Hasil →</a>
            </div>
        @else
            @if ($errors->any())
                <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('laporan.kemajuan.store', $pengajuan) }}" enctype="multipart/form-data"
                id="formLaporan">
                @csrf
                <input type="hidden" name="action" id="actionInput" value="draft">

                <div class="grid g12" style="align-items:start; grid-template-columns: 1.1fr 1fr; gap:16px;">
                    <div class="card">
                        <h3>Laporan Kemajuan</h3>

                        <div class="field" style="margin-bottom:14px;">
                            <label>Pilih Kegiatan</label>
                            <select
                                onchange="window.location.href='{{ route('laporan.kemajuan') }}?pengajuan_id=' + this.value">
                                @foreach ($daftarKegiatan as $keg)
                                    <option value="{{ $keg->id }}"
                                        {{ $keg->id === $pengajuan->id ? 'selected' : '' }}>{{ $keg->kode }} —
                                        {{ $keg->judul }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field"><label>Skema</label>
                            <div>{{ $pengajuan->skema->nama ?? '-' }} &middot; <span
                                    class="badge b-disetujui">Simlitabkes</span></div>
                        </div>
                        <div class="field"><label>Tahun</label>
                            <div>{{ $pengajuan->tahun }}</div>
                        </div>
                        <div class="field">
                            <label>Status Tahap</label>
                            <div>@php [$sl, $sc] = $laporan ? $laporan->statusLabel() : ['Belum Diisi', 'b-menunggu']; @endphp<span class="badge {{ $sc }}">{{ $sl }}</span></div>
                        </div>

                        <div class="field">
                            <label>Persentase Kemajuan</label>
                            <div class="hint" style="text-align:left; margin-bottom:6px;">Dihitung otomatis dari jumlah
                                luaran yang sudah dicentang tercapai di sebelah kanan.</div>
                            <div style="height:8px; background:var(--line); border-radius:99px; overflow:hidden;">
                                <div id="progressBar"
                                    style="height:100%; background:var(--green-600); width:{{ $laporan->persentase ?? 0 }}%; transition:.2s;">
                                </div>
                            </div>
                            <div id="persentaseLabel"
                                style="margin-top:4px; font-weight:700; font-size:12.5px; color:var(--ink-700);">
                                {{ $laporan->persentase ?? 0 }}% (<span id="persentaseDetail">0 dari 0 luaran
                                    tercapai</span>)
                            </div>
                        </div>

                        <div class="field">
                            <label>Upload Dokumen Kemajuan (PDF, maks. 2MB)</label>
                            @if ($laporan && $laporan->file_path)
                                <div class="file-chip"
                                    style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                                    <span>📄 <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                                            style="color:var(--green-700); font-weight:700;">{{ $laporan->file_nama_asli }}</a>
                                        &middot; {{ number_format($laporan->file_size / 1024, 0) }} KB</span>
                                    <button type="button"
                                        onclick="hapusItem('{{ route('laporan.kemajuan.hapus-file', $pengajuan) }}')"
                                        style="border:1px solid #fca5a5; background:#fff; color:#dc2626; border-radius:6px; padding:4px 10px; font-size:12px; cursor:pointer; white-space:nowrap;">Hapus</button>
                                </div>
                            @endif
                            <input type="file" name="file" accept="application/pdf" style="margin-top:8px;">
                        </div>

                        <div class="field">
                            <label>Dokumentasi Kegiatan (foto, boleh lebih dari satu)</label>
                            @if ($laporan && !empty($laporan->dokumentasi))
                                @foreach ($laporan->dokumentasi as $i => $dok)
                                    <div class="file-chip"
                                        style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:6px;">
                                        <span>🖼 {{ $dok['nama'] ?? 'Dokumentasi ' . ($i + 1) }}</span>
                                        <button type="button"
                                            onclick="hapusItem('{{ route('laporan.kemajuan.hapus-dokumentasi', [$pengajuan, $i]) }}')"
                                            style="border:1px solid #fca5a5; background:#fff; color:#dc2626; border-radius:6px; padding:4px 10px; font-size:12px; cursor:pointer; white-space:nowrap;">Hapus</button>
                                    </div>
                                @endforeach
                            @endif
                            <input type="file" name="dokumentasi[]" accept="image/*" multiple style="margin-top:8px;">
                        </div>
                    </div>

                    <div class="card">
                        <h3>Capaian Kegiatan</h3>
                        <div class="field"><label>Kegiatan yang telah dilakukan</label>
                            <textarea name="kegiatan_dilakukan" rows="4" placeholder="1. ...&#10;2. ...">{{ old('kegiatan_dilakukan', $laporan->kegiatan_dilakukan ?? '') }}</textarea>
                        </div>
                        <div class="field"><label>Kendala</label>
                            <textarea name="kendala" rows="2">{{ old('kendala', $laporan->kendala ?? '') }}</textarea>
                        </div>
                        <div class="field" style="margin-bottom:20px;"><label>Rencana Berikutnya</label>
                            <textarea name="rencana_berikutnya" rows="2">{{ old('rencana_berikutnya', $laporan->rencana_berikutnya ?? '') }}</textarea>
                        </div>

                        <div style="border-top:1px solid var(--line); padding-top:18px;">
                            <h3>Status Luaran Wajib &amp; Tambahan</h3>
                            <div class="sub" style="font-size:11.5px; color:var(--ink-500); margin-bottom:10px;">Centang
                                luaran yang sudah tercapai pada tahap ini — persentase kemajuan di kolom kiri otomatis
                                mengikuti jumlah yang dicentang.</div>
                           @php $tercapai = $laporan->luaran_tercapai ?? $pengajuan->luaran->pluck('id')->toArray(); @endphp
                            @foreach ($pengajuan->luaran as $l)
                                <label class="luaran-item"
                                    style="cursor:pointer; display:flex; align-items:center; justify-content:space-between; gap:10px; padding:6px 0;">
                                    <span style="display:flex; align-items:center; gap:8px;">
                                        <input type="checkbox" name="luaran_tercapai[]" value="{{ $l->id }}"
                                            {{ in_array($l->id, $tercapai) ? 'checked' : '' }}
                                            onchange="updatePersentase()">
                                        {{ $l->luaranMaster->nama }}
                                    </span>
                                    <span
                                        style="display:inline-block; padding:2px 10px; border-radius:99px; font-size:10px; font-weight:700; letter-spacing:.3px; white-space:nowrap; {{ $l->luaranMaster->wajib ? 'background:#fee2e2; color:#dc2626;' : 'background:#dbeafe; color:#2563eb;' }}">{{ $l->luaranMaster->wajib ? 'WAJIB' : 'TAMBAHAN' }}</span>
                                </label>
                            @endforeach

                            <div style="display:flex; gap:10px; margin-top:14px;">
                                <button type="submit" class="btn btn-outline"
                                    onclick="document.getElementById('actionInput').value='draft'">Simpan Draft</button>
                                <button type="submit" class="btn btn-primary"
                                    onclick="document.getElementById('actionInput').value='kirim'">Kirim Laporan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <script>
                const CSRF_TOKEN = '{{ csrf_token() }}';

                function hapusItem(url) {
                    if (!confirm('Yakin ingin menghapus file ini?')) return;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = '<input type="hidden" name="_token" value="' + CSRF_TOKEN + '">';
                    document.body.appendChild(form);
                    form.submit();
                }

                function updatePersentase() {
                    const checkboxes = document.querySelectorAll('input[name="luaran_tercapai[]"]');
                    const total = checkboxes.length;
                    const checked = document.querySelectorAll('input[name="luaran_tercapai[]"]:checked').length;
                    const persen = total > 0 ? Math.round((checked / total) * 100) : 0;

                    document.getElementById('progressBar').style.width = persen + '%';
                    document.getElementById('persentaseLabel').innerHTML = persen + '% (<span id="persentaseDetail">' + checked +
                        ' dari ' + total + ' luaran tercapai</span>)';
                }

                updatePersentase();
            </script>
        @endif
    @endif
@endsection
