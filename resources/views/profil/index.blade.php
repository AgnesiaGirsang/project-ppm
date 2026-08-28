@extends('layouts.app')

@section('title', 'Profil Dosen')
@section('crumbs', 'Menu Dosen / Profil')

@section('content')
    <style>
        /* ==== Profil Dosen — scoped styles ==== */
        .pf-grid {
            display: grid;
            grid-template-columns: 0.9fr 1.3fr 1fr;
            gap: 18px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .pf-grid {
                grid-template-columns: 1fr;
            }
        }

        .pf-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #eef2f0;
            box-shadow: 0 1px 3px rgba(16, 24, 40, .04);
            overflow: hidden;
        }

        /* --- Kartu identitas --- */
        .pf-identity-head {
            background: linear-gradient(120deg, #0b3d2e 0%, #0f5c44 45%, #00875A 100%);
            padding: 28px 20px 46px;
            text-align: center;
            position: relative;
        }

        .pf-avatar-wrap {
            position: relative;
            width: 96px;
            height: 96px;
            margin: 0 auto -38px;
        }

        .pf-avatar {
            width: 96px;
            height: 96px;
            border-radius: 16px;
            background: #fff;
            color: #00875A;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 800;
            overflow: hidden;
            border: 4px solid #fff;
            box-shadow: 0 6px 16px rgba(11, 61, 46, .35);
        }

        .pf-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pf-avatar-edit {
            position: absolute;
            bottom: -4px;
            right: -4px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #00875A;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            border: 3px solid #fff;
            cursor: pointer;
            transition: transform .15s ease, background .15s ease;
        }

        .pf-avatar-edit:hover {
            background: #0b3d2e;
            transform: scale(1.08);
        }

        .pf-identity-body {
            padding: 48px 20px 26px;
            text-align: center;
        }

        .pf-nama {
            font-weight: 800;
            font-size: 16.5px;
            color: #111827;
        }

        .pf-role {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #047857;
            background: #d1fae5;
            padding: 4px 12px;
            border-radius: 20px;
            margin-top: 8px;
            font-weight: 700;
        }

        .pf-role .dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #10b981;
        }

        /* --- Kartu data akademik --- */
        .pf-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 14.5px;
            color: #111827;
            padding: 18px 20px 4px;
        }

        .pf-section-title .ic {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: #e3f7ee;
            color: #00875A;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .pf-akademik-list {
            padding: 6px 20px 20px;
        }

        .pf-akademik-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f3f5f4;
        }

        .pf-akademik-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .pf-akademik-item .aic {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            flex: none;
            background: #f4fbf7;
            color: #00875A;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .pf-akademik-item .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 800;
            color: #9ca3af;
        }

        .pf-akademik-item .value {
            font-size: 13.5px;
            font-weight: 600;
            color: #1f2937;
            margin-top: 2px;
        }

        /* --- Kartu kontak --- */
        .pf-form {
            padding: 6px 20px 22px;
        }

        .pf-field {
            margin-bottom: 16px;
        }

        .pf-field label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 800;
            color: #374151;
            margin-bottom: 6px;
        }

        .pf-field input {
            width: 100%;
            box-sizing: border-box;
            border: 1.5px solid #e2ece7;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 13.5px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .pf-field input:focus {
            outline: none;
            border-color: #00875A;
            box-shadow: 0 0 0 4px rgba(0, 135, 90, .12);
        }

        .pf-error {
            color: #dc2626;
            font-size: 11.5px;
            margin-top: 4px;
            font-weight: 600;
        }

        .pf-btn-primary {
            width: 100%;
            background: linear-gradient(120deg, #0b3d2e, #00875A);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px 18px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 14px -4px rgba(11, 61, 46, .45);
            transition: transform .15s ease;
        }

        .pf-btn-primary:hover {
            transform: translateY(-2px);
        }
    </style>

    <div class="pf-grid">
        {{-- Kartu Foto & Nama --}}
        <div class="pf-card">
            <div class="pf-identity-head">
                <div class="pf-avatar-wrap">
                    <div class="pf-avatar">
                        @if ($pegawai->foto)
                            <img src="{{ Storage::url($pegawai->foto) }}" alt="Foto {{ $pegawai->nama }}">
                        @else
                            {{ $pegawai->initials() }}
                        @endif
                    </div>
                    <form action="{{ route('profil.foto') }}" method="POST" enctype="multipart/form-data" id="form-foto">
                        @csrf
                        <input type="file" name="foto" id="input-foto" accept="image/*" style="display:none;"
                            onchange="document.getElementById('form-foto').submit();">
                        <div class="pf-avatar-edit" title="Ubah foto"
                            onclick="document.getElementById('input-foto').click();">📷</div>
                    </form>
                </div>
            </div>
            <div class="pf-identity-body">
                <div class="pf-nama">{{ $pegawai->nama }}</div>
                <div class="pf-role"><span class="dot"></span>Dosen &middot; {{ $pegawai->jurusan }}</div>
            </div>
        </div>

        {{-- Kartu Data Akademik --}}
        <div class="pf-card">
            <div class="pf-section-title"><span class="ic">🎓</span> Data Akademik</div>
            <div class="pf-akademik-list">
                <div class="pf-akademik-item">
                    <div class="aic">🆔</div>
                    <div>
                        <div class="label">NIDN</div>
                        <div class="value">{{ $pegawai->nidn ?: '-' }}</div>
                    </div>
                </div>
                <div class="pf-akademik-item">
                    <div class="aic">🪪</div>
                    <div>
                        <div class="label">NIP</div>
                        <div class="value">{{ $pegawai->nip ?: '-' }}</div>
                    </div>
                </div>
                <div class="pf-akademik-item">
                    <div class="aic">🏛️</div>
                    <div>
                        <div class="label">Jurusan</div>
                        <div class="value">{{ $pegawai->jurusan ?: '-' }}</div>
                    </div>
                </div>
                <div class="pf-akademik-item">
                    <div class="aic">📘</div>
                    <div>
                        <div class="label">Program Studi</div>
                        <div class="value">{{ $pegawai->prodi ?: '-' }}</div>
                    </div>
                </div>
                <div class="pf-akademik-item">
                    <div class="aic">🏅</div>
                    <div>
                        <div class="label">Jabatan Akademik</div>
                        <div class="value">{{ $pegawai->jabatan ?: '-' }}</div>
                    </div>
                </div>
                <div class="pf-akademik-item">
                    <div class="aic">📊</div>
                    <div>
                        <div class="label">Pangkat / Golongan</div>
                        <div class="value">{{ $pegawai->pangkat ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Kontak --}}
        <div class="pf-card">
            <div class="pf-section-title"><span class="ic">✉️</span> Kontak</div>
            <form action="{{ route('profil.update') }}" method="POST" class="pf-form">
                @csrf
                @method('PUT')

                <div class="pf-field">
                    <label for="email">📧 Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $pegawai->email) }}">
                    @error('email')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="pf-field">
                    <label for="hp">📱 No. HP</label>
                    <input type="text" name="hp" id="hp" value="{{ old('hp', $pegawai->hp) }}">
                    @error('hp')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="pf-btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection
