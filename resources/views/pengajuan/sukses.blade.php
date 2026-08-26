@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

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

    <div class="card wizard-card">
        @include('pengajuan._stepper', ['current' => 6])

        <div style="text-align:center; padding:40px 20px;">
            <div class="sukses-icon"
                style="width:64px; height:64px; border-radius:14px; background:#0f6e4f; color:#fff; display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 20px auto;">
                <span>&#10003;</span>
            </div>

            <div class="sukses-fade-1" style="font-size:20px; font-weight:800; margin-bottom:8px;">Pengajuan berhasil dikirim!
            </div>

            <div class="sukses-fade-2" style="color:#4b5563; font-size:14px; margin-bottom:6px;">
                Kode pengajuan: <b>{{ $kode }}</b> &middot;
                Jalur {{ $jalur === 'mandiri' ? 'Mandiri' : 'Simlitabkes' }} &middot;
                Status: <b>Dalam Proses</b>.
            </div>

            <div class="sukses-fade-3" style="color:#6b7280; font-size:13px; max-width:520px; margin:0 auto 24px auto;">
                @if ($jalur === 'mandiri')
                    Anda akan menerima notifikasi di setiap tahap: Proposal &rarr; Laporan Hasil.
                @else
                    Anda akan menerima notifikasi di setiap tahap: Proposal &rarr; Laporan Kemajuan &rarr; Laporan Hasil.
                @endif
            </div>

            <a href="{{ route('riwayat') }}" class="btn btn-primary sukses-fade-4">Lihat Riwayat Pengajuan</a>
        </div>
    </div>
@endsection
