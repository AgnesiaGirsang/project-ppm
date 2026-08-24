@extends('layouts.admin')

@section('title', 'Daftar Validasi Proposal - SIPPM')
@section('header_title', 'Validasi Proposal')
@section('header_breadcrumb', 'Menu Admin / Validasi / Proposal')

@section('content')
@if(session('success'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-4 text-xs font-bold flex items-center gap-2">
    <i class="fa-solid fa-circle-check text-base"></i> {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 space-y-4">
    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <div>
            <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wide">Daftar Berkas Masuk</h3>
            <p class="text-[11px] text-slate-400">Pilih berkas yang ingin divalidasi melalui tombol aksi di tabel bawah.</p>
        </div>
        <span class="text-xs font-bold bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full border border-emerald-200">
            Total Masuk: {{ $pengajuans->total() }} Berkas
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Judul Proposal & Skema</th>
                    <th class="py-3 px-4">Pengusul / Ketua</th>
                    <th class="py-3 px-4">Tanggal Masuk</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                @forelse($pengajuans as $index => $item)
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="py-3.5 px-4 text-slate-400 font-bold">{{ $pengajuans->firstItem() + $index }}</td>
                    <td class="py-3.5 px-4 max-w-xs">
                        <span class="font-bold text-slate-900 block leading-snug">{{ $item->judul }}</span>
                        <span class="text-[10px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 inline-block mt-1">
                            {{ $item->skema->nama ?? 'Skema Tidak Ada' }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4">
                        <!-- Mengambil nama dan nip dari relasi pegawai (atau user) secara aman -->
                        <span class="font-bold block text-slate-800">{{ $item->pegawai->nama ?? $item->user->name ?? 'Tidak Diketahui' }}</span>
                        <span class="text-[10px] text-slate-400">NIP/NIDN: {{ $item->pegawai->nip ?? $item->pegawai->nidn ?? '-' }}</span>
                    </td>
                    <td class="py-3.5 px-4 text-slate-600">
                        {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <span class="bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold px-2.5 py-1 rounded-md inline-block">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <a href="{{ route('admin.validasi.proposal.detail', $item->id) }}" class="bg-[#022c22] hover:bg-emerald-900 text-white font-bold px-3.5 py-2 rounded-xl transition shadow-xs inline-flex items-center gap-1.5 text-[11px]">
                            <i class="fa-solid fa-magnifying-glass"></i> Validasi
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-slate-400 italic">
                        Tidak ada berkas proposal baru yang perlu divalidasi saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginasi -->
    <div class="mt-4">
        {{ $pengajuans->links() }}
    </div>
</div>
@endsection