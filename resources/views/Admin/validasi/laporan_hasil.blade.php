@extends('layouts.admin')

@section('title', $title ?? 'Validasi Laporan Hasil - SIPPM')
@section('header_title', 'Validasi Laporan Hasil')
@section('header_breadcrumb', 'Menu Admin / Validasi / Laporan Hasil')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 space-y-4">
        <!-- Header Tabel & Total Berkas Masuk -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wide">Daftar Berkas Masuk</h3>
                <p class="text-[11px] text-slate-400 font-medium">Pilih berkas yang ingin divalidasi melalui tombol aksi di
                    tabel bawah.</p>
            </div>
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold px-3.5 py-1.5 rounded-xl shadow-2xs">
                Total Masuk: {{ $laporans->total() }} Berkas
            </div>
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
                        <th class="p-3 text-center">Status</th>
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
                                        class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-200/80 px-2.5 py-0.5 rounded-md text-[10px] font-bold">
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
                                {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}
                            </td>

                            <!-- Status -->
                            <td class="p-3 text-center whitespace-nowrap">
                                <span
                                    class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase
                            {{ $item->status == 'disetujui' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($item->status == 'revisi' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <!-- Tombol Aksi -->
                            <td class="p-3 text-center whitespace-nowrap">
                                <a href="{{ route('admin.validasi.laporan_hasil.detail', $item->id) }}"
                                    class="inline-flex items-center gap-1.5 bg-[#022c22] hover:bg-emerald-900 text-white font-bold px-3.5 py-2 rounded-xl transition shadow-xs text-xs"
                                    title="Validasi Laporan">
                                    <i class="fa-solid fa-magnifying-glass text-[10px]"></i> Validasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 font-medium">
                                <div class="flex flex-col items-center justify-center space-y-1">
                                    <i class="fa-solid fa-folder-open text-slate-300 text-3xl mb-1"></i>
                                    <span>Belum ada berkas laporan hasil yang masuk.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginasi -->
        <div class="pt-3">
            {{ $laporans->links() }}
        </div>
    </div>
@endsection
