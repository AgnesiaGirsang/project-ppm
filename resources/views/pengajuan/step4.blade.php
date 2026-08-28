@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        .s4-cols {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .s4-col {
            flex: 1;
            min-width: 340px;
        }

        .s4-table-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05);
        }

        .s4-table-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 16px;
            background: linear-gradient(180deg, #00875A 0%, #046a48 100%);
            color: #fff;
        }

        .s4-col.tambahan .s4-table-head {
            background: linear-gradient(180deg, #4f46e5 0%, #3730a3 100%);
        }

        .s4-table-head b {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .02em;
        }

        .s4-pill {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .04em;
            padding: 3px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .22);
            color: #fff;
        }

        table.s4-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            background: #fff;
        }

        table.s4-table thead th {
            background: #f8fafc;
            color: #64748b;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            text-align: left;
            padding: 10px 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        table.s4-table thead th:first-child {
            width: 44px;
            text-align: center;
        }

        table.s4-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f3f5;
            vertical-align: middle;
        }

        table.s4-table tbody tr:last-child td {
            border-bottom: none;
        }

        table.s4-table tbody tr {
            transition: opacity .15s ease, background .15s ease;
        }

        table.s4-table tbody tr:not(.s4-row-disabled):hover {
            background: #fafbfc;
        }

        table.s4-table tbody tr.s4-row-checked {
            background: #f4fbf7;
        }

        table.s4-table tbody tr.s4-row-disabled {
            opacity: .45;
        }

        table.s4-table td:first-child {
            text-align: center;
        }

        table.s4-table input[type=checkbox] {
            width: 16px;
            height: 16px;
            accent-color: #00875A;
            cursor: pointer;
        }

        .s4-jenis {
            color: #1f2937;
        }

        .s4-jenis.on {
            font-weight: 700;
            color: #111827;
        }

        table.s4-table select {
            width: 100%;
            padding: 7px 10px;
            border: 1px solid #d8dee3;
            border-radius: 7px;
            font-size: 12.5px;
            font-family: inherit;
            background: #fff;
        }

        table.s4-table select:disabled {
            background: #f8fafc;
            color: #9ca3af;
        }

        .s4-dash {
            color: #9ca3af;
            font-size: 12px;
        }
    </style>

    <div class="card wizard-card">
        @include('pengajuan._stepper', ['current' => 4])

        <form method="POST" action="{{ route('pengajuan.step4.post') }}">
            @csrf

            @error('luaran_wajib')
                <div class="alert alert-danger" style="margin-bottom:14px;">{{ $message }}</div>
            @enderror

            <div class="s4-cols">

                {{-- TABEL 1: LUARAN WAJIB --}}
                <div class="s4-col wajib">
                    <div class="s4-table-wrap">
                        <div class="s4-table-head">
                            <b>Tabel 1 · Luaran Wajib</b>
                            <span class="s4-pill">Minimal 1 luaran</span>
                        </div>
                        <table class="s4-table">
                            <thead>
                                <tr>
                                    <th>Pilih</th>
                                    <th>Jenis Luaran</th>
                                    <th>Indikator Capaian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($luarans as $l)
                                    @php
                                        $checkedWajib = array_key_exists($l->id, $w['luaran_wajib']);
                                        $checkedTambahan = array_key_exists($l->id, $w['luaran_tambahan']);
                                        $opsiListWajib = is_array($l->opsi_indikator) ? $l->opsi_indikator : [];
                                        $rowClass = $checkedWajib
                                            ? 's4-row-checked'
                                            : ($checkedTambahan
                                                ? 's4-row-disabled'
                                                : '');
                                    @endphp
                                    <tr id="row-wajib-{{ $l->id }}" class="{{ $rowClass }}">
                                        <td>
                                            <input type="checkbox" name="luaran_wajib[]" value="{{ $l->id }}"
                                                id="wajib_check_{{ $l->id }}"
                                                onchange="toggleLuaran({{ $l->id }}, 'wajib', this)"
                                                {{ $checkedWajib ? 'checked' : '' }}
                                                {{ $checkedTambahan ? 'disabled' : '' }}>
                                        </td>
                                        <td class="s4-jenis {{ $checkedWajib ? 'on' : '' }}">{{ $l->nama }}</td>
                                        <td>
                                            @if (!empty($opsiListWajib))
                                                <select name="luaran_wajib_opsi[{{ $l->id }}]"
                                                    id="wajib_opsi_{{ $l->id }}"
                                                    {{ $checkedWajib ? '' : 'disabled' }}>
                                                    <option value="">Pilih Indikator...</option>
                                                    @foreach ($opsiListWajib as $opsi)
                                                        <option value="{{ $opsi }}"
                                                            {{ ($w['luaran_wajib'][$l->id] ?? null) === $opsi ? 'selected' : '' }}>
                                                            {{ $opsi }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <span
                                                    class="s4-dash">{{ $checkedTambahan ? 'Sudah dipilih di Luaran Tambahan' : '-' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL 2: LUARAN TAMBAHAN --}}
                <div class="s4-col tambahan">
                    <div class="s4-table-wrap">
                        <div class="s4-table-head">
                            <b>Tabel 2 · Luaran Tambahan</b>
                            <span class="s4-pill">Opsional</span>
                        </div>
                        <table class="s4-table">
                            <thead>
                                <tr>
                                    <th>Pilih</th>
                                    <th>Jenis Luaran</th>
                                    <th>Indikator Capaian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($luarans as $l)
                                    @php
                                        $checkedWajib = array_key_exists($l->id, $w['luaran_wajib']);
                                        $checkedTambahan = array_key_exists($l->id, $w['luaran_tambahan']);
                                        $opsiListTambahan = is_array($l->opsi_indikator) ? $l->opsi_indikator : [];
                                        $rowClass = $checkedTambahan
                                            ? 's4-row-checked'
                                            : ($checkedWajib
                                                ? 's4-row-disabled'
                                                : '');
                                    @endphp
                                    <tr id="row-tambahan-{{ $l->id }}" class="{{ $rowClass }}">
                                        <td>
                                            <input type="checkbox" name="luaran_tambahan[]" value="{{ $l->id }}"
                                                id="tambahan_check_{{ $l->id }}"
                                                onchange="toggleLuaran({{ $l->id }}, 'tambahan', this)"
                                                {{ $checkedTambahan ? 'checked' : '' }}
                                                {{ $checkedWajib ? 'disabled' : '' }}>
                                        </td>
                                        <td class="s4-jenis {{ $checkedTambahan ? 'on' : '' }}">{{ $l->nama }}</td>
                                        <td>
                                            @if (!empty($opsiListTambahan))
                                                <select name="luaran_tambahan_opsi[{{ $l->id }}]"
                                                    id="tambahan_opsi_{{ $l->id }}"
                                                    {{ $checkedTambahan ? '' : 'disabled' }}>
                                                    <option value="">Pilih Indikator...</option>
                                                    @foreach ($opsiListTambahan as $opsi)
                                                        <option value="{{ $opsi }}"
                                                            {{ ($w['luaran_tambahan'][$l->id] ?? null) === $opsi ? 'selected' : '' }}>
                                                            {{ $opsi }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <span
                                                    class="s4-dash">{{ $checkedWajib ? 'Sudah dipilih di Luaran Wajib' : '-' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            @if ($w['jenis'] === 'penelitian')
                <div class="field" style="margin-top:20px;">
                    <label>Inovasi Produk</label>
                    <textarea name="inovasi_produk" rows="3"
                        placeholder="Uraikan singkat rencana inovasi produk (dalam bentuk teks)...">{{ $w['inovasi_produk'] }}</textarea>
                </div>
            @endif

            <div style="display:flex; justify-content:space-between; margin-top:20px;">
                <a href="{{ route('pengajuan.step3') }}" class="btn btn-outline">Kembali</a>
                <button class="btn btn-primary" type="submit">Selanjutnya</button>
            </div>
        </form>
    </div>

    <script>
        function toggleLuaran(id, table, checkbox) {
            const otherTable = table === 'wajib' ? 'tambahan' : 'wajib';
            const otherCheckbox = document.getElementById(otherTable + '_check_' + id);
            const otherSelect = document.getElementById(otherTable + '_opsi_' + id);
            const ownSelect = document.getElementById(table + '_opsi_' + id);
            const ownRow = document.getElementById('row-' + table + '-' + id);
            const otherRow = document.getElementById('row-' + otherTable + '-' + id);

            if (ownSelect) {
                ownSelect.disabled = !checkbox.checked;
                if (!checkbox.checked) ownSelect.value = '';
            }
            if (ownRow) ownRow.classList.toggle('s4-row-checked', checkbox.checked);
            ownRow?.querySelector('.s4-jenis')?.classList.toggle('on', checkbox.checked);

            if (otherCheckbox) {
                otherCheckbox.disabled = checkbox.checked;
                if (otherRow) otherRow.classList.toggle('s4-row-disabled', checkbox.checked);
                if (checkbox.checked) {
                    otherCheckbox.checked = false;
                    otherRow?.classList.remove('s4-row-checked');
                    otherRow?.querySelector('.s4-jenis')?.classList.remove('on');
                    if (otherSelect) {
                        otherSelect.disabled = true;
                        otherSelect.value = '';
                    }
                }
            }
        }
    </script>
@endsection
