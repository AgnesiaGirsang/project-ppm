@extends('layouts.app')

@section('title', 'Profil Dosen')
@section('crumbs', 'Menu Dosen / Profil')

@section('content')
    <style>
        .profil-grid {
            display: grid;
            grid-template-columns: 1fr 1.3fr 1fr;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .profil-grid {
                grid-template-columns: 1fr;
            }
        }

        .profil-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 24px;
        }

        .profil-avatar {
            width: 96px;
            height: 96px;
            border-radius: 12px;
            background: #0f6e4f;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            margin: 0 auto 14px auto;
            overflow: hidden;
        }

        .profil-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profil-nama {
            text-align: center;
            font-weight: 700;
            font-size: 16px;
        }

        .profil-role {
            text-align: center;
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 14px;
        }

        .profil-btn-outline {
            display: block;
            margin: 0 auto;
            border: 1px solid #d1d5db;
            background: #fff;
            border-radius: 8px;
            padding: 6px 16px;
            font-size: 13px;
            cursor: pointer;
        }

        .profil-label {
            font-weight: 700;
            font-size: 14px;
            margin-top: 16px;
        }

        .profil-label:first-child {
            margin-top: 0;
        }

        .profil-value {
            color: #374151;
            margin-top: 2px;
        }

        .profil-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 8px 12px;
            margin-top: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .profil-btn-primary {
            background: #0f6e4f;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 16px;
        }
    </style>

    <div class="profil-grid">
        {{-- Kartu Foto & Nama --}}
        <div class="profil-card" style="text-align:center;">
            <div class="profil-avatar">
                @if ($pegawai->foto)
                    <img src="{{ Storage::url($pegawai->foto) }}" alt="Foto {{ $pegawai->nama }}">
                @else
                    {{ $pegawai->initials() }}
                @endif
            </div>
            <div class="profil-nama">{{ $pegawai->nama }}</div>
            <div class="profil-role">Dosen &middot; {{ $pegawai->jurusan }}</div>

            <form action="{{ route('profil.foto') }}" method="POST" enctype="multipart/form-data" id="form-foto">
                @csrf
                <input type="file" name="foto" id="input-foto" accept="image/*" style="display:none;"
                    onchange="document.getElementById('form-foto').submit();">
                <button type="button" class="profil-btn-outline" onclick="document.getElementById('input-foto').click();">
                    Ubah Foto
                </button>
            </form>
        </div>

        {{-- Kartu Data Akademik --}}
        <div class="profil-card">
            <div style="font-weight:800; font-size:16px; margin-bottom:6px;">Data Akademik</div>

            <div class="profil-label">NIDN</div>
            <div class="profil-value">{{ $pegawai->nidn ?: '-' }}</div>

            <div class="profil-label">NIP</div>
            <div class="profil-value">{{ $pegawai->nip ?: '-' }}</div>

            <div class="profil-label">Jurusan</div>
            <div class="profil-value">{{ $pegawai->jurusan ?: '-' }}</div>

            <div class="profil-label">Program Studi</div>
            <div class="profil-value">{{ $pegawai->prodi ?: '-' }}</div>

            <div class="profil-label">Jabatan Akademik</div>
            <div class="profil-value">{{ $pegawai->jabatan ?: '-' }}</div>

            <div class="profil-label">Pangkat / Golongan</div>
            <div class="profil-value">{{ $pegawai->pangkat ?: '-' }}</div>
        </div>

        {{-- Kartu Kontak --}}
        <div class="profil-card">
            <div style="font-weight:800; font-size:16px; margin-bottom:6px;">Kontak</div>

            <form action="{{ route('profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                <label class="profil-label" for="email">Email</label>
                <input type="email" name="email" id="email" class="profil-input"
                    value="{{ old('email', $pegawai->email) }}">
                @error('email')
                    <div style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</div>
                @enderror

                <label class="profil-label" for="hp">No. HP</label>
                <input type="text" name="hp" id="hp" class="profil-input"
                    value="{{ old('hp', $pegawai->hp) }}">
                @error('hp')
                    <div style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</div>
                @enderror

                <button type="submit" class="profil-btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection
