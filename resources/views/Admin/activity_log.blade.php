@extends('layouts.admin')

@section('title', 'Activity Log - SIPPM Poltekkes Kemenkes Medan')
@section('header_title', 'Activity Log')
@section('header_breadcrumb', 'Menu Admin / Activity Log')

@section('content')

    <div class="space-y-6 pb-10">

        {{-- HEADER CARD (EMERALD THEME) --}}
        <div
            class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 rounded-2xl shadow-xl p-6 md:p-8 text-white relative overflow-hidden">

            {{-- Background Decoration --}}
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/5 rounded-full blur-xl"></div>
            <div class="absolute right-32 -bottom-20 w-56 h-56 bg-emerald-500/10 rounded-full blur-2xl"></div>

            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center shadow-inner shrink-0">
                        <i class="fa-solid fa-clock-rotate-left text-xl text-emerald-300"></i>
                    </div>

                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-xl md:text-2xl font-black tracking-tight">
                                Riwayat Aktivitas Sistem
                            </h2>
                            <span
                                class="px-3 py-1 rounded-full bg-emerald-500/30 border border-emerald-400/30 text-[10px] font-extrabold uppercase tracking-wider text-emerald-200">
                                SIPPM System
                            </span>
                        </div>

                        <p class="text-xs md:text-sm text-emerald-100/80 mt-1.5 max-w-2xl leading-relaxed font-medium">
                            Pantau seluruh aktivitas pengguna dan perubahan data secara real-time di dalam Sistem Informasi
                            Penelitian dan Pengabdian kepada Masyarakat.
                        </p>
                    </div>
                </div>

                {{-- Total Data Badge --}}
                <div
                    class="bg-white/10 border border-white/15 rounded-2xl px-5 py-3.5 backdrop-blur-md flex items-center gap-4 shrink-0 shadow-sm">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-emerald-200 font-extrabold">
                            Total Aktivitas Tercatat
                        </p>
                        <p class="text-2xl font-black mt-0.5 text-white">
                            {{ $logs->total() }} <span class="text-xs font-normal text-emerald-200">Log</span>
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-300 border border-emerald-400/20">
                        <i class="fa-solid fa-database text-sm"></i>
                    </div>
                </div>

            </div>
        </div>


        {{-- MAIN CONTENT TABLE SECTION --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">

            {{-- TABLE HEADER --}}
            <div
                class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-50/50">
                <div>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                        Daftar Log Aktivitas
                    </h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                        Menampilkan catatan aktivitas terbaru dari seluruh pengguna sistem.
                    </p>
                </div>

                {{-- Status Sistem --}}
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-200/60 rounded-xl shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] font-extrabold text-emerald-700 uppercase tracking-wider">
                        Server Monitoring Aktif
                    </span>
                </div>
            </div>


            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left border-collapse text-xs">
                    <thead>
                        <tr
                            class="bg-slate-50/80 border-b border-slate-200 text-slate-400 uppercase tracking-wider font-black">
                            <th class="w-20 px-6 py-4">No</th>
                            <th class="px-6 py-4">Pengguna Sistem</th>
                            <th class="px-6 py-4">Detail Aktivitas</th>
                            <th class="px-6 py-4">Waktu Eksekusi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($logs as $index => $log)
                            @php
                                $userName = $log->user->name ?? 'Administrator';
                                $initial = strtoupper(substr($userName, 0, 1));
                                $tipe = $log->tipe ?? 'warning';

                                $statusConfig = match ($tipe) {
                                    'success' => [
                                        'dot' => 'bg-emerald-500',
                                        'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'label' => 'Berhasil',
                                    ],
                                    'danger' => [
                                        'dot' => 'bg-rose-500',
                                        'badge' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'label' => 'Perhatian',
                                    ],
                                    'info' => [
                                        'dot' => 'bg-sky-500',
                                        'badge' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'label' => 'Informasi',
                                    ],
                                    default => [
                                        'dot' => 'bg-amber-500',
                                        'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'label' => 'Aktivitas',
                                    ],
                                };
                            @endphp

                            <tr class="group hover:bg-slate-50/70 transition duration-200">

                                {{-- NOMOR --}}
                                <td class="px-6 py-4">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-slate-100 group-hover:bg-emerald-50 group-hover:text-emerald-700 border border-slate-200/60 group-hover:border-emerald-200 flex items-center justify-center transition font-bold text-slate-600 text-xs shadow-2xs">
                                        {{ $logs->firstItem() + $index }}
                                    </div>
                                </td>

                                {{-- PENGGUNA --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-emerald-900 text-emerald-200 flex items-center justify-center shrink-0 shadow-sm font-bold text-xs border border-emerald-800">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900">
                                                {{ $userName }}
                                            </p>
                                            <p
                                                class="text-[10px] font-semibold text-slate-400 mt-0.5 uppercase tracking-wider">
                                                Pengguna Sistem
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- AKTIVITAS --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="w-2.5 h-2.5 rounded-full {{ $statusConfig['dot'] }} mt-1.5 shrink-0"></span>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-800 leading-relaxed">
                                                {{ $log->aktivitas }}
                                            </p>
                                            <span
                                                class="inline-flex items-center mt-1.5 px-2.5 py-0.5 rounded-full border text-[9px] font-black uppercase tracking-wider {{ $statusConfig['badge'] }}">
                                                {{ $statusConfig['label'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- WAKTU --}}
                                <td class="px-6 py-4">
                                    @if ($log->created_at)
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">
                                                {{ $log->created_at->format('d M Y') }}
                                            </p>
                                            <p
                                                class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1 font-medium">
                                                <i class="fa-regular fa-clock text-[10px]"></i>
                                                {{ $log->created_at->format('H:i:s') }} WIB
                                            </p>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">-</span>
                                    @endif
                                </td>

                            </tr>

                        @empty
                            {{-- EMPTY STATE --}}
                            <tr>
                                <td colspan="4" class="py-16 px-6 text-center bg-slate-50/30">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-xl shadow-xs">
                                            <i class="fa-regular fa-folder-open"></i>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-800 mt-1">Belum Ada Aktivitas Tercatat</h4>
                                        <p class="text-[11px] text-slate-400 max-w-sm">
                                            Aktivitas yang dilakukan oleh pengguna di dalam sistem akan otomatis muncul di
                                            halaman ini secara real-time.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            {{-- FOOTER / PAGINATION --}}
            @if ($logs->hasPages())
                <div
                    class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500">
                    <div>
                        Menampilkan
                        <span class="text-slate-900 font-bold">{{ $logs->firstItem() }}</span> -
                        <span class="text-slate-900 font-bold">{{ $logs->lastItem() }}</span>
                        dari
                        <span class="text-slate-900 font-bold">{{ $logs->total() }}</span> total aktivitas
                    </div>
                    <div class="pagination-wrapper">
                        {{ $logs->links() }}
                    </div>
                </div>
            @endif

        </div>

    </div>

@endsection
