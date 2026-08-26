@extends('layouts.admin')

@section('title', 'Semua Pengajuan - SIPPM Poltekkes Kemenkes Medan')
@section('header_title', 'Semua Pengajuan')
@section('header_breadcrumb', 'Menu Admin / Pengajuan / Semua Pengajuan')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
        <div class="mb-6">
            <h2 class="text-base font-extrabold text-slate-900">Semua Pengajuan</h2>
            <p class="text-xs text-slate-500 font-semibold mt-0.5">Daftar seluruh pengajuan penelitian dan pengabdian.</p>
        </div>

        <!-- Stat Cards (Terhubung ke Controller) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">Semua</span>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xs"><i
                            class="fa-solid fa-file-lines"></i></div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-extrabold text-slate-900">{{ $totalSemua ?? 0 }}</h3>
                </div>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-extrabold tracking-wider text-amber-600 uppercase">Dalam Proses</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xs"><i
                            class="fa-solid fa-clock"></i></div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-extrabold text-slate-900">{{ $totalProses ?? 0 }}</h3>
                </div>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-extrabold tracking-wider text-emerald-600 uppercase">Disetujui</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-check"></i></div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-extrabold text-slate-900">{{ $totalDisetujui ?? 0 }}</h3>
                </div>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-extrabold tracking-wider text-rose-600 uppercase">Perlu Revisi</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs"><i
                            class="fa-solid fa-rotate-left"></i></div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-extrabold text-slate-900">{{ $totalRevisi ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <form method="GET" action="{{ route('admin.semua-pengajuan') }}"
            class="grid grid-cols-1 md:grid-cols-7 gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul / kode..."
                class="md:col-span-2 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 font-medium focus:outline-none focus:border-emerald-700 bg-slate-50/50">

            <select name="jenis"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-white text-slate-700 font-semibold focus:outline-none focus:border-emerald-700">
                <option value="">Jenis</option>
                <option value="penelitian" {{ request('jenis') == 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                <option value="pengabdian" {{ request('jenis') == 'pengabdian' ? 'selected' : '' }}>Pengabdian</option>
            </select>

            <select name="jalur"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-white text-slate-700 font-semibold focus:outline-none focus:border-emerald-700">
                <option value="">Jalur</option>
                <option value="Simlitabkes" {{ request('jalur') == 'Simlitabkes' ? 'selected' : '' }}>Simlitabkes</option>
                <option value="Mandiri" {{ request('jalur') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
            </select>

            <select name="status"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-white text-slate-700 font-semibold focus:outline-none focus:border-emerald-700">
                <option value="">Status</option>
                <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Dalam Proses</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Direvisi</option>
            </select>

            <input type="text" name="tahun" value="{{ request('tahun') }}" placeholder="Tahun (cth: 2026)"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-900 font-medium focus:outline-none focus:border-emerald-700 bg-slate-50/50">

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-emerald-800 text-white rounded-xl text-xs font-bold py-2.5 hover:bg-emerald-900 transition shadow-sm">Cari</button>
                <a href="{{ route('admin.semua-pengajuan') }}"
                    class="flex-1 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold py-2.5 hover:bg-slate-200 transition flex items-center justify-center">Reset</a>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto -mx-2">
            <table class="w-full text-left border-collapse text-xs min-w-[800px]" id="tabelSemuaPengajuan">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-600 uppercase tracking-wider font-extrabold">
                        <th class="py-3 px-3">Kode</th>
                        <th class="py-3 px-3">Judul</th>
                        <th class="py-3 px-3">Dosen</th>
                        <th class="py-3 px-3">Jenis</th>
                        <th class="py-3 px-3">Jalur</th>
                        <th class="py-3 px-3">Tgl Pengajuan</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pengajuans as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-3 font-semibold text-slate-600">{{ $item->kode ?? '-' }}</td>
                            <td class="py-4 px-3 font-extrabold text-slate-900">{{ $item->judul }}</td>
                            <td class="py-4 px-3 font-semibold text-slate-700">{{ $item->pegawai->nama ?? '-' }}</td>
                            <td class="py-4 px-3 font-medium text-slate-700 capitalize">{{ $item->jenis ?? '-' }}</td>
                            <td class="py-4 px-3">
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-bold {{ $item->jalur == 'Simlitabkes' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $item->jalur ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-3 font-medium text-slate-600">
                                {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                            <td class="py-4 px-3">
                                @php
                                    $badgeColor = match (strtolower($item->status)) {
                                        'disetujui' => 'bg-emerald-100 text-emerald-800',
                                        'revisi', 'direvisi' => 'bg-rose-100 text-rose-800',
                                        default => 'bg-amber-100 text-amber-800',
                                    };
                                @endphp
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-bold {{ $badgeColor }} shadow-sm capitalize">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.pengajuan.dokumen', $item->id) }}" target="_blank"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-100 text-slate-700 hover:bg-emerald-800 hover:text-white transition shadow-sm"
                                        title="Lihat Dokumen Proposal">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.pengajuan.download', $item->id) }}"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-100 text-slate-700 hover:bg-emerald-800 hover:text-white transition shadow-sm"
                                        title="Download Dokumen Proposal">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 font-medium italic">
                                Belum ada data pengajuan yang tersimpan di database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div
            class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-slate-200 mt-4 text-xs text-slate-500 font-semibold gap-3">
            <span>Menampilkan {{ $pengajuans->firstItem() ?? 0 }} sampai {{ $pengajuans->lastItem() ?? 0 }} dari
                {{ $pengajuans->total() }} data</span>
            <div>
                {{ $pengajuans->links() }}
            </div>
        </div>
    </div>
@endsection
