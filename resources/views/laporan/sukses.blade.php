@extends('layouts.app')

@section('title', $judulHalaman)
@section('crumbs', 'Menu Dosen / ' . $judulHalaman)

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <div class="card wizard-card" style="text-align:center; padding:48px 24px;">
        <div
            style="width:64px; height:64px; border-radius:16px; background:var(--green-700); color:#fff; display:flex; align-items:center; justify-content:center; font-size:30px; margin:0 auto 18px;">
            ✓</div>
        <h2 style="font-size:19px; font-weight:800; margin-bottom:8px;">{{ $judulHalaman }} berhasil dikirim!</h2>
        <div style="font-size:13px; color:var(--ink-700); margin-bottom:4px;">
            Kode pengajuan: <b>{{ $pengajuan->kode }}</b> &middot; Jalur {{ ucfirst($pengajuan->jalur) }} &middot; Status:
            <b>Dalam Proses</b>
        </div>
        <div style="font-size:12.5px; color:var(--ink-500); max-width:440px; margin:0 auto 26px;">
            {{ $judulHalaman }} kamu sudah masuk ke antrean validasi admin. Kamu akan menerima notifikasi setelah laporan
            ini diperiksa.
        </div>
        <a href="{{ $kembaliUrl }}" class="btn btn-primary">{{ $kembaliLabel }}</a>
    </div>
@endsection
