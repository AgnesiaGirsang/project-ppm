@extends('layouts.app')

@section('title', $judulHalaman)
@section('crumbs', 'Menu Dosen / ' . $judulHalaman)

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        .tag-opsional {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            color: #1d4ed8;
            background: #dbeafe;
            padding: 2px 8px;
            border-radius: 999px;
            margin-left: 6px;
            vertical-align: middle;
        }

        .tag-wajib-field {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            color: #dc2626;
            background: #fee2e2;
            padding: 2px 8px;
            border-radius: 999px;
            margin-left: 6px;
            vertical-align: middle;
        }
    </style>

    <div style="margin-bottom:14px;">
        <a href="{{ route('laporan.index', $tipe) }}" class="btn btn-outline" style="font-size:12.5px;">&larr; Kembali ke
            Daftar Kegiatan</a>
    </div>

    @if (session('success'))
        <div class="alert-box alert-info" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
    @endif

    @if ($readonly ?? false)
        <div class="alert-box alert-info" style="margin-bottom:16px;">
            🔒 Laporan ini sudah dikirim dan sedang/sudah divalidasi admin. Hubungi admin bila perlu perubahan.
        </div>
    @endif

    <div class="grid g12">
        <div class="card">
            <h3 style="margin-bottom:2px;">{{ $judulHalaman }}</h3>
            <div class="sub" style="font-size:11.5px; color:var(--ink-500); margin-bottom:14px;">
                {{ $pengajuan->kode }} — {{ $pengajuan->judul }}
            </div>

            <div class="field">
                <label>Skema</label>
                <div style="display:flex; align-items:center; gap:8px;">
                    <b>{{ $pengajuan->skema->nama ?? '-' }}</b>
                    <span
                        class="badge {{ $pengajuan->jalur === 'mandiri' ? 'b-selesai' : 'b-disetujui' }}">{{ ucfirst($pengajuan->jalur) }}</span>
                </div>
            </div>

            <div class="grid g2">
                <div class="field"><label>Tahun Pelaksanaan</label>
                    <div>{{ $pengajuan->tahun }}</div>
                </div>
                <div class="field">
                    <label>Status Laporan Hasil</label>
                    @if ($laporan)
                        @php [$statusLabel, $statusClass] = $laporan->statusLabel(); @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    @else
                        <span class="badge b-menunggu">Belum Diajukan</span>
                    @endif
                </div>
            </div>

            @if ($laporan && $laporan->status === 'revisi' && $laporan->catatan_validator)
                <div class="alert-box alert-amber">⚠️ <b>Catatan revisi dari
                        admin:</b><br>{{ $laporan->catatan_validator }}</div>
            @endif

            <form method="POST" action="{{ route('laporan.store', [$tipe, $pengajuan]) }}" enctype="multipart/form-data"
                id="formHasil">
                @csrf

                <div class="field">
                    <label>Ringkasan Hasil <span class="tag-opsional">OPSIONAL</span></label>
                    <textarea name="ringkasan_hasil" rows="3" placeholder="Uraikan singkat hasil pelaksanaan kegiatan..."
                        {{ $readonly ?? false ? 'disabled' : '' }}>{{ old('ringkasan_hasil', $laporan->ringkasan_hasil ?? '') }}</textarea>
                </div>

                <div class="field">
                    <label>Upload Dokumen Laporan Hasil (PDF, maks. 2MB) <span class="tag-wajib-field">WAJIB
                            DIISI</span></label>

                    @if ($laporan && $laporan->file_path)
                        <div class="file-chip" style="margin-bottom:10px;">
                            <span>📄 <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                                    style="color:var(--green-700); font-weight:700;">{{ $laporan->file_nama_asli }}</a>
                                &middot; {{ number_format($laporan->file_size / 1024, 0) }} KB</span>
                            @php [$fl, $fc] = $laporan->statusLabel(); @endphp
                            <span class="badge {{ $fc }}">{{ $fl }}</span>
                        </div>
                    @endif

                    @unless ($readonly ?? false)
                        <label for="fileInput" style="display:block;">
                            <div class="dropzone">
                                <div class="ic">☁️</div>
                                <b>Upload File Laporan Hasil (PDF, maks. 2MB)</b>
                                <span>Klik di sini untuk memilih file</span>
                            </div>
                        </label>
                        <input type="file" id="fileInput" name="file" accept="application/pdf" style="display:none;"
                            {{ $laporan && $laporan->file_path ? '' : 'required' }} onchange="showFileName(this)">
                        <div class="file-chip" id="fileChip" style="display:none;"></div>
                    @endunless
                </div>

                <div class="field">
                    <label>Link Inovasi Produk (Google Drive) <span class="tag-wajib-field">WAJIB DIISI</span></label>
                    <input type="text" name="link_inovasi_produk" placeholder="https://drive.google.com/..."
                        value="{{ old('link_inovasi_produk', $laporan->link_inovasi_produk ?? '') }}"
                        {{ $readonly ?? false ? 'disabled' : '' }} required>
                    <div class="hint" style="text-align:left;">Pastikan akses tautan public/editor agar dapat diakses
                        admin.</div>
                </div>

                <div class="field">
                    <label>No. SK Penelitian &amp; Pengabdian <span class="tag-wajib-field">WAJIB DIISI</span></label>
                    <input type="text" name="no_sk" placeholder="Contoh: SK.421/PNL-2026-00125/2026"
                        value="{{ old('no_sk', $laporan->no_sk ?? '') }}" {{ $readonly ?? false ? 'disabled' : '' }}
                        required>
                </div>
            </form>

            @if ($laporan && $laporan->file_path && !($readonly ?? false))
                <form method="POST" action="{{ route('laporan.hasil.hapus-file', $pengajuan) }}"
                    onsubmit="return confirm('Hapus dokumen ini?')" style="margin-top:6px;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Hapus Dokumen</button>
                </form>
            @endif
        </div>

        <div class="card">
            <h3 style="margin-bottom:2px;">Status Luaran Wajib &amp; Tambahan</h3>
            <div class="sub">Luaran berikut diambil dari rencana luaran saat pengajuan proposal. Isi tautan bukti untuk
                setiap luaran yang telah tercapai — status tercapai/belum tercapai mengikuti otomatis.</div>

            @forelse ($luaranList as $pl)
                @php $existing = ($laporan->luaran_tercapai ?? [])[$pl->id] ?? null; @endphp
                <div class="luaran-item">
                    <div class="hd">
                        <input type="checkbox" id="chk{{ $pl->id }}" disabled
                            {{ $existing && !empty($existing['link']) ? 'checked' : '' }}>
                        <b>{{ $pl->luaranMaster->nama ?? '-' }}</b>
                        @if ($pl->is_wajib)
                            <span class="tag-wajib">WAJIB</span>
                        @else
                            <span class="opt-tag">TAMBAHAN</span>
                        @endif
                    </div>
                    <input type="text" form="formHasil" name="luaran[{{ $pl->id }}][link]"
                        id="linkLuaran{{ $pl->id }}" placeholder="Link / nama file bukti luaran"
                        value="{{ $existing['link'] ?? '' }}" {{ $readonly ?? false ? 'disabled' : '' }}
                        oninput="document.getElementById('chk{{ $pl->id }}').checked = this.value.trim().length > 0">
                </div>
            @empty
                <div class="sub">Tidak ada luaran yang direncanakan pada pengajuan ini.</div>
            @endforelse

            @php
                $totalLuaran = count($luaranList);
                $totalTercapai = count($laporan->luaran_tercapai ?? []);
                $persen = $totalLuaran > 0 ? round(($totalTercapai / $totalLuaran) * 100) : 0;
            @endphp
            <div class="field" style="margin-top:14px;">
                <label>Kemajuan Luaran</label>
                <div style="background:#f1f5f9; border-radius:8px; height:8px; overflow:hidden;">
                    <div style="width:{{ $persen }}%; background:#00875A; height:100%;"></div>
                </div>
                <div class="sub" style="margin-top:4px;">{{ $totalTercapai }} dari {{ $totalLuaran }} luaran terpenuhi
                    ({{ $persen }}%)</div>
            </div>

            <div class="field" style="margin-top:16px;">
                <label>Dokumentasi Kegiatan <span class="tag-opsional">OPSIONAL</span></label>
                @if (!empty($laporan->dokumentasi))
                    <div class="file-chip" style="margin-bottom:8px;">
                        <span>🖼️ {{ count($laporan->dokumentasi) }} file dipilih</span>
                    </div>
                @endif
                @unless ($readonly ?? false)
                    <input type="file" form="formHasil" name="dokumentasi[]" accept="image/*" multiple>
                @endunless
            </div>

            @unless ($readonly ?? false)
                <div style="display:flex; justify-content:space-between; margin-top:20px;">
                    <button class="btn btn-outline" type="submit" form="formHasil" name="action" value="draft"
                        formnovalidate>Simpan Draft</button>
                    <button class="btn btn-primary" type="submit" form="formHasil" name="action" value="kirim">Kirim
                        Laporan</button>
                </div>
            @endunless
        </div>
    </div>

    <script>
        function showFileName(input) {
            const chip = document.getElementById('fileChip');
            if (input.files && input.files[0]) {
                const f = input.files[0];
                chip.style.display = 'flex';
                chip.innerHTML =
                    `<span>📄 ${f.name} &middot; ${Math.round(f.size/1024)} KB</span><span style="color:var(--green-700); font-weight:700;">Siap diunggah</span>`;
            }
        }
    </script>
@endsection
