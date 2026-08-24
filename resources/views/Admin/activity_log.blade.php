@extends('layouts.admin')

@section('title', 'Activity Log - SIPPM')

@section('header_title', 'Activity Log')

@section('header_breadcrumb', 'Menu Admin / Activity Log')

@section('content')

<div class="space-y-6">

    {{-- HEADER CARD --}}
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl shadow-lg p-6 md:p-7 text-white relative overflow-hidden">

        {{-- Background Decoration --}}
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full"></div>
        <div class="absolute right-20 -bottom-16 w-48 h-48 bg-white/5 rounded-full"></div>

        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-5">

            <div class="flex items-start gap-4">

                <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6 text-white"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl md:text-2xl font-bold tracking-tight">
                            Riwayat Aktivitas Sistem
                        </h2>

                        <span class="px-2.5 py-1 rounded-full bg-white/10 border border-white/10 text-[10px] font-bold uppercase tracking-wider">
                            SIPPM
                        </span>
                    </div>

                    <p class="text-sm text-slate-300 mt-2 max-w-2xl leading-relaxed">
                        Pantau seluruh aktivitas pengguna dan perubahan yang terjadi
                        dalam Sistem Informasi Penelitian dan Pengabdian kepada Masyarakat.
                    </p>
                </div>

            </div>

            {{-- Total Data --}}
            <div class="bg-white/10 border border-white/10 rounded-xl px-5 py-3 backdrop-blur-sm">

                <p class="text-[10px] uppercase tracking-widest text-slate-300 font-bold">
                    Total Aktivitas
                </p>

                <p class="text-2xl font-extrabold mt-1">
                    {{ $logs->total() }}
                </p>

            </div>

        </div>
    </div>


    {{-- MAIN CONTENT --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- TABLE HEADER --}}
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wide">
                    Daftar Aktivitas
                </h3>

                <p class="text-xs text-slate-400 mt-1">
                    Menampilkan catatan aktivitas terbaru dari seluruh pengguna sistem.
                </p>
            </div>

            {{-- Status --}}
            <div class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-50 border border-emerald-100 rounded-lg">

                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>

                <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">
                    Sistem Aktif
                </span>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[800px] text-left">

                <thead>

                    <tr class="bg-slate-50 border-b border-slate-200">

                        <th class="w-20 px-6 py-4 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                            No
                        </th>

                        <th class="px-6 py-4 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                            Pengguna
                        </th>

                        <th class="px-6 py-4 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                            Aktivitas
                        </th>

                        <th class="px-6 py-4 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                            Waktu
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($logs as $index => $log)

                        @php

                            $userName = $log->user->name ?? 'Sistem / Pengguna';

                            $initial = strtoupper(substr($userName, 0, 1));

                            $tipe = $log->tipe ?? 'warning';

                            $statusConfig = match($tipe) {
                                'success' => [
                                    'dot' => 'bg-emerald-500',
                                    'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'label' => 'Berhasil'
                                ],

                                'danger' => [
                                    'dot' => 'bg-rose-500',
                                    'badge' => 'bg-rose-50 text-rose-700 border-rose-100',
                                    'label' => 'Perhatian'
                                ],

                                'info' => [
                                    'dot' => 'bg-blue-500',
                                    'badge' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'label' => 'Informasi'
                                ],

                                default => [
                                    'dot' => 'bg-amber-500',
                                    'badge' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'label' => 'Aktivitas'
                                ],
                            };

                        @endphp


                        <tr class="group hover:bg-slate-50/80 transition duration-200">

                            {{-- NOMOR --}}
                            <td class="px-6 py-4">

                                <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-white group-hover:shadow-sm border border-transparent group-hover:border-slate-200 flex items-center justify-center transition">

                                    <span class="text-xs font-bold text-slate-500">
                                        {{ $logs->firstItem() + $index }}
                                    </span>

                                </div>

                            </td>


                            {{-- PENGGUNA --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    {{-- Avatar --}}
                                    <div class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center shrink-0 shadow-sm">

                                        <span class="text-xs font-bold text-white">
                                            {{ $initial }}
                                        </span>

                                    </div>

                                    <div>

                                        <p class="text-sm font-bold text-slate-800">
                                            {{ $userName }}
                                        </p>

                                        <p class="text-[11px] text-slate-400 mt-0.5">
                                            Pengguna SIPPM
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- AKTIVITAS --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <span class="w-2.5 h-2.5 rounded-full {{ $statusConfig['dot'] }}"></span>

                                    <div>

                                        <p class="text-sm font-medium text-slate-700">
                                            {{ $log->aktivitas }}
                                        </p>

                                        <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-md border text-[9px] font-bold uppercase tracking-wider {{ $statusConfig['badge'] }}">

                                            {{ $statusConfig['label'] }}

                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- WAKTU --}}
                            <td class="px-6 py-4">

                                @if($log->created_at)

                                    <div>

                                        <p class="text-sm font-semibold text-slate-700">
                                            {{ $log->created_at->format('d M Y') }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="w-3.5 h-3.5"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

                                            </svg>

                                            {{ $log->created_at->format('H:i:s') }} WIB

                                        </p>

                                    </div>

                                @else

                                    <span class="text-sm text-slate-400">
                                        -
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        {{-- EMPTY STATE --}}
                        <tr>

                            <td colspan="4" class="py-16 px-6 text-center">

                                <div class="flex flex-col items-center justify-center">

                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="w-8 h-8 text-slate-400"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="1.5"
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />

                                        </svg>

                                    </div>

                                    <h4 class="text-sm font-bold text-slate-700">
                                        Belum Ada Aktivitas
                                    </h4>

                                    <p class="text-xs text-slate-400 mt-2 max-w-sm">
                                        Aktivitas pengguna yang terjadi di dalam sistem
                                        akan secara otomatis tercatat dan ditampilkan di halaman ini.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- FOOTER / PAGINATION --}}
        @if($logs->hasPages())

            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col md:flex-row items-center justify-between gap-4">

                <p class="text-xs text-slate-400">

                    Menampilkan
                    <span class="font-bold text-slate-600">
                        {{ $logs->firstItem() }}
                    </span>
                    -
                    <span class="font-bold text-slate-600">
                        {{ $logs->lastItem() }}
                    </span>

                    dari

                    <span class="font-bold text-slate-600">
                        {{ $logs->total() }}
                    </span>

                    aktivitas

                </p>

                <div class="pagination-wrapper">

                    {{ $logs->links() }}

                </div>

            </div>

        @endif

    </div>

</div>

@endsection