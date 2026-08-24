@extends('layouts.admin')

@section('title', $title ?? 'Detail Validasi Laporan Kemajuan - SIPPM')
@section('header_title', 'Validasi Laporan Kemajuan')
@section('header_breadcrumb', 'Menu Admin / Validasi / Laporan Kemajuan / Detail')

@section('content')
<style>
    .animate-float { animation: floatIcon 2.5s ease-in-out infinite; }
    @keyframes floatIcon { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-8px); } }
    .custom-radio { appearance: none; -webkit-appearance: none; width: 1.1rem; height: 1.1rem; border: 2px solid #cbd5e1; border-radius: 50%; outline: none; cursor: pointer; display: grid; place-content: center; transition: all 0.2s ease-in-out; background-color: white; }
    .custom-radio::before { content: ""; width: 0.5rem; height: 0.5rem; border-radius: 50%; transform: scale(0); transition: 0.2s transform ease-in-out; background-color: white; }
    .custom-radio:checked { background-color: #047857; border-color: #047857; }
    .custom-radio:checked::before { transform: scale(1); }
</style>

<!-- Tombol Kembali -->
<div class="mb-4">
    <a href="{{ route('admin.validasi.laporan-kemajuan') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl hover:bg-slate-50 transition shadow-xs">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Laporan
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
    
    <!-- Kolom Kiri: Preview Dokumen PDF & Progress -->
    <div class="lg:col-span-7 bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 space-y-3.5">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wide">Preview Dokumen Laporan Kemajuan</h2>
                <p class="text-[11px] text-slate-400 font-medium">{{ $selected->file_nama_asli ?? basename($selected->file_path ?? 'Dokumen tidak tersedia') }}</p>
            </div>
            @php
                $filePath = $selected->file_path ?? null;
            @endphp
            @if($filePath)
            <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg hover:bg-emerald-100 transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-external-link-alt"></i> Buka Tab Baru
            </a>
            @endif
        </div>

        <div class="w-full h-[550px] bg-slate-50 border border-slate-200 rounded-xl flex flex-col items-center justify-center p-4 text-center relative overflow-hidden">
            @if($filePath)
                <iframe src="{{ asset('storage/' . $filePath) }}" class="w-full h-full rounded-lg border-0" frameborder="0"></iframe>
            @else
                <div class="space-y-2">
                    <div class="animate-float inline-block mb-1">
                        <i class="fa-solid fa-file-pdf text-rose-500 text-4xl"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-700">File PDF Laporan Tidak Ditemukan</h3>
                </div>
            @endif
        </div>

        <!-- Progress Bar Capaian dari Model -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-2 text-xs">
            <div class="flex justify-between font-bold text-slate-700">
                <span>Persentase Capaian / Kemajuan</span>
                <span class="text-emerald-700">{{ $selected->persentase ?? 0 }}%</span>
            </div>
            <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                <div class="bg-emerald-600 h-full rounded-full" style="width: {{ $selected->persentase ?? 0 }}%;"></div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Informasi Detail, Checklist & Form Keputusan -->
    <div class="lg:col-span-5 space-y-4">
        
        <!-- Informasi Pengajuan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 space-y-3 text-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wide">Informasi Detail Laporan</h3>
                <span class="text-[10px] bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-1 rounded-md font-bold uppercase">
                    {{ ucfirst($selected->status) }}
                </span>
            </div>

            <div class="space-y-3">
                <div>
                    <span class="text-slate-400 font-semibold block text-[10px] uppercase tracking-wider mb-0.5">Judul Penelitian</span>
                    <span class="font-bold text-slate-900 text-xs leading-snug block">{{ $selected->pengajuan->judul ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase tracking-wider mb-0.5">Dosen Pengusul</span>
                        <span class="font-bold text-slate-900 text-xs block">{{ $selected->pengajuan->pegawai->nama ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase tracking-wider mb-0.5">Skema</span>
                        <span class="font-bold text-slate-900 text-xs block">{{ $selected->pengajuan->skema->nama ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Gabungan Checklist & Keputusan Validasi -->
        <form action="{{ route('admin.validasi.laporan-kemajuan.update', $selected->id) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 space-y-4 text-xs">
            @csrf
            
            <!-- Checklist Validasi Dokumen Laporan -->
            <div>
                <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wide border-b border-slate-100 pb-2.5 mb-3">Checklist Validasi Laporan</h3>
                <div class="space-y-2.5">
                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" name="checklist[]" value="capaian" class="w-4 h-4 text-emerald-700 border-slate-300 rounded focus:ring-emerald-600 accent-emerald-700">
                        <span class="font-semibold text-slate-700">Capaian target sesuai proposal</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" name="checklist[]" value="bukti" class="w-4 h-4 text-emerald-700 border-slate-300 rounded focus:ring-emerald-600 accent-emerald-700">
                        <span class="font-semibold text-slate-700">Bukti luaran/kegiatan terlampir</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" name="checklist[]" value="keuangan" class="w-4 h-4 text-emerald-700 border-slate-300 rounded focus:ring-emerald-600 accent-emerald-700">
                        <span class="font-semibold text-slate-700">Penggunaan anggaran rasional</span>
                    </label>
                </div>
            </div>

            <!-- Form Keputusan -->
            <div class="pt-2 border-t border-slate-100">
                <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wide mb-3">Keputusan Validasi</h3>
                
                <div class="flex items-center gap-5 bg-slate-50 p-3 rounded-xl border border-slate-100 mb-3">
                    <label class="flex items-center gap-2 font-bold text-slate-800 cursor-pointer text-xs">
                        <input type="radio" name="keputusan" value="setuju" class="custom-radio" {{ $selected->status == 'disetujui' ? 'checked' : '' }} required> Valid / Setujui
                    </label>
                    <label class="flex items-center gap-2 font-bold text-slate-800 cursor-pointer text-xs">
                        <input type="radio" name="keputusan" value="revisi" class="custom-radio" {{ $selected->status == 'revisi' ? 'checked' : '' }} required> Perlu Revisi
                    </label>
                </div>

                <div class="space-y-1 mb-4">
                    <label class="block font-semibold text-slate-600 text-[11px]">Catatan Validator</label>
                    <textarea name="catatan" rows="3" placeholder="Masukkan catatan atau instruksi perbaikan..." class="w-full border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-emerald-700 bg-slate-50/50 resize-none transition">{{ $selected->catatan_validator ?? '' }}</textarea>
                </div>

                <button type="submit" class="w-full bg-[#022c22] text-white font-bold py-3 rounded-xl hover:bg-emerald-900 transition shadow-sm text-xs flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Simpan & Kirim Validasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection