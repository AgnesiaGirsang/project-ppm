@extends('layouts.admin')

@section('title', 'Detail Validasi Proposal - SIPPM')
@section('header_title', 'Validasi Proposal')
@section('header_breadcrumb', 'Menu Admin / Validasi / Proposal / Detail')

@section('content')
<!-- Tombol Kembali -->
<div class="mb-4">
    <a href="{{ route('admin.validasi.proposal') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl hover:bg-slate-50 transition shadow-xs">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    
    <!-- KOLOM KIRI: Informasi Pengajuan, Tim Peneliti, & Dokumen Proposal -->
    <div class="lg:col-span-7 space-y-5">
        
        <!-- Informasi Pengajuan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4 text-xs">
            <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">Informasi Pengajuan</h3>

            <div class="grid grid-cols-1 gap-3.5">
                <div>
                    <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Kode Pengajuan</span>
                    <span class="font-bold text-slate-900 text-xs">{{ $selected->kode_pengajuan ?? 'PNL-2026-00001' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Judul</span>
                    <span class="font-bold text-slate-900 text-xs leading-snug block">{{ $selected->judul }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Dosen</span>
                    <span class="font-bold text-slate-900 text-xs block">{{ $selected->pegawai->nama ?? $selected->user->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Skema</span>
                    <span class="font-bold text-slate-900 text-xs block">{{ $selected->skema->nama ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Jenis Kegiatan</span>
                        <span class="font-bold text-slate-900 text-xs block">{{ $selected->jenis_kegiatan ?? 'Penelitian' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Jalur</span>
                        <span class="font-bold text-slate-900 text-xs block">{{ $selected->jalur ?? 'Simlitabkes' }}</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Tahun Anggaran</span>
                        <span class="font-bold text-slate-900 text-xs block">{{ $selected->tahun_anggaran ?? '2026' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Total Biaya</span>
                        <span class="font-bold text-slate-900 text-xs block">Rp {{ number_format($selected->total_biaya ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Tanggal Pengajuan</span>
                        <span class="font-bold text-slate-900 text-xs block">{{ $selected->created_at ? $selected->created_at->format('d F Y, H:i') : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Status Saat Ini</span>
                        <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">
                            {{ ucfirst($selected->status ?? 'Revisi') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tim Peneliti -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
            <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">Tim Peneliti</h3>
            <div class="space-y-2.5">
                <div class="flex items-center gap-3 p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user text-xs"></i>
                    </div>
                    <div>
                        <span class="font-bold text-slate-800 block text-xs">-</span>
                        <span class="text-[10px] text-slate-400 uppercase">ketua</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user text-xs"></i>
                    </div>
                    <div>
                        <span class="font-bold text-slate-800 block text-xs">-</span>
                        <span class="text-[10px] text-slate-400 uppercase">anggota</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dokumen Proposal -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
            <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">Dokumen Proposal</h3>
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="text-rose-500 text-lg">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div>
                        <span class="font-bold text-slate-800 block text-xs">{{ basename($selected->proposal_path ?? 'CV_Yana_BTN_2026.pdf') }}</span>
                        <span class="text-[10px] text-slate-400">150.4 KB</span>
                    </div>
                </div>
                @if($selected->proposal_path)
                <div class="flex items-center gap-3 text-xs font-bold">
                    <a href="{{ asset('storage/' . $selected->proposal_path) }}" target="_blank" class="text-emerald-700 hover:underline">Preview</a>
                    <a href="{{ asset('storage/' . $selected->proposal_path) }}" download class="text-slate-600 hover:underline">Download</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Form Keputusan Validasi -->
    <div class="lg:col-span-5 space-y-5">
        
        <form action="{{ route('admin.validasi.proposal.update', $selected->id) }}" method="POST" class="space-y-5">
            @csrf

            <!-- Keputusan Validasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4 text-xs">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">Keputusan Validasi</h3>
                
                <div class="space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="keputusan" value="setuju" class="mt-0.5 w-4 h-4 text-emerald-700 border-slate-300 focus:ring-emerald-600 accent-emerald-700" {{ $selected->status == 'disetujui' ? 'checked' : '' }} required>
                        <div>
                            <span class="font-bold text-slate-800 block">Setujui Proposal</span>
                            <span class="text-[11px] text-slate-400">Proposal dinyatakan valid dan dapat dilanjutkan</span>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="keputusan" value="revisi" class="mt-0.5 w-4 h-4 text-emerald-700 border-slate-300 focus:ring-emerald-600 accent-emerald-700" {{ $selected->status == 'revisi' ? 'checked' : '' }} required>
                        <div>
                            <span class="font-bold text-slate-800 block">Perlu Revisi</span>
                            <span class="text-[11px] text-slate-400">Proposal dikembalikan untuk diperbaiki</span>
                        </div>
                    </label>
                </div>

                <div class="space-y-1.5 pt-2">
                    <label class="block font-semibold text-slate-700 text-xs">Catatan Revisi <span class="text-slate-400 font-normal">(opsional, isi jika perlu revisi)</span></label>
                    <textarea name="catatan" rows="3" placeholder="Tuliskan catatan revisi untuk dosen..." class="w-full border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-emerald-700 bg-slate-50/50 resize-none transition">{{ $selected->catatan_validator ?? '' }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-[#047857] text-white font-bold py-2.5 px-4 rounded-xl hover:bg-emerald-800 transition shadow-sm text-xs flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Keputusan
                    </button>
                    <a href="{{ route('admin.validasi.proposal') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition text-center">
                        Batal
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection