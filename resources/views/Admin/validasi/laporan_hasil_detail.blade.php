@extends('layouts.admin')

@section('title', $title ?? 'Detail Validasi Laporan Hasil - SIPPM')
@section('header_title', 'Validasi Laporan Hasil')
@section('header_breadcrumb', 'Menu Admin / Validasi / Laporan Hasil / Detail')

@section('content')
    <!-- Tombol Kembali -->
    <div class="mb-4">
        <a href="{{ route('admin.validasi.laporan_hasil') }}"
            class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl hover:bg-slate-50 transition shadow-xs">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- KOLOM KIRI: Informasi Pengajuan, Ringkasan, Luaran, & Dokumen -->
        <div class="lg:col-span-7 space-y-5">

            <!-- Informasi Pengajuan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4 text-xs">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">
                    Informasi Pengajuan</h3>

                <div class="grid grid-cols-1 gap-3.5">
                    <div>
                        <span
                            class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Judul</span>
                        <span
                            class="font-bold text-slate-900 text-xs leading-snug block">{{ $selected->pengajuan->judul ?? '-' }}</span>
                    </div>
                    <div>
                        <span
                            class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Dosen</span>
                        <span
                            class="font-bold text-slate-900 text-xs block">{{ $selected->pengajuan->pegawai->nama ?? '-' }}</span>
                    </div>
                    <div>
                        <span
                            class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Skema</span>
                        <span
                            class="font-bold text-slate-900 text-xs block">{{ $selected->pengajuan->skema->nama ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span
                                class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Tanggal
                                Dikirim</span>
                            <span
                                class="font-bold text-slate-900 text-xs block">{{ $selected->created_at ? $selected->created_at->format('d F Y, H:i') : '-' }}</span>
                        </div>
                        <div>
                            <span
                                class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Status
                                Saat Ini</span>
                            <span
                                class="inline-block mt-0.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">
                                {{ ucfirst($selected->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">No.
                                SK Penelitian & Pengabdian</span>
                            <span class="font-bold text-slate-900 text-xs block">{{ $selected->no_sk ?? '-' }}</span>
                        </div>
                        <div>
                            <span
                                class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Link
                                Inovasi/Produk</span>
                            @if ($selected->link_inovasi_produk)
                                <a href="{{ $selected->link_inovasi_produk }}" target="_blank"
                                    class="font-bold text-emerald-700 text-xs hover:underline block truncate">{{ $selected->link_inovasi_produk }}</a>
                            @else
                                <span class="font-bold text-slate-900 text-xs block">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Hasil -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">
                    Ringkasan Hasil</h3>
                <p class="text-slate-800 leading-relaxed whitespace-pre-line">{{ $selected->ringkasan_hasil ?? '-' }}</p>
            </div>

            <!-- Status Luaran Wajib & Tambahan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">
                    Status Luaran Wajib & Tambahan</h3>
                <div class="space-y-2.5">
                    @php
                        $luaranTercapai = $selected->luaran_tercapai ?? [];
                        $luaranList = \App\Models\LuaranMaster::where('jenis', $selected->pengajuan->jenis ?? null)
                            ->orderByDesc('wajib')
                            ->orderBy('id')
                            ->get();
                    @endphp
                    @forelse($luaranList as $l)
                        @php $item = $luaranTercapai[$l->id] ?? null; @endphp
                        <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 space-y-1.5">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <i
                                        class="fa-solid {{ $item ? 'fa-circle-check text-emerald-600' : 'fa-circle-xmark text-slate-300' }}"></i>
                                    <span class="font-semibold text-slate-800">{{ $l->nama }}</span>
                                </div>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $l->wajib ? 'bg-rose-50 text-rose-600' : 'bg-sky-50 text-sky-600' }} uppercase">
                                    {{ $l->wajib ? 'Wajib' : 'Tambahan' }}
                                </span>
                            </div>
                            @if ($item && !empty($item['link']))
                                <div class="pl-6 text-[11px] text-slate-500">
                                    Bukti: <span class="font-semibold text-slate-700">{{ $item['link'] }}</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-slate-400">Tidak ada data luaran.</p>
                    @endforelse
                </div>
            </div>

            <!-- Dokumen Laporan Hasil (PDF) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">
                    Dokumen Laporan Hasil</h3>
                @if ($selected->file_path)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="text-rose-500 text-lg"><i class="fa-solid fa-file-pdf"></i></div>
                            <div>
                                <span
                                    class="font-bold text-slate-800 block text-xs">{{ $selected->file_nama_asli ?? basename($selected->file_path) }}</span>
                                <span
                                    class="text-[10px] text-slate-400">{{ $selected->file_size ? number_format($selected->file_size / 1024, 1) . ' KB' : '' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-xs font-bold">
                            <a href="{{ route('berkas.show', ['path' => $selected->file_path]) }}" target="_blank"
                                class="text-emerald-700 hover:underline">Preview</a>
                            <a href="{{ route('berkas.show', ['path' => $selected->file_path, 'download' => 1]) }}"
                                class="text-slate-600 hover:underline">Download</a>
                        </div>
                    </div>
                @else
                    <p class="text-slate-400">Belum ada dokumen yang diunggah.</p>
                @endif
            </div>

            <!-- Dokumentasi Kegiatan (Foto) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">
                    Dokumentasi Kegiatan</h3>
                @if (!empty($selected->dokumentasi))
                    <div class="grid grid-cols-3 gap-3">
                        @foreach ($selected->dokumentasi as $foto)
                            <a href="{{ route('berkas.show', ['path' => $foto['path'] ?? '']) }}" target="_blank"
                                class="block rounded-xl overflow-hidden border border-slate-100 aspect-square bg-slate-50">
                                <img src="{{ route('berkas.show', ['path' => $foto['path'] ?? '']) }}"
                                    alt="{{ $foto['nama'] ?? 'dokumentasi' }}" class="w-full h-full object-cover">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-400">Belum ada foto dokumentasi yang diunggah.</p>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: Form Keputusan Validasi -->
        <div class="lg:col-span-5 space-y-5">

            <form action="{{ route('admin.validasi.laporan_hasil.update', $selected->id) }}" method="POST"
                class="space-y-5">
                @csrf

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4 text-xs">
                    <h3
                        class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">
                        Keputusan Validasi</h3>

                    <div class="space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="radio" name="keputusan" value="setuju"
                                class="mt-0.5 w-4 h-4 text-emerald-700 border-slate-300 focus:ring-emerald-600 accent-emerald-700"
                                {{ $selected->status == 'disetujui' ? 'checked' : '' }} required>
                            <div>
                                <span class="font-bold text-slate-800 block">Setujui Laporan</span>
                                <span class="text-[11px] text-slate-400">Laporan hasil dinyatakan valid, kegiatan dinyatakan
                                    selesai</span>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="radio" name="keputusan" value="revisi"
                                class="mt-0.5 w-4 h-4 text-emerald-700 border-slate-300 focus:ring-emerald-600 accent-emerald-700"
                                {{ $selected->status == 'revisi' ? 'checked' : '' }} required>
                            <div>
                                <span class="font-bold text-slate-800 block">Perlu Revisi</span>
                                <span class="text-[11px] text-slate-400">Laporan dikembalikan untuk diperbaiki dosen</span>
                            </div>
                        </label>
                    </div>

                    <div class="space-y-1.5 pt-2">
                        <label class="block font-semibold text-slate-700 text-xs">Catatan Revisi <span
                                class="text-slate-400 font-normal">(opsional, isi jika perlu revisi)</span></label>
                        <textarea name="catatan" rows="3" placeholder="Tuliskan catatan revisi untuk dosen..."
                            class="w-full border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-emerald-700 bg-slate-50/50 resize-none transition">{{ $selected->catatan_validator ?? '' }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 bg-[#047857] text-white font-bold py-2.5 px-4 rounded-xl hover:bg-emerald-800 transition shadow-sm text-xs flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Keputusan
                        </button>
                        <a href="{{ route('admin.validasi.laporan_hasil') }}"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition text-center">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
