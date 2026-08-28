@extends('layouts.app')

@section('title', $judulHalaman)
@section('crumbs', 'Menu Dosen / ' . $judulHalaman)

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        @keyframes suksesPop {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            60% {
                transform: scale(1.12);
                opacity: 1;
            }

            80% {
                transform: scale(0.95);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes suksesCheck {
            0% {
                transform: scale(0) rotate(-20deg);
                opacity: 0;
            }

            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        @keyframes suksesRise {
            0% {
                opacity: 0;
                transform: translateY(14px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes suksesRing {
            0% {
                box-shadow: 0 0 0 0 rgba(15, 110, 79, .35);
            }

            100% {
                box-shadow: 0 0 0 16px rgba(15, 110, 79, 0);
            }
        }

        .sukses-icon {
            animation: suksesPop .55s cubic-bezier(.34, 1.56, .64, 1) both,
                suksesRing 1.1s ease-out .55s both;
            position: relative;
        }

        .sukses-icon span {
            display: inline-block;
            animation: suksesCheck .4s ease-out .35s both;
        }

        .sukses-fade-1 {
            animation: suksesRise .5s ease-out .55s both;
        }

        .sukses-fade-2 {
            animation: suksesRise .5s ease-out .7s both;
        }

        .sukses-fade-3 {
            animation: suksesRise .5s ease-out .85s both;
        }

        .sukses-fade-4 {
            animation: suksesRise .5s ease-out 1s both;
        }
    </style>

    <div class="card wizard-card" style="text-align:center; padding:48px 24px;">
        <div class="sukses-icon"
            style="width:64px; height:64px; border-radius:16px; background:var(--green-700); color:#fff; display:flex; align-items:center; justify-content:center; font-size:30px; margin:0 auto 18px;">
            <span>✓</span>
        </div>

        <h2 class="sukses-fade-1" style="font-size:19px; font-weight:800; margin-bottom:8px;">{{ $judulHalaman }} berhasil
            dikirim!</h2>

        <div class="sukses-fade-2" style="font-size:13px; color:var(--ink-700); margin-bottom:4px;">
            Kode pengajuan: <b>{{ $pengajuan->kode }}</b> &middot; Jalur {{ ucfirst($pengajuan->jalur) }} &middot; Status:
            <b>Dalam Proses</b>
        </div>

        <div class="sukses-fade-3" style="font-size:12.5px; color:var(--ink-500); max-width:440px; margin:0 auto 26px;">
            {{ $judulHalaman }} kamu sudah masuk ke antrean validasi admin. Kamu akan menerima notifikasi setelah laporan
            ini diperiksa.
        </div>

        <a href="{{ $kembaliUrl }}" class="btn btn-primary sukses-fade-4">{{ $kembaliLabel }}</a>
    </div>
@endsection
