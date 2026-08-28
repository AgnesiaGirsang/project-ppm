@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        .s1-jenis-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 6px;
        }

        @media (max-width: 640px) {
            .s1-jenis-grid {
                grid-template-columns: 1fr;
            }
        }

        .s1-jenis-card {
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1.5px solid #e2ece7;
            border-radius: 12px;
            padding: 14px 16px;
            cursor: pointer;
            position: relative;
            transition: border-color .15s ease, background .15s ease, box-shadow .15s ease;
            background: #fff;
        }

        .s1-jenis-card:hover {
            border-color: #a9d9c6;
            background: #f7fbf9;
        }

        .s1-jenis-card:has(input:checked) {
            border-color: #00875A;
            background: #f4fbf7;
            box-shadow: 0 0 0 3px rgba(0, 135, 90, .12);
        }

        .s1-jenis-card input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .s1-jenis-ic {
            flex-shrink: 0;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #eef2f0;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s ease, color .15s ease;
        }

        .s1-jenis-card:has(input:checked) .s1-jenis-ic {
            background: #00875A;
            color: #fff;
        }

        .s1-jenis-ic svg {
            width: 18px;
            height: 18px;
        }

        .s1-jenis-text b {
            display: block;
            font-size: 13.5px;
            color: #111827;
            font-weight: 700;
        }

        .s1-jenis-text span {
            font-size: 11.5px;
            color: #6b7280;
        }

        .s1-jalur-ic {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #e6f4ee;
            color: #00875A;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            vertical-align: middle;
        }

        .s1-jalur-ic svg {
            width: 15px;
            height: 15px;
        }

        .s1-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 13.5px;
            color: #111827;
            margin-bottom: 8px;
        }
    </style>

    <div class="card wizard-card">
        @include('pengajuan._stepper', ['current' => 1])

        @if ($errors->any())
            <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('pengajuan.step1.post') }}" id="formStep1">
            @csrf

            <div class="field">
                <div class="s1-section-title">Jenis Kegiatan</div>
                <div class="s1-jenis-grid">
                    <label class="s1-jenis-card">
                        <input type="radio" name="jenis" value="penelitian"
                            {{ $w['jenis'] === 'penelitian' ? 'checked' : '' }} required>
                        <div class="s1-jenis-ic">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 3v14" />
                                <path d="M15 7v10" />
                                <path d="M4 21h16" />
                                <path d="M6 21V9a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12" />
                            </svg>
                        </div>
                        <div class="s1-jenis-text"><b>Penelitian</b><span>Kegiatan riset ilmiah</span></div>
                    </label>
                    <label class="s1-jenis-card">
                        <input type="radio" name="jenis" value="pengabdian"
                            {{ $w['jenis'] === 'pengabdian' ? 'checked' : '' }}>
                        <div class="s1-jenis-ic">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </div>
                        <div class="s1-jenis-text"><b>Pengabdian kepada Masyarakat</b><span>Kegiatan berbasis
                                mitra/komunitas</span></div>
                    </label>
                </div>
            </div>

            <div class="field" style="margin-top:16px;">
                <div class="s1-section-title">Jalur Pengajuan</div>
            </div>
            <div class="jalur-grid">
                <div class="jalur-card {{ $w['jalur'] === 'simlitabkes' ? 'sel' : '' }}"
                    onclick="pickJalur(this,'simlitabkes')">
                    <input type="radio" name="jalur" value="simlitabkes"
                        {{ $w['jalur'] === 'simlitabkes' ? 'checked' : '' }} required style="display:none;">
                    <div class="hd">
                        <span class="s1-jalur-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4" />
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                            </svg></span>
                        <b>Simlitabkes</b><span class="tag" style="background:var(--green-bg); color:var(--green-txt);">3
                            Tahap</span>
                    </div>
                    <div class="steps-mini"><span><span class="n">1</span>Proposal</span>→<span><span
                                class="n">2</span>Kemajuan</span>→<span><span class="n">3</span>Hasil</span></div>
                    <p>Wajib melalui 3 tahapan berurutan: Proposal, Laporan Kemajuan, dan Laporan Hasil. Setiap tahap harus
                        disetujui admin sebelum lanjut ke tahap berikutnya.</p>
                </div>
                <div class="jalur-card {{ $w['jalur'] === 'mandiri' ? 'sel' : '' }}" onclick="pickJalur(this,'mandiri')">
                    <input type="radio" name="jalur" value="mandiri" {{ $w['jalur'] === 'mandiri' ? 'checked' : '' }}
                        style="display:none;">
                    <div class="hd">
                        <span class="s1-jalur-ic" style="background:#eee6fb; color:#6b3fc2;"><svg viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg></span>
                        <b>Mandiri</b><span class="tag" style="background:#eee6fb; color:#6b3fc2;">2
                            Tahap</span>
                    </div>
                    <div class="steps-mini"><span><span class="n">1</span>Proposal</span>→<span><span
                                class="n">2</span>Hasil</span></div>
                    <p>Hanya perlu memenuhi 2 tahapan: Proposal dan Laporan Hasil. Laporan Kemajuan tidak diperlukan pada
                        jalur ini.</p>
                </div>
            </div>

            <div class="field">
                <label>Skema</label>
                <select name="skema_id" id="skemaSelect" required>
                    <option value="">Pilih jenis & jalur dahulu...</option>
                </select>
            </div>

            <div class="grid g2" style="margin-top:6px;">
                <div>
                    <div class="field"><label>Rumpun Ilmu</label>
                        <select name="rumpun_ilmu_id">
                            <option value="">Pilih rumpun ilmu</option>
                            @foreach ($rumpunIlmu as $r)
                                <option value="{{ $r->id }}"
                                    {{ (int) $w['rumpun_ilmu_id'] === $r->id ? 'selected' : '' }}>{{ $r->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <div class="field"><label>Judul
                            {{ $w['jenis'] === 'pengabdian' ? 'Pengabdian' : 'Penelitian' }}</label>
                        <input type="text" name="judul" placeholder="Contoh: Sistem Informasi Klinik Berbasis Web"
                            value="{{ $w['judul'] }}" required>
                    </div>
                </div>
            </div>

            <div class="grid g4">
                <div class="field"><label>Tahun Anggaran</label><input type="number" name="tahun_anggaran"
                        value="{{ $w['tahun_anggaran'] }}" min="2020" max="2100" required></div>
                <div class="field"><label>Tahun Pengajuan</label><input type="number" name="tahun_pengajuan"
                        value="{{ $w['tahun_pengajuan'] }}" min="2020" max="2100" required></div>
                <div class="field"><label>Tahun Pelaksanaan</label>
                    <select name="tahun_pelaksanaan" required>
                        <option value="I" {{ $w['tahun_pelaksanaan'] === 'I' ? 'selected' : '' }}>I</option>
                        <option value="II" {{ $w['tahun_pelaksanaan'] === 'II' ? 'selected' : '' }}>II</option>
                        <option value="III" {{ $w['tahun_pelaksanaan'] === 'III' ? 'selected' : '' }}>III</option>
                    </select>
                </div>
                <div class="field"><label>Tahun Capaian</label><input type="number" name="tahun_capaian"
                        value="{{ $w['tahun_capaian'] }}" min="2020" max="2100" required></div>
            </div>

            <div style="display:flex; justify-content:flex-end; margin-top:20px;">
                <button class="btn btn-primary" type="submit">Selanjutnya</button>
            </div>
        </form>
    </div>

    <script>
        const SKEMA_DATA = @json($skemaGrouped);
        const SELECTED_SKEMA = {{ $w['skema_id'] ? (int) $w['skema_id'] : 'null' }};

        function pickJalur(el, val) {
            el.parentElement.querySelectorAll('.jalur-card').forEach(c => c.classList.remove('sel'));
            el.classList.add('sel');
            el.querySelector('input[type=radio]').checked = true;
            loadSkema();
        }

        function loadSkema() {
            const jenis = document.querySelector('input[name=jenis]:checked')?.value;
            const jalur = document.querySelector('input[name=jalur]:checked')?.value;
            const sel = document.getElementById('skemaSelect');
            sel.innerHTML = '';

            if (!jenis || !jalur) {
                sel.innerHTML = '<option value="">Pilih jenis & jalur dahulu...</option>';
                return;
            }

            const key = jenis + '|' + jalur;
            const list = SKEMA_DATA[key] || [];

            if (list.length === 0) {
                sel.innerHTML = '<option value="">Tidak ada skema tersedia</option>';
                return;
            }

            sel.innerHTML = '<option value="">Pilih skema...</option>' +
                list.map(s => `<option value="${s.id}" ${s.id === SELECTED_SKEMA ? 'selected' : ''}>${s.nama}</option>`)
                .join('');
        }

        document.querySelectorAll('input[name=jenis]').forEach(r => r.addEventListener('change', loadSkema));

        loadSkema();
    </script>
@endsection
