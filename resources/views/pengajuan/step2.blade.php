@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        .select-wrap {
            position: relative;
            width: 100%;
        }

        .select-wrap select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 100%;
            padding: 9px 34px 9px 12px;
            border: 1px solid #d8dee3;
            border-radius: 8px;
            background: #fff;
            font-size: 13px;
            color: #1f2937;
            font-family: inherit;
            cursor: pointer;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .select-wrap select:hover {
            border-color: #00875A;
        }

        .select-wrap select:focus {
            outline: none;
            border-color: #00875A;
            box-shadow: 0 0 0 3px rgba(0, 135, 90, 0.15);
        }

        .select-wrap::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 12px;
            width: 8px;
            height: 8px;
            border-right: 2px solid #6b7280;
            border-bottom: 2px solid #6b7280;
            transform: translateY(-65%) rotate(45deg);
            pointer-events: none;
        }

        .external-fields {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .external-fields input {
            flex: 1;
            padding: 8px 10px;
            border: 1px solid #d8dee3;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
        }

        .external-fields input:focus {
            outline: none;
            border-color: #00875A;
            box-shadow: 0 0 0 3px rgba(0, 135, 90, 0.15);
        }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid #f3c9cb;
            background: #fdf2f3;
            color: #e5484d;
            cursor: pointer;
            transition: background .15s ease, border-color .15s ease, transform .1s ease;
        }

        .btn-delete svg {
            width: 15px;
            height: 15px;
        }

        .btn-delete:hover {
            background: #e5484d;
            border-color: #e5484d;
            color: #fff;
        }

        .btn-delete:active {
            transform: scale(0.95);
        }

        .tim-table-wrap {
            margin-top: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
        }

        table.tim-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        table.tim-table thead th {
            background: #f8fafc;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            text-align: left;
            padding: 13px 16px;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        table.tim-table thead th:first-child {
            width: 56px;
            text-align: center;
        }

        table.tim-table thead th:last-child {
            width: 70px;
            text-align: center;
        }

        table.tim-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f3f5;
            vertical-align: middle;
            color: #1f2937;
        }

        table.tim-table tbody tr:last-child td {
            border-bottom: none;
        }

        table.tim-table tbody tr:hover {
            background: #fafbfc;
        }

        table.tim-table td:first-child {
            text-align: center;
            color: #9ca3af;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        table.tim-table td:last-child {
            text-align: center;
        }

        .tim-person {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tim-person .tim-avatar {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e6f4ee;
            color: #00875A;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tim-person b {
            font-weight: 600;
            font-size: 13.5px;
            color: #111827;
        }

        table.tim-table td.tim-nip {
            font-variant-numeric: tabular-nums;
            color: #4b5563;
        }

        table.tim-table td.tim-jurusan {
            color: #4b5563;
        }

        .badge-peran {
            display: inline-flex;
            align-items: center;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .02em;
        }

        .badge-peran.ketua {
            background: #e6f4ee;
            color: #00875A;
        }

        .badge-peran.anggota {
            background: #eef2ff;
            color: #4338ca;
        }

        .s2-ketua-card {
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1.5px solid #d6f0e3;
            background: #f4fbf7;
            border-radius: 12px;
            padding: 12px 16px;
        }

        .s2-ketua-av {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #00875A;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
    </style>

    <div class="card wizard-card">
        @include('pengajuan._stepper', ['current' => 2])

        <div class="field">
            <label>Ketua Pengaju</label>
            <div class="s2-ketua-card">
                <div class="s2-ketua-av">{{ $ketua->initials() }}</div>
                <div><b style="font-size:13.5px; color:#111827;">{{ $ketua->nama }}</b><br>
                    <span style="color:#6b7280; font-size:11.5px;">NIP {{ $ketua->nip }} &middot; Ketua (otomatis, akun
                        kamu sendiri)</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('pengajuan.step2.post') }}" id="formStep2">
            @csrf

            <div class="row-between" style="margin-top:22px;">
                <h3 style="margin-bottom:0;">Daftar Tim Peneliti/Pelaksana</h3>
                <button type="button" class="btn btn-primary btn-sm" onclick="addAnggota()">+ Tambah Anggota</button>
            </div>
            <div class="alert-box alert-info">ℹ️ Tim bersifat dinamis (1–5 orang termasuk ketua), dipilih dari data pegawai
                yang telah diimpor admin. Jika anggota berasal dari luar Poltekkes, pilih opsi "Lainnya".</div>

            <div class="tim-table-wrap">
                <table class="tim-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jurusan/Institusi</th>
                            <th>Peran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="timBody">
                        <tr>
                            <td>1</td>
                            <td>
                                <div class="tim-person">
                                    <div class="tim-avatar">{{ $ketua->initials() }}</div>
                                    <b>{{ $ketua->nama }}</b>
                                </div>
                            </td>
                            <td class="tim-nip">{{ $ketua->nip }}</td>
                            <td class="tim-jurusan">{{ $ketua->jurusan }}</td>
                            <td><span class="badge-peran ketua">Ketua</span></td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="display:flex; justify-content:space-between; margin-top:20px;">
                <a href="{{ route('pengajuan.step1') }}" class="btn btn-outline">Kembali</a>
                <button class="btn btn-primary" type="submit">Selanjutnya</button>
            </div>
        </form>
    </div>

    <script>
        const PEGAWAI_LIST = @json($pegawaiListJson);
        const INITIAL_ANGGOTA = @json($initialAnggotaJson);

        let tim = INITIAL_ANGGOTA.filter(a => a.pegawai_id || a.nama_external).map(a => ({
            pegawai_id: a.pegawai_id ? a.pegawai_id : (a.nama_external ? 'external' : ''),
            nama_external: a.nama_external,
            institusi_external: a.institusi_external,
        }));

        function optionsHTML(selectedId) {
            let opts = '<option value="">— Pilih dari data pegawai —</option>';
            PEGAWAI_LIST.forEach(p => {
                const used = tim.some(t => t.pegawai_id == p.id);
                if (!used || selectedId == p.id) {
                    opts += `<option value="${p.id}" ${selectedId == p.id ? 'selected' : ''}>${p.nama}</option>`;
                }
            });
            opts +=
                `<option value="external" ${selectedId === 'external' ? 'selected' : ''}>Lainnya (di luar data pegawai)</option>`;
            return opts;
        }

        function renderTim() {
            const tbody = document.getElementById('timBody');
            tbody.querySelectorAll('tr[data-row]').forEach(r => r.remove());

            tim.forEach((t, idx) => {
                const isExternal = t.pegawai_id === 'external';
                const pegawai = !isExternal ? PEGAWAI_LIST.find(p => p.id == t.pegawai_id) : null;
                const tr = document.createElement('tr');
                tr.setAttribute('data-row', '');
                tr.innerHTML = `
      <td>${idx + 2}</td>
      <td>
        <div class="select-wrap">
          <select onchange="changeAnggota(${idx}, this.value)">${optionsHTML(t.pegawai_id)}</select>
        </div>
        ${isExternal ? `<div class="external-fields">
                                                                                                                                                                                                                                      <input type="text" placeholder="Nama lengkap" value="${t.nama_external || ''}" oninput="tim[${idx}].nama_external=this.value">
                                                                                                                                                                                                                                      <input type="text" placeholder="Institusi asal" value="${t.institusi_external || ''}" oninput="tim[${idx}].institusi_external=this.value; renderTim();">
                                                                                                                                                                                                                                    </div>` : ''}
      </td>
      <td class="tim-nip">${isExternal ? '-' : (pegawai ? (pegawai.nip || '-') : '-')}</td>
      <td class="tim-jurusan">${isExternal ? (t.institusi_external || '-') : (pegawai ? pegawai.jurusan : '-')}</td>
      <td><span class="badge-peran anggota">Anggota</span></td>
      <td>
        <button type="button" class="btn-delete" title="Hapus anggota" onclick="removeAnggota(${idx})">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
            <path d="M10 11v6"></path>
            <path d="M14 11v6"></path>
            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
          </svg>
        </button>
      </td>
    `;
                tbody.appendChild(tr);
            });

            syncHiddenInputs();
        }

        function syncHiddenInputs() {
            document.querySelectorAll('.hidden-anggota').forEach(el => el.remove());
            const form = document.getElementById('formStep2');
            tim.forEach((t, idx) => {
                const isExternal = t.pegawai_id === 'external';
                form.insertAdjacentHTML('beforeend', `
      <input type="hidden" class="hidden-anggota" name="anggota[${idx}][pegawai_id]" value="${isExternal ? '' : (t.pegawai_id || '')}">
      <input type="hidden" class="hidden-anggota" name="anggota[${idx}][nama_external]" value="${isExternal ? (t.nama_external || '') : ''}">
      <input type="hidden" class="hidden-anggota" name="anggota[${idx}][institusi_external]" value="${isExternal ? (t.institusi_external || '') : ''}">
    `);
            });
        }

        function addAnggota() {
            if (tim.length >= 4) {
                alert('Maksimal tim berjumlah 5 orang (1 ketua + 4 anggota).');
                return;
            }
            tim.push({
                pegawai_id: '',
                nama_external: '',
                institusi_external: ''
            });
            renderTim();
        }

        function removeAnggota(idx) {
            tim.splice(idx, 1);
            renderTim();
        }

        function changeAnggota(idx, val) {
            tim[idx] = {
                pegawai_id: val,
                nama_external: '',
                institusi_external: ''
            };
            renderTim();
        }

        renderTim();
    </script>
@endsection
