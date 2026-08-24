@extends('layouts.admin')

@section('title', $title ?? 'Pengajuan Penelitian - SIPPM')

@section('header_title', $title ?? 'Pengajuan Penelitian')
@section('header_breadcrumb', 'Menu Admin / Pengajuan / Penelitian')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
    <div class="mb-6">
        <h2 class="text-base font-extrabold text-slate-900">{{ $title ?? 'Pengajuan Penelitian' }}</h2>
        <p class="text-xs text-slate-500 font-semibold mt-0.5">Kelola seluruh pengajuan kegiatan penelitian dosen.</p>
    </div>

    <!-- Filter & Search Bar Sesuai Gambar Referensi -->
    <form method="GET" action="{{ route('admin.penelitian') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-7 gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul penelitian..." 
               class="md:col-span-2 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 font-medium focus:outline-none focus:border-emerald-700 bg-slate-50/50">
        
        <select name="dosen" class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-white text-slate-700 font-semibold focus:outline-none focus:border-emerald-700">
            <option value="">Nama Dosen</option>
            <!-- Tambahkan option dosen jika diperlukan -->
        </select>

        <select name="skema" class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-white text-slate-700 font-semibold focus:outline-none focus:border-emerald-700">
            <option value="">Skema</option>
        </select>

        <select name="jalur" class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-white text-slate-700 font-semibold focus:outline-none focus:border-emerald-700">
            <option value="">Jalur</option>
            <option value="Simlitabkes" {{ request('jalur') == 'Simlitabkes' ? 'selected' : '' }}>Simlitabkes</option>
            <option value="Mandiri" {{ request('jalur') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
        </select>

        <select name="status" class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-white text-slate-700 font-semibold focus:outline-none focus:border-emerald-700">
            <option value="">Status</option>
            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Dalam Proses</option>
            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Direvisi</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-emerald-800 text-white rounded-xl text-xs font-bold py-2.5 hover:bg-emerald-900 transition shadow-sm">Cari</button>
            <a href="{{ route('admin.penelitian') }}" class="flex-1 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold py-2.5 hover:bg-slate-200 transition flex items-center justify-center">Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto -mx-2">
        <table class="w-full text-left border-collapse text-xs min-w-[700px]" id="tabelPenelitian">
            <thead>
                <tr class="border-b border-slate-200 text-slate-600 uppercase tracking-wider font-extrabold">
                    <th class="py-3 px-3">No</th>
                    <th class="py-3 px-3">Judul</th>
                    <th class="py-3 px-3">Dosen</th>
                    <th class="py-3 px-3">Skema</th>
                    <th class="py-3 px-3">Tgl Pengajuan</th>
                    <th class="py-3 px-3">Jalur</th>
                    <th class="py-3 px-3">Status</th>
                    <th class="py-3 px-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pengajuans as $index => $p)
                    @php
                        [$statusText, $statusClass] = $p->statusLabel();
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4 px-3 font-semibold text-slate-600">
                            {{ $pengajuans->firstItem() + $index }}
                        </td>
                        <td class="py-4 px-3 font-extrabold text-slate-900">
                            {{ $p->judul }}
                        </td>
                        <td class="py-4 px-3 font-semibold text-slate-700">
                            {{ $p->pegawai->nama ?? '-' }}
                        </td>
                        <td class="py-4 px-3 font-medium text-slate-700">
                            {{ $p->skema->nama_skema ?? '-' }}
                        </td>
                        <td class="py-4 px-3 font-medium text-slate-600">
                            {{ $p->created_at ? $p->created_at->format('d/m/Y') : '-' }}
                        </td>
                        <td class="py-4 px-3">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $p->jalur == 'Simlitabkes' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $p->jalur ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-3">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold shadow-sm {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="py-4 px-3 text-center">
                            <!-- Tombol Aksi Ikon Mata Langsung Membuka Dokumen di Tab Baru -->
                            <a href="{{ route('admin.pengajuan.dokumen', $p->id) }}" target="_blank" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-100 text-slate-700 hover:bg-emerald-800 hover:text-white transition shadow-sm" title="Lihat Dokumen Proposal">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400 font-medium italic">
                            Belum ada data pengajuan penelitian yang tersimpan di database.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination & Informasi Jumlah Data -->
    <div class="flex justify-between items-center pt-6 border-t border-slate-200 mt-4 text-xs text-slate-500 font-semibold">
        <span>Menampilkan {{ $pengajuans->firstItem() ?? 0 }} sampai {{ $pengajuans->lastItem() ?? 0 }} dari {{ $pengajuans->total() }} data</span>
        <div>
            {{ $pengajuans->links() }}
        </div>
    </div>
</div>
@endsection