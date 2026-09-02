@extends('layouts.app')

@section('title', 'Revisi Pengajuan')
@section('crumbs', 'Menu Dosen / Riwayat Pengajuan / Revisi')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        /* ====== MultiSelect Anggota Tim (box chip + dropdown checklist) ====== */
        .ms {
            position: relative;
        }

        .ms-box {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            min-height: 44px;
            padding: 6px 34px 6px 8px;
            border: 1px solid #d8dee3;
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            position: relative;
        }

        .ms-box:hover {
            border-color: #00875A;
        }

        .ms.open .ms-box {
            border-color: #00875A;
            box-shadow: 0 0 0 3px rgba(0, 135, 90, 0.15);
        }

        .ms-placeholder {
            color: #9ca3af;
            font-size: 13px;
            padding: 4px 4px;
        }

        .ms-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #00875A;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 6px 5px 10px;
            border-radius: 999px;
            max-width: 200px;
        }

        .ms-chip span.txt {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ms-chip .x {
            flex-shrink: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            line-height: 1;
            cursor: pointer;
        }

        .ms-chip .x:hover {
            background: rgba(255, 255, 255, 0.45);
        }

        .ms-arrow {
            position: absolute;
            top: 50%;
            right: 12px;
            width: 9px;
            height: 9px;
            border-right: 2px solid #6b7280;
            border-bottom: 2px solid #6b7280;
            transform: translateY(-70%) rotate(45deg);
            transition: transform .15s ease;
            pointer-events: none;
        }

        .ms.open .ms-arrow {
            transform: translateY(-30%) rotate(-135deg);
        }

        .ms-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            max-height: 280px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 12px 28px rgba(16, 24, 40, 0.12);
            z-index: 40;
            padding: 6px 0;
        }

        .ms.open .ms-dropdown {
            display: block;
        }

        .ms-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            cursor: pointer;
            font-size: 13px;
        }

        .ms-option:hover {
            background: #f8fafc;
        }

        .ms-option input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #00875A;
            flex-shrink: 0;
        }

        .ms-option .meta {
            color: #9ca3af;
            font-size: 11px;
        }

        .ms-select-all {
            font-weight: 700;
            border-bottom: 1px solid #f1f3f5;
            padding-bottom: 10px;
            margin-bottom: 4px;
        }

        .ms-option-empty {
            padding: 14px;
            text-align: center;
            color: #9ca3af;
            font-size: 12.5px;
        }
    </style>

    <div class="card wizard-card">
        <div style="margin-bottom:16px;">
            <h3 style="margin-bottom:2px;">Revisi Pengajuan</h3>
            <div style="font-family:'JetBrains Mono', monospace; font-size:12px; color:var(--ink-500);">
                {{ $pengajuan->kode }}</div>
        </div>

        @if ($pengajuan->catatan_validator)
            <div class="alert-box alert-amber">⚠️ <b>Catatan revisi dari admin:</b><br>{{ $pengajuan->catatan_validator }}
            </div>
        @endif

        @if ($errors->any())
            <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('pengajuan.update', $pengajuan) }}" enctype="multipart/form-data"
            id="formRevisi">
            @csrf
            @method('PUT')

            <div class="field">
                <label>Judul Kegiatan</label>
                <input type="text" name="judul" value="{{ old('judul', $pengajuan->judul) }}" required>
            </div>

            <div class="field">
                <label>Skema</label>
                <div style="padding:9px 0; color:var(--ink-500); font-size:13px;">{{ $pengajuan->skema->nama ?? '-' }} <span
                        style="font-size:11px;">(tidak bisa diubah saat revisi)</span></div>
            </div>

            <div class="field">
                <label>Ketua Pengaju</label>
                <div class="check-list">
                    <label style="cursor:default;">
                        <div class="av">{{ $ketua->initials() }}</div>
                        <div><b>{{ $ketua->nama }}</b><br><span style="color:var(--ink-500); font-size:11px;">NIP
                                {{ $ketua->nip }} &middot; Ketua</span></div>
                    </label>
                </div>
            </div>

            <div class="field">
                <label>Anggota Tim dari Sistem</label>

                <div class="ms" id="anggotaMs">
                    <div class="ms-box" id="anggotaMsBox">
                        <div class="ms-arrow"></div>
                    </div>
                    <div class="ms-dropdown" id="anggotaMsDropdown"></div>
                </div>

                <div id="anggotaHiddenInputs"></div>
            </div>

            <div class="field">
                <label>Anggota di Luar Sistem</label>
                <div id="timLuarWrap">
                    @forelse ($timLuarExisting as $luar)
                        <div class="grid g2" style="margin-bottom:8px;" data-row>
                            <input type="text" name="tim_luar_nama[]" placeholder="Nama lengkap"
                                value="{{ $luar['nama'] }}">
                            <div style="display:flex; gap:8px;">
                                <input type="text" name="tim_luar_instansi[]" placeholder="Asal institusi (opsional)"
                                    value="{{ $luar['instansi'] }}">
                                <button type="button" class="btn btn-outline" onclick="this.closest('[data-row]').remove()"
                                    style="flex-shrink:0; padding:0 14px;">✕</button>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
                <button type="button" class="btn btn-outline" onclick="tambahBarisLuar()">+ Tambah Anggota Luar</button>
            </div>

            <div class="field" style="margin-top:16px;">
                <label>Dokumen Proposal Saat Ini</label>
                <div class="file-chip">
                    <span>📄 <a href="{{ asset('storage/' . $pengajuan->proposal_path) }}" target="_blank"
                            style="color:var(--green-700); font-weight:700;">{{ $pengajuan->proposal_nama_asli }}</a>
                        &middot; {{ number_format($pengajuan->proposal_size / 1024, 0) }} KB</span>
                </div>
                <div class="sub" style="margin:8px 0; font-size:11.5px; color:var(--ink-500);">Unggah dokumen baru kalau
                    proposal perlu diganti (opsional). Dokumen lama tetap tersimpan, tidak akan dihapus.</div>
                <input type="file" name="proposal" accept="application/pdf">
            </div>

            <div class="field">
                <label>Total Biaya Usulan (Rp)</label>
                <input type="text" id="total_biaya_display" placeholder="10.000.000" autocomplete="off"
                    inputmode="numeric">
                <input type="hidden" name="total_biaya" id="total_biaya"
                    value="{{ old('total_biaya', $pengajuan->total_biaya) }}">
            </div>

            <div class="alert-box alert-info">ℹ️ Setelah dikirim ulang, status pengajuan kembali menjadi <b>Dalam Proses</b>
                dan akan divalidasi ulang oleh admin.</div>

            <div style="display:flex; justify-content:space-between; margin-top:20px;">
                <a href="{{ route('pengajuan.detail', $pengajuan) }}" class="btn btn-outline">Batal</a>
                <button class="btn btn-primary" type="submit">Kirim Ulang Revisi</button>
            </div>
        </form>
    </div>

    <script>
        @php
            $anggotaListArr = $anggotaTersedia
                ->map(function ($a) {
                    return [
                        'id' => $a->id,
                        'nama' => $a->nama,
                        'nip' => $a->nip,
                        'jurusan' => $a->jurusan,
                    ];
                })
                ->values();
        @endphp
        const ANGGOTA_LIST = @json($anggotaListArr);
        const TIM_TERPILIH_AWAL = @json(array_values($timTerpilih));

        let selectedIds = [...TIM_TERPILIH_AWAL];

        function getInitials(name) {
            if (!name) return '?';
            const parts = name.replace(/^(Dr\.|Prof\.|drg\.)\s*/i, '').trim().split(/\s+/);
            const letters = (parts[0]?.[0] || '') + (parts.length > 1 ? (parts[1][0] || '') : '');
            return letters.toUpperCase() || '?';
        }

        function renderAnggotaMs() {
            const box = document.getElementById('anggotaMsBox');
            const dropdown = document.getElementById('anggotaMsDropdown');
            const hiddenWrap = document.getElementById('anggotaHiddenInputs');

            // Chip di dalam box
            let chipsHtml = '';
            if (selectedIds.length === 0) {
                chipsHtml = '<span class="ms-placeholder">Pilih anggota dari sistem...</span>';
            } else {
                chipsHtml = selectedIds.map(id => {
                    const a = ANGGOTA_LIST.find(x => x.id == id);
                    if (!a) return '';
                    return `<span class="ms-chip"><span class="txt">${a.nama}</span><span class="x" data-remove-id="${a.id}">✕</span></span>`;
                }).join('');
            }
            box.innerHTML = chipsHtml + '<div class="ms-arrow"></div>';

            // Hidden input untuk submit form
            hiddenWrap.innerHTML = selectedIds.map(id => `<input type="hidden" name="tim[]" value="${id}">`).join('');

            // Dropdown checklist
            const allChecked = ANGGOTA_LIST.length > 0 && selectedIds.length === ANGGOTA_LIST.length;
            let optsHtml = `
      <label class="ms-option ms-select-all">
        <input type="checkbox" id="anggotaSelectAll" ${allChecked ? 'checked' : ''}>
        Select All
      </label>`;

            if (ANGGOTA_LIST.length === 0) {
                optsHtml += '<div class="ms-option-empty">Belum ada dosen lain yang terdaftar di sistem.</div>';
            } else {
                ANGGOTA_LIST.forEach(a => {
                    const checked = selectedIds.includes(a.id);
                    optsHtml += `
        <label class="ms-option">
          <input type="checkbox" data-id="${a.id}" ${checked ? 'checked' : ''}>
          <span>
            <b>${a.nama}</b><br>
            <span class="meta">NIP ${a.nip}${a.jurusan ? ' &middot; ' + a.jurusan : ''}</span>
          </span>
        </label>`;
                });
            }
            dropdown.innerHTML = optsHtml;
        }

        function toggleAnggota(id, checked) {
            id = parseInt(id);
            if (checked) {
                if (!selectedIds.includes(id)) selectedIds.push(id);
            } else {
                selectedIds = selectedIds.filter(x => x !== id);
            }
            renderAnggotaMs();
        }

        const msEl = document.getElementById('anggotaMs');
        const msBoxEl = document.getElementById('anggotaMsBox');
        const msDropdownEl = document.getElementById('anggotaMsDropdown');

        msBoxEl.addEventListener('click', function() {
            msEl.classList.toggle('open');
        });

        // Event delegation: klik x pada chip, checkbox individual, dan select all
        msEl.addEventListener('click', function(e) {
            const removeId = e.target.getAttribute('data-remove-id');
            if (removeId) {
                e.stopPropagation();
                toggleAnggota(removeId, false);
            }
        });

        msDropdownEl.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        msDropdownEl.addEventListener('change', function(e) {
            if (e.target.id === 'anggotaSelectAll') {
                if (e.target.checked) {
                    selectedIds = ANGGOTA_LIST.map(a => a.id);
                } else {
                    selectedIds = [];
                }
                renderAnggotaMs();
            } else if (e.target.hasAttribute('data-id')) {
                toggleAnggota(e.target.getAttribute('data-id'), e.target.checked);
            }
        });

        document.addEventListener('click', function(e) {
            if (!msEl.contains(e.target)) {
                msEl.classList.remove('open');
            }
        });

        renderAnggotaMs();

        function tambahBarisLuar() {
            const wrap = document.getElementById('timLuarWrap');
            const row = document.createElement('div');
            row.className = 'grid g2';
            row.style.marginBottom = '8px';
            row.setAttribute('data-row', '');
            row.innerHTML = `
    <input type="text" name="tim_luar_nama[]" placeholder="Nama lengkap">
    <div style="display:flex; gap:8px;">
      <input type="text" name="tim_luar_instansi[]" placeholder="Asal institusi (opsional)">
      <button type="button" class="btn btn-outline" onclick="this.closest('[data-row]').remove()" style="flex-shrink:0; padding:0 14px;">✕</button>
    </div>`;
            wrap.appendChild(row);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const display = document.getElementById('total_biaya_display');
            const hidden = document.getElementById('total_biaya');

            function formatRupiah(angka) {
                if (!angka) return '';
                return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            if (hidden.value) {
                display.value = formatRupiah(hidden.value);
            }

            display.addEventListener('input', function(e) {
                let angka = e.target.value.replace(/\D/g, '');
                hidden.value = angka;
                e.target.value = formatRupiah(angka);
            });
        });
    </script>
@endsection
