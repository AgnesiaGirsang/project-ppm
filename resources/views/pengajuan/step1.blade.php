@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <div class="card wizard-card">
        @include('pengajuan._stepper', ['current' => 1])

        @if ($errors->any())
            <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('pengajuan.step1.post') }}" id="formStep1">
            @csrf

            <div class="field">
                <label>Jenis Kegiatan</label>
                <label class="radio-opt">
                    <input type="radio" name="jenis" value="penelitian"
                        {{ $w['jenis'] === 'penelitian' ? 'checked' : '' }} required>
                    Penelitian
                </label>
                <label class="radio-opt">
                    <input type="radio" name="jenis" value="pengabdian"
                        {{ $w['jenis'] === 'pengabdian' ? 'checked' : '' }}>
                    Pengabdian kepada Masyarakat
                </label>
            </div>

            <div class="field"><label>Jalur Pengajuan</label></div>
            <div class="jalur-grid">
                <div class="jalur-card {{ $w['jalur'] === 'simlitabkes' ? 'sel' : '' }}"
                    onclick="pickJalur(this,'simlitabkes')">
                    <input type="radio" name="jalur" value="simlitabkes"
                        {{ $w['jalur'] === 'simlitabkes' ? 'checked' : '' }} required style="display:none;">
                    <div class="hd"><b>Simlitabkes</b><span class="tag"
                            style="background:var(--green-bg); color:var(--green-txt);">3 Tahap</span></div>
                    <div class="steps-mini"><span><span class="n">1</span>Proposal</span>→<span><span
                                class="n">2</span>Kemajuan</span>→<span><span class="n">3</span>Hasil</span></div>
                    <p>Wajib melalui 3 tahapan berurutan: Proposal, Laporan Kemajuan, dan Laporan Hasil. Setiap tahap harus
                        disetujui admin sebelum lanjut ke tahap berikutnya.</p>
                </div>
                <div class="jalur-card {{ $w['jalur'] === 'mandiri' ? 'sel' : '' }}" onclick="pickJalur(this,'mandiri')">
                    <input type="radio" name="jalur" value="mandiri" {{ $w['jalur'] === 'mandiri' ? 'checked' : '' }}
                        style="display:none;">
                    <div class="hd"><b>Mandiri</b><span class="tag" style="background:#eee6fb; color:#6b3fc2;">2
                            Tahap</span></div>
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
                            <option value="">Pilih rumpun ilmu (opsional)</option>
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
