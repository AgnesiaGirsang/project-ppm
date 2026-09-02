@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        .s3-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 13.5px;
            color: #111827;
            margin-bottom: 12px;
        }

        .s3-section-title .ic {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: #e6f4ee;
            color: #00875A;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .s3-section-title .ic svg {
            width: 13px;
            height: 13px;
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

        /* ===================== COMPACT UPLOAD CARD ===================== */
        .upload-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 18px;
        }

        .upload-row {
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            background: #fff;
            transition: border-color .15s ease, background .15s ease;
        }

        .upload-row:hover {
            border-color: #00875A;
            background: #f8fdfb;
        }

        .upload-row.is-filled {
            border-color: #b7e4cf;
            background: #f2fbf7;
        }

        .upload-row .u-icon {
            flex: 0 0 auto;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #eef2f7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .upload-row.is-filled .u-icon {
            background: #dcfce7;
        }

        .upload-row .u-info {
            flex: 1 1 auto;
            min-width: 0;
        }

        .upload-row .u-title {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
            font-size: 12.5px;
            color: #111827;
            margin-bottom: 2px;
        }

        .upload-row .u-meta {
            font-size: 11px;
            color: #6b7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .upload-row .u-meta.u-meta-ok {
            color: #00875A;
            font-weight: 600;
        }

        .upload-row .u-status {
            flex: 0 0 auto;
            font-size: 10.5px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 999px;
            background: #f3f4f6;
            color: #6b7280;
            white-space: nowrap;
        }

        .upload-row.is-filled .u-status {
            background: #dcfce7;
            color: #00875A;
        }

        .upload-row .u-btn {
            flex: 0 0 auto;
            border: 1px solid #d1d5db;
            background: #fff;
            color: #374151;
            font-size: 11.5px;
            font-weight: 700;
            padding: 7px 12px;
            border-radius: 8px;
            cursor: pointer;
            white-space: nowrap;
            transition: background .15s ease, border-color .15s ease;
        }

        .upload-row .u-btn:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .upload-hint {
            font-size: 11px;
            color: var(--ink-500, #6b7280);
            margin: -6px 0 14px 2px;
        }
    </style>

    <div class="card wizard-card">
        @include('pengajuan._stepper', ['current' => 3])

        @if ($errors->any())
            <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('pengajuan.step3.post') }}" enctype="multipart/form-data">
            @csrf

            <div class="s3-section-title">
                <span class="ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                </span>
                Dokumen & Anggaran
            </div>

            <div class="upload-hint">Format PDF, ukuran maksimal 2 MB per dokumen. Klik baris di bawah untuk memilih
                file.</div>

            <div class="upload-list">
                {{-- ===================== Dokumen Proposal (WAJIB) ===================== --}}
                @php
                    $proposalFilled = (bool) $w['proposal_path'];
                @endphp
                <label for="proposalInput" class="upload-row {{ $proposalFilled ? 'is-filled' : '' }}" id="rowProposal">
                    <span class="u-icon">📄</span>
                    <span class="u-info">
                        <span class="u-title">Dokumen Proposal <span class="tag-wajib-field">WAJIB</span></span>
                        <span class="u-meta {{ $proposalFilled ? 'u-meta-ok' : '' }}" id="metaProposal">
                            @if ($proposalFilled)
                                {{ $w['proposal_nama_asli'] }} &middot;
                                {{ number_format($w['proposal_size'] / 1024, 0) }} KB
                            @else
                                Belum ada file dipilih
                            @endif
                        </span>
                    </span>
                    <span class="u-status" id="statusProposal">{{ $proposalFilled ? 'Tersimpan' : 'Kosong' }}</span>
                    <span class="u-btn">Pilih File</span>
                </label>
                <input type="file" id="proposalInput" name="proposal" accept="application/pdf" style="display:none;"
                    onchange="handleFile(this, 'rowProposal', 'metaProposal', 'statusProposal')">

                {{-- ===================== Dokumen Kontrak (WAJIB) ===================== --}}
                @php
                    $kontrakFilled = (bool) $w['kontrak_path'];
                @endphp
                <label for="kontrakInput" class="upload-row {{ $kontrakFilled ? 'is-filled' : '' }}" id="rowKontrak">
                    <span class="u-icon">📄</span>
                    <span class="u-info">
                        <span class="u-title">Dokumen Kontrak <span class="tag-wajib-field">WAJIB</span></span>
                        <span class="u-meta {{ $kontrakFilled ? 'u-meta-ok' : '' }}" id="metaKontrak">
                            @if ($kontrakFilled)
                                {{ $w['kontrak_nama_asli'] }} &middot;
                                {{ number_format($w['kontrak_size'] / 1024, 0) }} KB
                            @else
                                Belum ada file dipilih
                            @endif
                        </span>
                    </span>
                    <span class="u-status" id="statusKontrak">{{ $kontrakFilled ? 'Tersimpan' : 'Kosong' }}</span>
                    <span class="u-btn">Pilih File</span>
                </label>
                <input type="file" id="kontrakInput" name="kontrak" accept="application/pdf" style="display:none;"
                    onchange="handleFile(this, 'rowKontrak', 'metaKontrak', 'statusKontrak')">

                {{-- ===================== Dokumen RAB (WAJIB) ===================== --}}
                @php
                    $rabFilled = (bool) $w['rab_path'];
                @endphp
                <label for="rabInput" class="upload-row {{ $rabFilled ? 'is-filled' : '' }}" id="rowRab">
                    <span class="u-icon">📄</span>
                    <span class="u-info">
                        <span class="u-title">Dokumen RAB <span class="tag-wajib-field">WAJIB</span></span>
                        <span class="u-meta {{ $rabFilled ? 'u-meta-ok' : '' }}" id="metaRab">
                            @if ($rabFilled)
                                {{ $w['rab_nama_asli'] }} &middot; {{ number_format($w['rab_size'] / 1024, 0) }} KB
                            @else
                                Belum ada file dipilih
                            @endif
                        </span>
                    </span>
                    <span class="u-status" id="statusRab">{{ $rabFilled ? 'Tersimpan' : 'Kosong' }}</span>
                    <span class="u-btn">Pilih File</span>
                </label>
                <input type="file" id="rabInput" name="rab" accept="application/pdf" style="display:none;"
                    onchange="handleFile(this, 'rowRab', 'metaRab', 'statusRab')">

                {{-- ===================== Dokumen Kwitansi (WAJIB) ===================== --}}
                @php
                    $kwitansiFilled = (bool) $w['kwitansi_path'];
                @endphp
                <label for="kwitansiInput" class="upload-row {{ $kwitansiFilled ? 'is-filled' : '' }}" id="rowKwitansi">
                    <span class="u-icon">📄</span>
                    <span class="u-info">
                        <span class="u-title">Dokumen Kwitansi <span class="tag-wajib-field">WAJIB</span></span>
                        <span class="u-meta {{ $kwitansiFilled ? 'u-meta-ok' : '' }}" id="metaKwitansi">
                            @if ($kwitansiFilled)
                                {{ $w['kwitansi_nama_asli'] }} &middot;
                                {{ number_format($w['kwitansi_size'] / 1024, 0) }} KB
                            @else
                                Belum ada file dipilih
                            @endif
                        </span>
                    </span>
                    <span class="u-status" id="statusKwitansi">{{ $kwitansiFilled ? 'Tersimpan' : 'Kosong' }}</span>
                    <span class="u-btn">Pilih File</span>
                </label>
                <input type="file" id="kwitansiInput" name="kwitansi" accept="application/pdf" style="display:none;"
                    onchange="handleFile(this, 'rowKwitansi', 'metaKwitansi', 'statusKwitansi')">

                {{-- ===================== Bukti Pajak (OPSIONAL) ===================== --}}
                @php
                    $pajakFilled = (bool) $w['bukti_pajak_path'];
                @endphp
                <label for="buktiPajakInput" class="upload-row {{ $pajakFilled ? 'is-filled' : '' }}" id="rowBuktiPajak">
                    <span class="u-icon">📄</span>
                    <span class="u-info">
                        <span class="u-title">Bukti Pajak <span class="tag-opsional">OPSIONAL</span></span>
                        <span class="u-meta {{ $pajakFilled ? 'u-meta-ok' : '' }}" id="metaBuktiPajak">
                            @if ($pajakFilled)
                                {{ $w['bukti_pajak_nama_asli'] }} &middot;
                                {{ number_format($w['bukti_pajak_size'] / 1024, 0) }} KB
                            @else
                                Belum ada file dipilih (jika ada)
                            @endif
                        </span>
                    </span>
                    <span class="u-status" id="statusBuktiPajak">{{ $pajakFilled ? 'Tersimpan' : 'Kosong' }}</span>
                    <span class="u-btn">Pilih File</span>
                </label>
                <input type="file" id="buktiPajakInput" name="bukti_pajak" accept="application/pdf"
                    style="display:none;"
                    onchange="handleFile(this, 'rowBuktiPajak', 'metaBuktiPajak', 'statusBuktiPajak')">

                {{-- ===================== Berita Acara / Hibah (OPSIONAL) ===================== --}}
                @php
                    $beritaFilled = (bool) $w['berita_acara_path'];
                @endphp
                <label for="beritaAcaraInput" class="upload-row {{ $beritaFilled ? 'is-filled' : '' }}"
                    id="rowBeritaAcara">
                    <span class="u-icon">📄</span>
                    <span class="u-info">
                        <span class="u-title">Berita Acara / Hibah <span class="tag-opsional">OPSIONAL</span></span>
                        <span class="u-meta {{ $beritaFilled ? 'u-meta-ok' : '' }}" id="metaBeritaAcara">
                            @if ($beritaFilled)
                                {{ $w['berita_acara_nama_asli'] }} &middot;
                                {{ number_format($w['berita_acara_size'] / 1024, 0) }} KB
                            @else
                                Belum ada file dipilih (jika ada)
                            @endif
                        </span>
                    </span>
                    <span class="u-status" id="statusBeritaAcara">{{ $beritaFilled ? 'Tersimpan' : 'Kosong' }}</span>
                    <span class="u-btn">Pilih File</span>
                </label>
                <input type="file" id="beritaAcaraInput" name="berita_acara" accept="application/pdf"
                    style="display:none;"
                    onchange="handleFile(this, 'rowBeritaAcara', 'metaBeritaAcara', 'statusBeritaAcara')">
            </div>

            <div class="alert-box alert-amber" style="margin-bottom:18px;">⚠️ Jika proposal diminta revisi oleh admin,
                dokumen lama <b>tidak akan dihapus</b> dan tetap tersimpan. Anda cukup mengunggah dokumen proposal versi
                terbaru sebagai tambahan.</div>

            <div class="field">
                <label>Total Biaya Usulan (Rp)</label>
                <input type="text" id="total_biaya_display" placeholder="10.000.000" autocomplete="off"
                    inputmode="numeric">
                <input type="hidden" name="total_biaya" id="total_biaya" value="{{ $w['total_biaya'] }}">
            </div>

            <div style="display:flex; justify-content:space-between; margin-top:20px;">
                <a href="{{ route('pengajuan.step2') }}" class="btn btn-outline">Kembali</a>
                <button class="btn btn-primary" type="submit">Selanjutnya</button>
            </div>
        </form>
    </div>

    <script>
        function handleFile(input, rowId, metaId, statusId) {
            const row = document.getElementById(rowId);
            const meta = document.getElementById(metaId);
            const status = document.getElementById(statusId);

            if (input.files && input.files[0]) {
                const f = input.files[0];
                row.classList.add('is-filled');
                meta.classList.add('u-meta-ok');
                meta.textContent = `${f.name} · ${Math.round(f.size / 1024)} KB`;
                status.textContent = 'Siap diunggah';
            }
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
