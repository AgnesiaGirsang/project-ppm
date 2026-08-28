@extends('layouts.admin')

@section('title', $title ?? 'Validasi Laporan Hasil - SIPPM')
@section('header_title', 'Validasi Laporan Hasil')
@section('header_breadcrumb', 'Menu Admin / Validasi / Laporan Hasil')

@section('content')
    <div class="space-y-6">
        <!-- Card Statistik Atas (Sesuai Gambar Referensi) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 w-full sm:w-80">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-400 font-bold block text-[10px] uppercase tracking-wider mb-1">Total Berkas
                        Masuk</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-slate-900">{{ $laporans->total() }}</span>
                        <span class="text-xs font-semibold text-slate-500">Berkas</span>
                    </div>
                </div>
                <div
                    class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700 text-lg shadow-2xs">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
            </div>
        </div>

        <!-- Card Tabel & Daftar Berkas -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4">

            <!-- Header Tabel, Deskripsi & Urutkan -->
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wide">Daftar Berkas & Riwayat
                        Validasi Laporan Hasil</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Kelola, verifikasi, dan pantau status kelayakan
                        laporan hasil penelitian/pengabdian.</p>
                </div>

                <!-- Filter Urutkan -->
                <div class="flex items-center gap-2 self-end sm:self-auto">
                    <span class="text-xs text-slate-400 font-semibold whitespace-nowrap">Urutkan:</span>
                    <select onchange="window.location.href=this.value"
                        class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white focus:outline-none focus:border-emerald-700 transition">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}"
                            {{ request('sort') == 'terbaru' || !request('sort') ? 'selected' : '' }}>Terbaru (Paling Baru)
                        </option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'terlama']) }}"
                            {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr
                            class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <th class="p-3.5">No</th>
                            <th class="p-3.5">Judul Laporan & Skema</th>
                            <th class="p-3.5">Pengusul / Ketua</th>
                            <th class="p-3.5">Tanggal Masuk</th>
                            <th class="p-3.5 text-center">Status Validasi</th>
                            <th class="p-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @forelse($laporans as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition align-middle">
                                <td class="p-3.5 font-bold text-slate-500">{{ $laporans->firstItem() + $index }}</td>

                                <!-- Judul & Skema -->
                                <td class="p-3.5 space-y-1.5 max-w-sm">
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
                                <td class="p-3.5">
                                    <span
                                        class="font-bold text-slate-900 block">{{ $item->pengajuan->pegawai->nama ?? '-' }}</span>
                                    <span class="text-[10px] text-slate-400 block font-normal">NIP/NIDN:
                                        {{ $item->pengajuan->pegawai->nip ?? '-' }}</span>
                                </td>

                                <!-- Tanggal Masuk -->
                                <td class="p-3.5 text-slate-500 font-medium whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-regular fa-clock text-slate-400 text-[11px]"></i>
                                        <span>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</span>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="p-3.5 text-center whitespace-nowrap">
                                    @php
                                        $status = strtolower($item->status);
                                        $statusClass = match ($status) {
                                            'disetujui' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                            'revisi' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                            default => 'bg-amber-50 text-amber-700 border border-amber-200',
                                        };
                                        // Mengubah label 'pending' atau default menjadi 'PROSES' sesuai style gambar
                                        $statusLabel =
                                            $status == 'pending' || $status == 'proses'
                                                ? 'PROSES'
                                                : ucfirst($item->status);
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="p-3.5 text-center whitespace-nowrap">
                                    <a href="{{ route('admin.validasi.laporan_hasil.detail', $item->id) }}"
                                        class="inline-flex items-center gap-1.5 bg-[#047857] hover:bg-emerald-800 text-white font-bold px-3.5 py-2 rounded-xl transition shadow-xs text-xs"
                                        title="Validasi Laporan">
                                        <i class="fa-solid fa-clipboard-check text-[10px]"></i> Validasi
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
    </div>
@endsection
