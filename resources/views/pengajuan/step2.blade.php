@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <div class="card wizard-card">
        @include('pengajuan._stepper', ['current' => 2])

        <div class="field">
            <label>Ketua Pengaju</label>
            <div class="check-list" style="max-height:none;">
                <label style="cursor:default;">
                    <div class="av">{{ $ketua->initials() }}</div>
                    <div><b>{{ $ketua->nama }}</b><br><span style="color:var(--ink-500); font-size:11px;">NIP
                            {{ $ketua->nip }} &middot; Ketua (otomatis, akun kamu sendiri)</span></div>
                </label>
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

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIDN/NIP</th>
                        <th>Jurusan/Institusi</th>
                        <th>Peran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="timBody">
                    <tr>
                        <td>1</td>
                        <td>{{ $ketua->nama }}</td>
                        <td>{{ $ketua->nidn ?? $ketua->nip }}</td>
                        <td>{{ $ketua->jurusan }}</td>
                        <td><span class="badge b-disetujui">Ketua</span></td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>

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
      <td colspan="2">
        <select onchange="changeAnggota(${idx}, this.value)">${optionsHTML(t.pegawai_id)}</select>
        ${isExternal ? `<div style="display:flex; gap:6px; margin-top:6px;">
                                                                                                                                                                                                                              <input type="text" placeholder="Nama lengkap" value="${t.nama_external || ''}" oninput="tim[${idx}].nama_external=this.value">
                                                                                                                                                                                                                              <input type="text" placeholder="Institusi asal" value="${t.institusi_external || ''}" oninput="tim[${idx}].institusi_external=this.value; renderTim();">
                                                                                                                                                                                                                            </div>` : ''}
      </td>
      <td>${isExternal ? (t.institusi_external || '-') : (pegawai ? pegawai.jurusan : '-')}</td>
      <td><span class="badge b-revisi">Anggota</span></td>
      <td><button type="button" class="icon-btn" onclick="removeAnggota(${idx})">🗑</button></td>
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
