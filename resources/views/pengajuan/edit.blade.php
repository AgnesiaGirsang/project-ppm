@extends('layouts.app')

@section('title', 'Revisi Pengajuan')
@section('crumbs', 'Menu Dosen / Riwayat Pengajuan / Revisi')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">
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

        <form method="POST" action="{{ route('pengajuan.update', $pengajuan) }}" enctype="multipart/form-data">
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
                <div class="check-list">
                    @forelse ($anggotaTersedia as $a)
                        <label>
                            <input type="checkbox" name="tim[]" value="{{ $a->id }}"
                                {{ in_array($a->id, $timTerpilih) ? 'checked' : '' }}>
                            <div class="av">{{ $a->initials() }}</div>
                            <div><b>{{ $a->nama }}</b><br><span style="color:var(--ink-500); font-size:11px;">NIP
                                    {{ $a->nip }} @if ($a->jurusan)
                                        &middot; {{ $a->jurusan }}
                                    @endif
                                </span></div>
                        </label>
                    @empty
                        <div style="padding:16px; text-align:center; color:var(--ink-500); font-size:13px;">Belum ada dosen
                            lain yang terdaftar di sistem.</div>
                    @endforelse
                </div>
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
