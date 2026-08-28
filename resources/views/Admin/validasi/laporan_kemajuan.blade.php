@extends('layouts.admin')

@section('title', $title ?? 'Validasi Laporan Kemajuan - SIPPM')
@section('header_title', 'Validasi Laporan Kemajuan')
@section('header_breadcrumb', 'Menu Admin / Validasi / Laporan Kemajuan')

@section('content')
    <div class="space-y-6">

        <!-- CARD TOTAL BERKAS MASUK (Sesuai Desain Halaman Proposal) -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-5 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">Total Berkas
                    Masuk</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black text-slate-900">{{ $laporans->total() }}</span>
                    <span class="text-xs font-bold text-slate-500">Berkas</span>
                </div>
            </div>
            <div
                class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-base border border-emerald-100 shadow-2xs">
                <i class="fa-solid fa-folder-open"></i>
            </div>
        </div>

        <!-- KONTEN UTAMA TABEL -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-5 space-y-4">

            <!-- Header Tabel & Filter Urutan -->
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wide">Daftar Berkas & Riwayat
                        Validasi Laporan Kemajuan</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Kelola, verifikasi, dan pantau status kelayakan
                        laporan kemajuan penelitian/pengabdian.</p>
                </div>

                <!-- Filter Sorting Terbaru / Terlama -->
                <form method="GET" action="{{ route('admin.validasi.laporan-kemajuan') }}"
                    class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500 whitespace-nowrap">Urutkan:</span>
                    <select name="sort" onchange="this.form.submit()"
                        class="text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-700 shadow-2xs">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru (Paling Baru)
                        </option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </form>
            </div>

            <!-- Tabel Data -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr
                            class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <th class="p-3">No</th>
                            <th class="p-3">Judul Laporan & Skema</th>
                            <th class="p-3">Pengusul / Ketua</th>
                            <th class="p-3">Tanggal Masuk</th>
                            <th class="p-3 text-center">Status Validasi</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @forelse($laporans as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition align-middle">
                                <td class="p-3 font-bold text-slate-500">{{ $laporans->firstItem() + $index }}</td>

                                <!-- Judul & Skema -->
                                <td class="p-3 space-y-1.5 max-w-sm">
                                    <span class="font-bold text-slate-900 text-xs leading-snug block uppercase">
                                        {{ $item->pengajuan->judul ?? '-' }}
                                    </span>
                                    <div>
                                        <span
                                            class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200/80 px-2.5 py-0.5 rounded-md text-[10px] font-bold">
                                            <i class="fa-solid fa-bookmark text-[9px]"></i>
                                            {{ $item->pengajuan->skema->nama ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Pengusul & NIP -->
                                <td class="p-3">
                                    <span
                                        class="font-bold text-slate-900 block">{{ $item->pengajuan->pegawai->nama ?? '-' }}</span>
                                    <span class="text-[10px] text-slate-400 block font-normal">NIP/NIDN:
                                        {{ $item->pengajuan->pegawai->nip ?? '-' }}</span>
                                </td>

                                <!-- Tanggal Masuk -->
                                <td class="p-3 text-slate-500 font-medium whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-regular fa-clock text-slate-400 text-[11px]"></i>
                                        {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}
                                    </div>
                                </td>

                                <!-- Status Validasi -->
                                <td class="p-3 text-center whitespace-nowrap">
                                    @php
                                        $status = strtolower($item->status);
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider
                                {{ $status == 'disetujui' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($status == 'revisi' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        {{ $status == 'disetujui' ? 'DISETUJUI' : ($status == 'revisi' ? 'REVISI' : 'PROSES') }}
                                    </span>
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="p-3 text-center whitespace-nowrap">
                                    <a href="{{ route('admin.validasi.laporan-kemajuan.detail', $item->id) }}"
                                        class="inline-flex items-center gap-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2 rounded-full transition shadow-xs text-xs"
                                        title="Validasi Laporan">
                                        <i class="fa-solid fa-square-check text-[10px]"></i> Validasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400 font-medium">
                                    <div class="flex flex-col items-center justify-center space-y-1">
                                        <i class="fa-solid fa-folder-open text-slate-300 text-3xl mb-1"></i>
                                        <span>Belum ada berkas laporan kemajuan yang masuk.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi (Otomatis muncul jika data lebih dari 10 per halaman) -->
            <div class="pt-3">
                {{ $laporans->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
