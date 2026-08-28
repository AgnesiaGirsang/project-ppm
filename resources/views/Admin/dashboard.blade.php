@extends('layouts.admin')

@section('title', 'Dashboard Admin - SIPPM Poltekkes Kemenkes Medan')
@section('header_title', 'Dashboard Overview')
@section('header_breadcrumb', 'Menu Admin / Dashboard')

@section('content')
    <div class="space-y-6 pb-10">

        {{-- WELCOME BANNER / HERO SECTION --}}
        <div
            class="relative overflow-hidden bg-gradient-to-r from-[#0f1d24] via-[#0f2a24] to-[#043828] rounded-3xl shadow-xl p-6 lg:p-8 text-white border border-emerald-900/40">
            <div class="absolute -right-12 -top-12 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none">
            </div>
            <div class="absolute right-32 -bottom-20 w-60 h-60 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-2">
                    <div
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[11px] font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Sistem Informasi Penelitian & Pengabdian
                    </div>
                    <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-white flex items-center gap-2">
                        Selamat Datang, Admin <span class="text-2xl">👋</span>
                    </h1>
                    <p class="text-slate-300 text-xs lg:text-sm max-w-2xl leading-relaxed">
                        Kelola data pengajuan proposal, pantau statistik kegiatan, dan tinjau aktivitas sistem secara
                        real-time dari panel kontrol utama.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('admin.semua-pengajuan') }}"
                        class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-[#059669] hover:bg-[#047857] text-white text-xs font-bold shadow-lg shadow-emerald-950/40 transition-all transform hover:-translate-y-0.5 border border-emerald-500/20">
                        <i class="fa-solid fa-circle-plus text-sm"></i>
                        Kelola Pengajuan
                    </a>
                </div>
            </div>
        </div>

        {{-- STAT CARDS (METRICS) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- Total Pengajuan --}}
            <div
                class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-200/80 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-sky-50 rounded-bl-full -z-0 transition-transform group-hover:scale-110">
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase">Total Pengajuan
                            </p>
                            <h4 class="text-3xl font-black text-slate-900 mt-2 tracking-tight">
                                {{ $stats->total_pengajuan ?? 0 }}</h4>
                        </div>
                        <span
                            class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold shadow-sm border border-sky-100/50">
                            <i class="fa-solid fa-file-lines text-lg"></i>
                        </span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400 font-medium">Keseluruhan data</span>
                        <a href="{{ route('admin.semua-pengajuan') }}"
                            class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1 transition">
                            Detail <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Dalam Proses --}}
            <div
                class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-200/80 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-full -z-0 transition-transform group-hover:scale-110">
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase">Dalam Proses</p>
                            <h4 class="text-3xl font-black text-slate-900 mt-2 tracking-tight">
                                {{ $stats->menunggu_validasi ?? 0 }}</h4>
                        </div>
                        <span
                            class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shadow-sm border border-amber-100/50">
                            <i class="fa-solid fa-clock text-lg"></i>
                        </span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400 font-medium">Menunggu validasi</span>
                        <a href="{{ route('admin.semua-pengajuan', ['status' => 'proses']) }}"
                            class="text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1 transition">
                            Detail <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Disetujui --}}
            <div
                class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-200/80 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -z-0 transition-transform group-hover:scale-110">
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase">Disetujui</p>
                            <h4 class="text-3xl font-black text-slate-900 mt-2 tracking-tight">{{ $stats->disetujui ?? 0 }}
                            </h4>
                        </div>
                        <span
                            class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shadow-sm border border-emerald-100/50">
                            <i class="fa-solid fa-check-circle text-lg"></i>
                        </span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400 font-medium">Proposal lolos</span>
                        <a href="{{ route('admin.semua-pengajuan', ['status' => 'disetujui']) }}"
                            class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 transition">
                            Detail <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Perlu Revisi --}}
            <div
                class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-200/80 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-rose-50 rounded-bl-full -z-0 transition-transform group-hover:scale-110">
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase">Perlu Revisi</p>
                            <h4 class="text-3xl font-black text-slate-900 mt-2 tracking-tight">
                                {{ $stats->revisi_ditolak ?? 0 }}</h4>
                        </div>
                        <span
                            class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold shadow-sm border border-rose-100/50">
                            <i class="fa-solid fa-rotate-right text-lg"></i>
                        </span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400 font-medium">Butuh perbaikan</span>
                        <a href="{{ route('admin.semua-pengajuan', ['status' => 'revisi']) }}"
                            class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1 transition">
                            Detail <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- CHART SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Chart Jenis Kegiatan --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center">
                        <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">Jenis Kegiatan</h3>
                        <span class="text-[10px] bg-slate-100 text-slate-600 font-bold px-2.5 py-1 rounded-lg">Tahun
                            {{ now()->year }}</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Distribusi penelitian & pengabdian</p>
                </div>

                <div class="my-5 flex flex-col items-center justify-center gap-4">
                    <div class="relative w-32 h-32 flex items-center justify-center">
                        <canvas id="chartJenis"></canvas>
                    </div>
                    <div class="w-full grid grid-cols-2 gap-2 mt-2">
                        <div
                            class="flex items-center justify-between gap-2 text-xs font-bold text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <div class="flex items-center gap-1.5 truncate"><span
                                    class="w-2.5 h-2.5 rounded-md bg-emerald-800 shrink-0"></span><span
                                    class="truncate">Penelitian</span></div>
                            <span class="text-slate-900 font-black">{{ $persenPenelitian ?? 0 }}%</span>
                        </div>
                        <div
                            class="flex items-center justify-between gap-2 text-xs font-bold text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <div class="flex items-center gap-1.5 truncate"><span
                                    class="w-2.5 h-2.5 rounded-md bg-emerald-400 shrink-0"></span><span
                                    class="truncate">Pengabdian</span></div>
                            <span class="text-slate-900 font-black">{{ $persenPengabdian ?? 0 }}%</span>
                        </div>
                    </div>
                </div>

                <div
                    class="text-[11px] text-center text-slate-400 font-medium bg-slate-50/50 py-2 rounded-xl border border-slate-100">
                    Persentase berdasarkan total data aktif
                </div>
            </div>

            {{-- Chart Skema --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center">
                        <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">Statistik Skema</h3>
                        <span
                            class="text-[10px] bg-slate-100 text-slate-600 font-bold px-2.5 py-1 rounded-lg">Kategori</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Perbandingan jumlah per skema</p>
                </div>

                <div class="h-44 my-4 relative">
                    <canvas id="chartSkema"></canvas>
                </div>

                <div
                    class="text-[11px] text-center text-slate-400 font-medium bg-slate-50/50 py-2 rounded-xl border border-slate-100">
                    Grafik Batang Distribusi Skema
                </div>
            </div>

            {{-- Chart Tahun --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center">
                        <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">Tren Pengajuan</h3>
                        <span
                            class="text-[10px] bg-slate-100 text-slate-600 font-bold px-2.5 py-1 rounded-lg">2022–2026</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Perkembangan tahunan sistem</p>
                </div>

                <div class="h-44 my-4 relative">
                    <canvas id="chartTahun"></canvas>
                </div>

                <div
                    class="text-[11px] text-center text-slate-400 font-medium bg-slate-50/50 py-2 rounded-xl border border-slate-100">
                    Grafik Tren Lini Masa
                </div>
            </div>

        </div>

        {{-- PENGAJUAN TERBARU (FULL WIDTH) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">Pengajuan Terbaru</h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Daftar proposal penelitian & pengabdian yang baru
                        saja masuk ke sistem.</p>
                </div>
                <a href="{{ route('admin.semua-pengajuan') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-extrabold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-4 py-2.5 rounded-xl transition shrink-0 self-start sm:self-auto">
                    Lihat Semua Pengajuan <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr
                            class="text-slate-400 uppercase tracking-wider font-black border-b border-slate-100 bg-slate-50/50">
                            <th class="py-3.5 px-4 rounded-l-xl">Judul Proposal</th>
                            <th class="py-3.5 px-4">Dosen / Pengusul</th>
                            <th class="py-3.5 px-4">Skema</th>
                            <th class="py-3.5 px-4 text-right rounded-r-xl">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse(($pengajuanTerbaru ?? []) as $p)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-4 font-bold text-slate-900 max-w-md">
                                    <div class="flex items-center gap-2">
                                        {{-- Badge Tipe Dokumen --}}
                                        @php
                                            $badgeTipeColor = match ($p->tipe_label ?? 'Proposal') {
                                                'Proposal' => 'bg-sky-50 text-sky-700 border-sky-200',
                                                'Laporan Kemajuan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Laporan Hasil' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                                            };
                                        @endphp
                                        <span
                                            class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase border {{ $badgeTipeColor }}">
                                            {{ $p->tipe_label ?? 'Proposal' }}
                                        </span>
                                        <span class="truncate" title="{{ $p->nama_judul }}">
                                            {{ $p->nama_judul }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-semibold text-slate-700">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 border border-slate-200/60 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($p->nama_pegawai ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="truncate max-w-[180px]">{{ $p->nama_pegawai }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-medium text-slate-600">
                                    <span
                                        class="bg-slate-100 text-slate-700 px-3 py-1 rounded-lg text-[11px] font-semibold border border-slate-200/50">
                                        {{ $p->nama_skema }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    @php
                                        $statusLower = strtolower($p->status);

                                        if (
                                            str_contains($statusLower, 'revisi') ||
                                            str_contains($statusLower, 'tolak') ||
                                            str_contains($statusLower, 'perbaikan')
                                        ) {
                                            $statusClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                        } elseif (
                                            str_contains($statusLower, 'proses') ||
                                            str_contains($statusLower, 'menunggu')
                                        ) {
                                            $statusClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                        } elseif (
                                            str_contains($statusLower, 'disetujui') ||
                                            str_contains($statusLower, 'setuju')
                                        ) {
                                            $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        } else {
                                            $statusClass = 'bg-slate-100 text-slate-700 border-slate-200';
                                        }
                                    @endphp
                                    <span
                                        class="border px-3.5 py-1.5 rounded-full font-bold text-[10px] uppercase tracking-wider {{ $statusClass }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400 font-medium italic">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="fa-solid fa-folder-open text-3xl text-slate-300"></i>
                                        <p>Belum ada data pengajuan atau laporan terbaru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                <span>Data tersinkronisasi otomatis dengan database</span>
                <span>SIPPM Poltekkes Medan</span>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const emeraldDark = '#065f46';
        const emeraldShades = ['#065f46', '#047857', '#059669', '#10b981', '#34d399', '#6ee7b7'];

        // Chart Jenis Kegiatan (Doughnut)
        new Chart(document.getElementById('chartJenis'), {
            type: 'doughnut',
            data: {
                labels: ['Penelitian', 'Pengabdian'],
                datasets: [{
                    data: [{{ $persenPenelitian ?? 0 }}, {{ $persenPengabdian ?? 0 }}],
                    backgroundColor: ['#065f46', '#34d399'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: {
                            size: 12,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 11
                        },
                        padding: 10,
                        cornerRadius: 8
                    }
                }
            }
        });

        // Chart Skema (Bar)
        new Chart(document.getElementById('chartSkema'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($perSkemaLabels ?? []) !!},
                datasets: [{
                    data: {!! json_encode($perSkemaData ?? []) !!},
                    backgroundColor: emeraldShades,
                    borderRadius: 6,
                    barThickness: 'flex',
                    maxBarThickness: 28,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 9,
                                weight: '600'
                            },
                            color: '#64748b',
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 4
                        }
                    },
                    y: {
                        display: false,
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Chart Tahun (Line)
        new Chart(document.getElementById('chartTahun'), {
            type: 'line',
            data: {
                labels: {!! json_encode($perTahunLabels ?? []) !!},
                datasets: [{
                    data: {!! json_encode($perTahunData ?? []) !!},
                    borderColor: emeraldDark,
                    backgroundColor: 'rgba(6, 95, 70, 0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: emeraldDark,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10,
                                weight: '600'
                            },
                            color: '#64748b'
                        }
                    },
                    y: {
                        display: false,
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endpush
