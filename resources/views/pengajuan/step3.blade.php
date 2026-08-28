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

            <div class="field">
                <label>Dokumen Proposal</label>
                <div class="sub" style="margin-bottom:10px; font-size:11.5px; color:var(--ink-500);">Unggah proposal
                    dalam
                    format PDF, ukuran maksimal 2 MB.</div>

                <label for="proposalInput" style="display:block;">
                    <div class="dropzone">
                        <div class="ic">☁️</div>
                        <b>Upload File Proposal (PDF, maks. 2MB)</b>
                        <span>Klik di sini untuk memilih file</span>
                    </div>
                </label>
                <input type="file" id="proposalInput" name="proposal" accept="application/pdf" style="display:none;"
                    onchange="showFileName(this)">

                @if ($w['proposal_path'])
                    <div class="file-chip" id="fileChip">
                        <span>📄 {{ $w['proposal_nama_asli'] }} &middot; {{ number_format($w['proposal_size'] / 1024, 0) }}
                            KB</span>
                        <span style="color:var(--green-700); font-weight:700;">Tersimpan</span>
                    </div>
                @else
                    <div class="file-chip" id="fileChip" style="display:none;"></div>
                @endif

                <div class="alert-box alert-amber" style="margin-top:14px;">⚠️ Jika proposal diminta revisi oleh admin,
                    dokumen lama <b>tidak akan dihapus</b> dan tetap tersimpan. Anda cukup mengunggah dokumen proposal versi
                    terbaru sebagai tambahan.</div>
            </div>

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
        function showFileName(input) {
            const chip = document.getElementById('fileChip');
            if (input.files && input.files[0]) {
                const f = input.files[0];
                chip.style.display = 'flex';
                chip.innerHTML =
                    `<span>📄 ${f.name} &middot; ${Math.round(f.size/1024)} KB</span><span style="color:var(--green-700); font-weight:700;">Siap diunggah</span>`;
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
