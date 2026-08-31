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
                        <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Ketua
                            Peneliti</span>
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
                                class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Tahun
                                Pelaksanaan</span>
                            <span
                                class="font-bold text-slate-900 text-xs block">{{ $selected->pengajuan->tahun_pelaksanaan ?? '-' }}</span>
                        </div>
                        <div>
                            <span
                                class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Status
                                Laporan Hasil</span>
                            <span
                                class="inline-block mt-0.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 uppercase">
                                {{ ucfirst($selected->status) }}
                            </span>
                        </div>
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
                            <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">No.
                                SK Penelitian & Pengabdian</span>
                            <span class="font-bold text-slate-900 text-xs block">{{ $selected->no_sk ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Hasil -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide">
                        Ringkasan Hasil</h3>
                    <span
                        class="text-[10px] font-bold px-2 py-0.5 bg-slate-100 text-slate-500 rounded uppercase">Opsional</span>
                </div>
                <p class="text-slate-800 leading-relaxed whitespace-pre-line">{{ $selected->ringkasan_hasil ?? '-' }}</p>
            </div>

            <!-- Status Luaran Wajib & Tambahan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4 text-xs">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide">
                        Status Luaran Wajib & Tambahan</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">
                        Luaran berikut diambil dari rencana luaran saat pengajuan proposal. Isi tautan bukti untuk setiap
                        luaran yang telah tercapai.
                    </p>
                </div>

                <div class="space-y-3 pt-1">
                    @php
                        $luaranTercapai = $selected->luaran_tercapai ?? [];
                        $luaranList = $selected->pengajuan
                            ->luaran()
                            ->with('luaranMaster')
                            ->orderByDesc('is_wajib')
                            ->get();

                        $totalLuaran = count($luaranList);
                        $jumlahTerpenuhi = 0;
                        foreach ($luaranList as $l) {
                            if (isset($luaranTercapai[$l->id]) && !empty($luaranTercapai[$l->id])) {
                                $jumlahTerpenuhi++;
                            }
                        }
                        $persen = $totalLuaran > 0 ? round(($jumlahTerpenuhi / $totalLuaran) * 100) : 0;
                    @endphp

                    @forelse($luaranList as $l)
                        @php
                            $item = $luaranTercapai[$l->id] ?? null;
                            $isChecked = $item ? true : false;
                            $linkBukti = is_array($item) ? $item['link'] ?? '' : $item;
                        @endphp
                        <div class="p-3.5 bg-slate-50/60 rounded-xl border border-slate-200/70 space-y-2.5">
                            <div class="flex items-center justify-between gap-3">
                                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                    <input type="checkbox" disabled {{ $isChecked ? 'checked' : '' }}
                                        class="w-4 h-4 rounded text-emerald-600 border-slate-300 focus:ring-emerald-500 accent-emerald-600">
                                    <span
                                        class="font-bold text-slate-800 text-xs">{{ $l->luaranMaster->nama ?? '-' }}</span>
                                </label>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded {{ $l->is_wajib ? 'bg-rose-50 text-rose-600' : 'bg-sky-50 text-sky-600' }} uppercase">
                                    {{ $l->is_wajib ? 'Wajib' : 'Tambahan' }}
                                </span>
                            </div>
                            <div
                                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-600 text-xs truncate">
                                {{ !empty($linkBukti) ? $linkBukti : 'Link / nama file bukti luaran' }}
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 italic">Tidak ada data luaran.</p>
                    @endforelse
                </div>

                <!-- Progress Bar Kemajuan Luaran -->
                <div class="pt-2 border-t border-slate-100 space-y-1.5">
                    <div class="flex justify-between font-bold text-[11px]">
                        <span class="text-slate-700 uppercase tracking-wider">Kemajuan Luaran</span>
                        <span class="text-slate-500">{{ $jumlahTerpenuhi }} dari {{ $totalLuaran }} luaran terpenuhi
                            ({{ $persen }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-emerald-600 h-full rounded-full transition-all duration-500"
                            style="width: {{ $persen }}%;"></div>
                    </div>
                </div>
            </div>

            <!-- Dokumen Laporan Hasil (PDF) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
                <h3
                    class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf text-rose-500"></i> Dokumen Laporan Hasil (PDF)
                </h3>
                @if ($selected->file_path)
                    <div
                        class="flex items-center justify-between p-3.5 bg-slate-50/50 rounded-2xl border border-slate-200/80 gap-3">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div
                                class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-base shrink-0 shadow-2xs">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="font-bold text-slate-900 block text-xs truncate"
                                    title="{{ $selected->file_nama_asli ?? basename($selected->file_path) }}">
                                    {{ $selected->file_nama_asli ?? basename($selected->file_path) }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium block mt-0.5">
                                    {{ $selected->file_size ? number_format($selected->file_size / 1024, 1) . ' KB • ' : '' }}Format
                                    PDF siap diverifikasi
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            {{-- Tombol Pratinjau --}}
                            <a href="{{ route('berkas.show', ['path' => $selected->file_path]) }}" target="_blank"
                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 font-bold text-xs transition shadow-2xs">
                                <i class="fa-solid fa-eye text-slate-500 text-[11px]"></i> Pratinjau
                            </a>
                            {{-- Tombol Unduh --}}
                            <a href="{{ route('berkas.show', ['path' => $selected->file_path, 'download' => 1]) }}"
                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs transition shadow-2xs">
                                <i class="fa-solid fa-download text-[11px]"></i> Unduh
                            </a>
                        </div>
                    </div>
                @else
                    <p class="text-slate-400 italic">Belum ada dokumen yang diunggah.</p>
                @endif
            </div>

            <!-- Link Inovasi Produk -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">
                    Link Inovasi / Produk</h3>
                @if ($selected->link_inovasi_produk)
                    <a href="{{ $selected->link_inovasi_produk }}" target="_blank"
                        class="font-bold text-emerald-700 text-xs hover:underline block truncate bg-slate-50/50 border border-slate-200/80 p-3 rounded-xl">{{ $selected->link_inovasi_produk }}</a>
                @else
                    <span class="font-bold text-slate-900 text-xs block">-</span>
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

        <!-- KOLOM KANAN: Form Keputusan Validasi (Gaya Sesuai Gambar Referensi) -->
        <div class="lg:col-span-5 space-y-5 sticky top-5">

            <form action="{{ route('admin.validasi.laporan_hasil.update', $selected->id) }}" method="POST"
                class="space-y-5">
                @csrf

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4 text-xs">
                    <h3
                        class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i> Keputusan Validasi
                    </h3>

                    <div class="space-y-3">
                        <!-- Opsi Setujui -->
                        <label
                            class="flex items-start gap-3 p-3.5 rounded-xl border border-emerald-500 bg-emerald-50/20 cursor-pointer transition">
                            <input type="radio" name="keputusan" value="setuju"
                                class="mt-0.5 w-4 h-4 text-emerald-700 border-slate-300 focus:ring-emerald-600 accent-emerald-700"
                                {{ $selected->status == 'disetujui' ? 'checked' : '' }} required>
                            <div>
                                <span class="font-bold text-slate-900 block text-xs">Setujui Laporan</span>
                                <span class="text-[11px] text-slate-500 leading-snug block mt-0.5">Laporan hasil dinyatakan
                                    valid dan kegiatan selesai.</span>
                            </div>
                        </label>

                        <!-- Opsi Revisi -->
                        <label
                            class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-200 bg-white hover:border-slate-300 cursor-pointer transition">
                            <input type="radio" name="keputusan" value="revisi"
                                class="mt-0.5 w-4 h-4 text-emerald-700 border-slate-300 focus:ring-emerald-600 accent-emerald-700"
                                {{ $selected->status == 'revisi' ? 'checked' : '' }} required>
                            <div>
                                <span class="font-bold text-slate-900 block text-xs">Perlu Revisi</span>
                                <span class="text-[11px] text-slate-500 leading-snug block mt-0.5">Laporan dikembalikan
                                    kepada ketua peneliti untuk diperbaiki.</span>
                            </div>
                        </label>
                    </div>

                    <div class="space-y-1.5 pt-2">
                        <label class="block font-bold text-slate-700 text-xs">Catatan / Catatan Revisi <span
                                class="text-slate-400 font-normal">(opsional)</span></label>
                        <textarea name="catatan" rows="3" placeholder="Tuliskan catatan atau instruksi perbaikan..."
                            class="w-full border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-emerald-700 bg-slate-50/50 resize-none transition">{{ $selected->catatan_validator ?? '' }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl hover:bg-emerald-800 transition shadow-sm text-xs flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Keputusan
                        </button>
                        <a href="{{ route('admin.validasi.laporan_hasil') }}"
                            class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition text-center">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
