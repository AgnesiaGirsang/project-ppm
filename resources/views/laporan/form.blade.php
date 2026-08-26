@extends('layouts.app')

@section('title', $judulHalaman)
@section('crumbs', 'Menu Dosen / ' . $judulHalaman)

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    @if ($tanpaKegiatan ?? false)

        <div class="card">
            <h3 style="margin-bottom:6px;">Laporan Hasil</h3>
            <div class="empty-note">Belum ada pengajuan yang sudah berada di tahap Laporan Hasil. Laporan Hasil bisa diisi
                setelah pengajuanmu melewati tahap Proposal (dan Laporan Kemajuan, untuk jalur Simlitabkes).</div>
        </div>
    @else
        @if (session('success'))
            <div class="alert-box alert-info" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
        @endif

        <div class="grid g12">
            <div class="card">
                <h3 style="margin-bottom:2px;">{{ $judulHalaman }}</h3>
                <div class="sub">Pilih Kegiatan</div>

                <div class="field" style="margin-top:-6px;">
                    <select onchange="if(this.value) window.location = this.value">
                        @foreach ($daftarKegiatan as $k)
                            <option value="{{ route('laporan.form', ['hasil', $k]) }}"
                                {{ $k->id === $pengajuan->id ? 'selected' : '' }}>{{ $k->judul }} ({{ $k->kode }})
                            </option>
                        @endforeach
                    </select>
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
                    <div class="field"><label>Tahun</label>
                        <div>{{ $pengajuan->tahun_pengajuan }}</div>
                    </div>
                    <div class="field">
                        <label>Status Tahap</label>
                        @php
                            $statusLabel = match ($pengajuan->status) {
                                'proses' => ['Dalam Proses', 'b-menunggu'],
                                'disetujui' => ['Disetujui', 'b-disetujui'],
                                'revisi' => ['Direvisi', 'b-revisi'],
                                'selesai' => ['Selesai', 'b-selesai'],
                                default => [ucfirst($pengajuan->status), 'b-menunggu'],
                            };
                        @endphp
                        <span class="badge {{ $statusLabel[1] }}">{{ $statusLabel[0] }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('laporan.store', [$tipe, $pengajuan]) }}"
                    enctype="multipart/form-data" id="formHasil">
                    @csrf

                    <div class="field">
                        <label>Ringkasan Hasil</label>
                        <textarea name="ringkasan_hasil" rows="3" placeholder="Uraikan singkat hasil pelaksanaan kegiatan...">{{ old('ringkasan_hasil', $laporan->ringkasan_hasil ?? '') }}</textarea>
                    </div>

                    <div class="field">
                        <label>Upload Dokumen Laporan Hasil (PDF, maks. 2MB)</label>

                        @if ($laporan && $laporan->file_path)
                            <div class="file-chip" style="margin-bottom:10px;">
                                <span>📄 <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                                        style="color:var(--green-700); font-weight:700;">{{ $laporan->file_nama_asli }}</a>
                                    &middot; {{ number_format($laporan->file_size / 1024, 0) }} KB</span>
                                @php [$fl, $fc] = $laporan->statusLabel(); @endphp
                                <span class="badge {{ $fc }}">{{ $fl }}</span>
                            </div>
                        @endif

                        <label for="fileInput" style="display:block;">
                            <div class="dropzone">
                                <div class="ic">☁️</div>
                                <b>Upload File Laporan Hasil (PDF, maks. 2MB)</b>
                                <span>Klik di sini untuk memilih file</span>
                            </div>
                        </label>
                        <input type="file" id="fileInput" name="file" accept="application/pdf" style="display:none;"
                            onchange="showFileName(this)">
                        <div class="file-chip" id="fileChip" style="display:none;"></div>
                    </div>

                    <div class="field">
                        <label>Link Inovasi Produk (Google Drive)</label>
                        <input type="text" name="link_inovasi_produk" placeholder="https://drive.google.com/..."
                            value="{{ old('link_inovasi_produk', $laporan->link_inovasi_produk ?? '') }}">
                        <div class="hint" style="text-align:left;">Pastikan akses tautan public/editor agar dapat diakses
                            admin.</div>
                    </div>

                    <div class="field">
                        <label>No. SK Penelitian &amp; Pengabdian</label>
                        <input type="text" name="no_sk" placeholder="Contoh: SK.421/PNL-2026-00125/2026"
                            value="{{ old('no_sk', $laporan->no_sk ?? '') }}">
                    </div>
                </form>

                @if ($laporan && $laporan->file_path)
                    <form method="POST" action="{{ route('laporan.hasil.hapus-file', $pengajuan) }}"
                        onsubmit="return confirm('Hapus dokumen ini?')" style="margin-top:6px;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Hapus Dokumen</button>
                    </form>
                @endif
            </div>

            <div class="card">
                <h3 style="margin-bottom:2px;">Status Luaran Wajib &amp; Tambahan</h3>
                <div class="sub">Lengkapi bukti / tautan luaran yang telah tercapai.</div>

                @foreach ($luaranList as $l)
                    @php $existing = ($laporan->luaran_tercapai ?? [])[$l->id] ?? null; @endphp
                    <div class="luaran-item">
                        <div class="hd">
                            <input type="checkbox" form="formHasil" name="luaran[{{ $l->id }}][checked]"
                                value="1" {{ $existing ? 'checked' : '' }}
                                onchange="toggleLuaranLink({{ $l->id }}, this)">
                            <b>{{ $l->nama }}</b>
                            @if ($l->wajib)
                                <span class="tag-wajib">WAJIB</span>
                            @else
                                <span class="opt-tag">TAMBAHAN</span>
                            @endif
                        </div>
                        <input type="text" form="formHasil" name="luaran[{{ $l->id }}][link]"
                            id="linkLuaran{{ $l->id }}" placeholder="Link / nama file bukti luaran"
                            value="{{ $existing['link'] ?? '' }}" {{ $existing ? '' : 'disabled' }}>
                    </div>
                @endforeach

                <div class="field" style="margin-top:16px;">
                    <label>Dokumentasi Kegiatan</label>
                    @if (!empty($laporan->dokumentasi))
                        <div class="file-chip" style="margin-bottom:8px;">
                            <span>🖼️ {{ count($laporan->dokumentasi) }} file dipilih</span>
                        </div>
                    @endif
                    <input type="file" form="formHasil" name="dokumentasi[]" accept="image/*" multiple>
                </div>

                <div style="display:flex; justify-content:space-between; margin-top:20px;">
                    <button class="btn btn-outline" type="submit" form="formHasil" name="action" value="draft">Simpan
                        Draft</button>
                    <button class="btn btn-primary" type="submit" form="formHasil" name="action" value="kirim">Kirim
                        Laporan</button>
                </div>
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

            function toggleLuaranLink(id, checkbox) {
                document.getElementById('linkLuaran' + id).disabled = !checkbox.checked;
            }
        </script>

    @endif
@endsection
