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

        /* Kotak centang status luaran — dibuat custom (bukan checkbox native) supaya warnanya konsisten hijau, tidak abu-abu walau readonly */
        .luaran-item input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        .check-visual {
            display: inline-block;
            width: 18px;
            height: 18px;
            min-width: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 5px;
            background: #fff;
            position: relative;
            vertical-align: middle;
            margin-right: 6px;
            transition: background .15s ease, border-color .15s ease;
        }

        .check-visual.is-checked {
            background: #00875A;
            border-color: #00875A;
        }

        .check-visual.is-checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 1px;
            width: 5px;
            height: 9px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .luaran-lain-row {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .luaran-lain-row select,
        .luaran-lain-row input[type="text"] {
            flex: 1;
        }

        .luaran-lain-empty {
            font-size: 12px;
            color: var(--ink-500);
            margin-bottom: 8px;
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
                    <div>{{ $pengajuan->tahun_pelaksanaan }}</div>
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
                        <span class="check-visual {{ $existing && !empty($existing['link']) ? 'is-checked' : '' }}"
                            id="checkVisual{{ $pl->id }}"></span>
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
                        oninput="const isFilled = this.value.trim().length > 0; document.getElementById('chk{{ $pl->id }}').checked = isFilled; document.getElementById('checkVisual{{ $pl->id }}').classList.toggle('is-checked', isFilled); updateKemajuanLuaran();">
                </div>
            @empty
                <div class="sub">Tidak ada luaran yang direncanakan pada pengajuan ini.</div>
            @endforelse

            @php
                $totalLuaran = count($luaranList);
            @endphp
            <div class="field" style="margin-top:14px;">
                <label>Kemajuan Luaran</label>
                <div style="background:#f1f5f9; border-radius:8px; height:8px; overflow:hidden;">
                    <div id="progressBarLuaran" style="width:0%; background:#00875A; height:100%; transition:width 0.2s;">
                    </div>
                </div>
                <div class="sub" style="margin-top:4px;" id="progressTextLuaran">0 dari {{ $totalLuaran }} luaran
                    terpenuhi (0%)</div>
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

            {{-- ===================== Luaran Lainnya ===================== --}}
            <div class="field" style="margin-top:16px; padding-top:16px; border-top:1px dashed #e2e8f0;">
                <label>Luaran Lainnya <span class="tag-opsional">OPSIONAL</span></label>
                <div class="sub" style="margin-bottom:10px;">
                    Luaran yang tercapai di lapangan tapi tidak direncanakan/dipilih saat pengajuan proposal bisa
                    ditambahkan di sini. Pilih judul luaran dari daftar, lalu isi tautan buktinya.
                </div>

                <div id="luaranLainContainer"></div>
                <div class="luaran-lain-empty" id="luaranLainEmptyMsg" style="display:none;">Belum ada luaran lain yang
                    ditambahkan.</div>

                @unless ($readonly ?? false)
                    @if ($luaranMasterLain->isEmpty())
                        <div class="sub">Semua luaran yang tersedia sudah dipilih saat pengajuan proposal.</div>
                    @else
                        <button type="button" class="btn btn-outline btn-sm" onclick="tambahLuaranLain()">+ Tambah
                            Luaran</button>
                    @endif
                @endunless
            </div>

            @unless ($readonly ?? false)
                <div style="display:flex; justify-content:space-between; margin-top:20px;">
                    <button class="btn btn-primary" type="submit" form="formHasil" name="action" value="kirim">Kirim
                        Laporan</button>
                </div>
            @endunless
        </div>
    </div>

    {{-- Template baris "Luaran Lainnya" — di-clone lewat JS setiap kali baris baru ditambahkan --}}
    <template id="templateLuaranLain">
        <div class="luaran-lain-row">
            <select form="formHasil" name="luaran_lain[__INDEX__][luaran_master_id]"
                {{ $readonly ?? false ? 'disabled' : '' }}>
                <option value="">Pilih judul luaran...</option>
                @foreach ($luaranMasterLain as $lm)
                    <option value="{{ $lm->id }}">{{ $lm->nama }}</option>
                @endforeach
            </select>
            <input type="text" form="formHasil" name="luaran_lain[__INDEX__][link]"
                placeholder="Link / nama file bukti luaran" {{ $readonly ?? false ? 'disabled' : '' }}>
            @unless ($readonly ?? false)
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.luaran-lain-row').remove()">
                    Hapus</button>
            @endunless
        </div>
    </template>

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

        function updateKemajuanLuaran() {
            const checkboxes = document.querySelectorAll('.luaran-item input[type="checkbox"]');
            const total = checkboxes.length;
            let tercapai = 0;
            checkboxes.forEach(chk => {
                if (chk.checked) tercapai++;
            });

            const persen = total > 0 ? Math.round((tercapai / total) * 100) : 0;

            document.getElementById('progressBarLuaran').style.width = persen + '%';
            document.getElementById('progressTextLuaran').textContent =
                tercapai + ' dari ' + total + ' luaran terpenuhi (' + persen + '%)';
        }

        // Hitung sekali saat halaman dimuat, supaya kalau ada link yang sudah terisi
        // sebelumnya (dari draft/reload), progress bar langsung akurat tanpa perlu diketik ulang.
        document.addEventListener('DOMContentLoaded', updateKemajuanLuaran);

        // ===== Luaran Lainnya =====
        let luaranLainIndex = 0;

        function tambahLuaranLain(selectedId = '', linkVal = '') {
            const tplHtml = document.getElementById('templateLuaranLain').innerHTML.replaceAll('__INDEX__',
                luaranLainIndex);
            const wrapper = document.createElement('div');
            wrapper.innerHTML = tplHtml.trim();
            const row = wrapper.firstElementChild;

            if (selectedId) row.querySelector('select').value = selectedId;
            if (linkVal) row.querySelector('input[type="text"]').value = linkVal;

            document.getElementById('luaranLainContainer').appendChild(row);
            luaranLainIndex++;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const existing = @json($laporan->luaran_tambahan_lain ?? []);
            existing.forEach(item => tambahLuaranLain(item.luaran_master_id, item.link));
        });
    </script>
@endsection
