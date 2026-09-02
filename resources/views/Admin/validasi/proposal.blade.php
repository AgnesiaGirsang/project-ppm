@extends('layouts.admin')

@section('title', 'Daftar Validasi Proposal - SIPPM Poltekkes Kemenkes Medan')
@section('header_title', 'Validasi Proposal')
@section('header_breadcrumb', 'Menu Admin / Validasi / Proposal')

@section('content')
    <div class="space-y-6">

        <!-- RINGKASAN STATISTIK KECIL -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Total Berkas Masuk</p>
                    <h4 class="text-xl font-extrabold text-slate-800 mt-1">{{ $pengajuans->total() }} <span
                            class="text-xs font-medium text-slate-500">Berkas</span></h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-folder-open text-sm"></i>
                </div>
            </div>
        </div>

        <!-- KONTROL UTAMA & TABEL -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">

            <!-- HEADER KARTU & FILTER URUTAN -->
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">
                        Daftar Berkas & Riwayat Validasi Proposal
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Kelola, verifikasi, dan pantau status kelayakan proposal
                        penelitian/pengabdian.</p>
                </div>

                <!-- DROPDOWN SORTING -->
                <form method="GET" action="{{ route('admin.validasi.proposal') }}" class="flex items-center gap-2.5">
                    <span class="text-xs font-bold text-slate-500 shrink-0">Urutkan:</span>
                    <select name="sort" onchange="this.form.submit()"
                        class="text-xs border border-slate-200 rounded-xl px-3.5 py-2 bg-slate-50/50 font-semibold text-slate-700 focus:outline-none focus:border-emerald-700 transition shadow-2xs">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru (Paling Baru)
                        </option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama (Paling Lama)
                        </option>
                    </select>
                </form>
            </div>

            <!-- TABEL DATA -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-6 w-16 text-center">No</th>
                            <th class="py-3.5 px-6">Judul Proposal & Skema</th>
                            <th class="py-3.5 px-6">Pengusul / Ketua</th>
                            <th class="py-3.5 px-6">Tanggal Masuk</th>
                            <th class="py-3.5 px-6">Status Validasi</th>
                            <th class="py-3.5 px-6">Divalidasi Oleh</th>
                            <th class="py-3.5 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($pengajuans as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition group">
                                <!-- NOMOR -->
                                <td class="py-4 px-6 text-center font-bold text-slate-400">
                                    {{ $pengajuans->firstItem() + $index }}
                                </td>

                                <!-- JUDUL & SKEMA -->
                                <td class="py-4 px-6 max-w-xs">
                                    <span
                                        class="font-extrabold text-slate-900 block leading-relaxed line-clamp-2 group-hover:text-emerald-900 transition">
                                        {{ $item->judul }}
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1.5 mt-1.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                                        <i class="fa-solid fa-bookmark text-[9px]"></i> {{ $item->skema->nama ?? '-' }}
                                    </span>
                                </td>

                                <!-- PENGUSUL -->
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-900 block">{{ $item->pegawai->nama ?? '-' }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono mt-0.5 block">NIP/NIDN:
                                        {{ $item->pegawai->nip ?? '-' }}</span>
                                </td>

                                <!-- TANGGAL MASUK -->
                                <td class="py-4 px-6 text-slate-600 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 font-semibold">
                                        <i class="fa-regular fa-clock text-slate-400"></i>
                                        {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}
                                    </div>
                                </td>

                                <!-- STATUS BADGE -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    @if ($item->status == 'disetujui')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Disetujui
                                        </span>
                                    @elseif($item->status == 'revisi')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span> Revisi
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Proses
                                        </span>
                                    @endif
                                </td>

                                <!-- DIVALIDASI OLEH -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    @if ($item->divalidasi_oleh)
                                        <span
                                            class="font-bold text-slate-900 block">{{ $item->validator->name ?? 'Admin' }}</span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">
                                            {{ $item->divalidasi_pada ? $item->divalidasi_pada->format('d/m/Y H:i') : '-' }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">Belum divalidasi</span>
                                    @endif
                                </td>

                                <!-- TOMBOL AKSI (VALIDASI) -->
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    <a href="{{ route('admin.validasi.proposal.detail', $item->id) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-900 transition shadow-2xs group-hover:shadow-sm">
                                        <i class="fa-solid fa-clipboard-check text-[11px]"></i> Validasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400 italic">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="fa-regular fa-folder-open text-3xl text-slate-300"></i>
                                        <p>Belum ada data pengajuan proposal yang masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                {{ $pengajuans->links() }}
            </div>

        </div>

    </div>
@endsection
