@extends('layouts.admin')

@section('title', 'Notifikasi - SIPPM')
@section('header_title', 'Notifikasi')
@section('header_breadcrumb', 'Menu Admin / Notifikasi')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    
    <!-- Bagian Filter Kategori & Tombol Tandai Semua Dibaca -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-slate-100">
        <div class="flex items-center gap-2 flex-wrap">
            @php $currentFilter = request('filter', 'semua'); @endphp
            
            <a href="{{ route('admin.notifikasi', ['filter' => 'semua']) }}" 
               class="px-5 py-2 rounded-full text-xs font-bold transition {{ $currentFilter == 'semua' ? 'bg-[#022c22] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
               Semua
            </a>
            <a href="{{ route('admin.notifikasi', ['filter' => 'pengajuan']) }}" 
               class="px-5 py-2 rounded-full text-xs font-bold transition {{ $currentFilter == 'pengajuan' ? 'bg-[#022c22] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
               Pengajuan
            </a>
            <a href="{{ route('admin.notifikasi', ['filter' => 'validasi']) }}" 
               class="px-5 py-2 rounded-full text-xs font-bold transition {{ $currentFilter == 'validasi' ? 'bg-[#022c22] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
               Validasi
            </a>
            <a href="{{ route('admin.notifikasi', ['filter' => 'laporan']) }}" 
               class="px-5 py-2 rounded-full text-xs font-bold transition {{ $currentFilter == 'laporan' ? 'bg-[#022c22] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
               Laporan
            </a>
        </div>

        <form action="{{ route('admin.notifikasi.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs font-bold text-slate-600 hover:text-emerald-800 flex items-center gap-1.5 transition">
                <i class="fa-solid fa-check text-emerald-600"></i> Tandai semua dibaca
            </button>
        </form>
    </div>

    <!-- Daftar List Notifikasi -->
    <div class="space-y-4">
        @forelse($notifications as $notif)
            <div class="p-4 rounded-xl border {{ is_null($notif->read_at) ? 'bg-emerald-50/30 border-emerald-100/80' : 'bg-white border-slate-100' }} flex items-start gap-3.5 transition hover:shadow-sm">
                <!-- Titik Indikator Hijau/Abu-abu -->
                <div class="mt-1.5 flex-shrink-0">
                    <span class="w-2.5 h-2.5 rounded-full {{ is_null($notif->read_at) ? 'bg-emerald-600 ring-4 ring-emerald-100' : 'bg-slate-300' }} block"></span>
                </div>
                
                <!-- Konten Teks Notifikasi -->
                <div class="flex-grow">
                    <h3 class="text-xs font-extrabold text-slate-900">{{ $notif->title }}</h3>
                    <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                    <span class="block text-[11px] text-slate-400 mt-2 font-medium">
                        {{ $notif->created_at ? $notif->created_at->translatedFormat('d M Y H:i') : '' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-bell-slash text-lg"></i>
                </div>
                <p class="text-sm font-bold text-slate-700">Tidak ada notifikasi</p>
                <p class="text-xs text-slate-400 mt-0.5">Belum ada pemberitahuan baru yang masuk pada kategori ini.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection