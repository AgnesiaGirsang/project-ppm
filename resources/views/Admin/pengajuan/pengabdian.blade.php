@extends('layouts.admin')

@section('title', 'Pengajuan Pengabdian - SIPPM Poltekkes Kemenkes Medan')
@section('header_title', 'Pengajuan Pengabdian')
@section('header_breadcrumb', 'Menu Admin / Pengajuan / Pengabdian')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
        <div class="mb-6">
            <h2 class="text-base font-extrabold text-slate-900">Pengajuan Pengabdian kepada Masyarakat</h2>
            <p class="text-xs text-slate-500 font-semibold mt-0.5">Kelola seluruh pengajuan kegiatan pengabdian dosen.</p>
        </div>

        <form method="GET" action="{{ route('admin.pengabdian') }}" class="grid grid-cols-1 md:grid-cols-7 gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul kegiatan..."
                class="md:col-span-2 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 font-medium focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 bg-slate-50/50">

            <select name="dosen"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-white text-slate-700 font-semibold focus:outline-none focus:border-emerald-700">
                <option value="">Nama Dosen</option>
                @foreach ($listDosen ?? [] as $dosen)
                    <option value="{{ $dosen->nama }}" {{ request('dosen') == $dosen->nama ? 'selected' : '' }}>
                        {{ $dosen->nama }}</option>
                @endforeach
            </select>

            <select name="skema"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-white text-slate-700 font-semibold focus:outline-none focus:border-emerald-700">
                <option value="">Skema</option>
                @foreach ($listSkema ?? [] as $skema)
                    <option value="{{ $skema->id }}" {{ request('skema') == $skema->id ? 'selected' : '' }}>
                        {{ $skema->nama }}</option>
                @endforeach
            </select>

            <select name="jalur"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-white text-slate-700 font-semibold focus:outline-none focus:border-emerald-700">
                <option value="">Jalur</option>
                <option value="Simlitabkes" {{ request('jalur') == 'Simlitabkes' ? 'selected' : '' }}>Simlitabkes</option>
                <option value="Mandiri" {{ request('jalur') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
            </select>

            <select name="status"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-white text-slate-700 font-semibold focus:outline-none focus:border-emerald-700">
                <option value="">Status</option>
                <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Dalam Proses</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Direvisi</option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-emerald-800 text-white rounded-xl text-xs font-bold py-2.5 hover:bg-emerald-900 transition shadow-sm">Cari</button>
                <a href="{{ route('admin.pengabdian') }}"
                    class="flex-1 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold py-2.5 hover:bg-slate-200 transition text-center flex items-center justify-center">Reset</a>
            </div>
        </form>

        <div class="overflow-x-auto -mx-2">
            <table class="w-full text-left border-collapse text-xs min-w-[800px]">
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
                    @forelse($pengajuans as $index => $item)
                        @php
                            $badgeColor = match (strtolower($item->status)) {
                                'disetujui' => 'bg-emerald-100 text-emerald-800',
                                'revisi', 'direvisi' => 'bg-rose-100 text-rose-800',
                                default => 'bg-amber-100 text-amber-800',
                            };
                            $statusText = match (strtolower($item->status)) {
                                'disetujui' => 'Disetujui',
                                'revisi' => 'Direvisi',
                                'proses' => 'Dalam Proses',
                                default => ucfirst($item->status),
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-3 font-semibold text-slate-600">{{ $pengajuans->firstItem() + $index }}</td>
                            <td class="py-4 px-3 font-extrabold text-slate-900">{{ $item->judul }}</td>
                            <td class="py-4 px-3 font-semibold text-slate-700">{{ $item->pegawai->nama ?? '-' }}</td>
                            <td class="py-4 px-3 font-medium text-slate-700">{{ $item->skema->nama ?? '-' }}</td>
                            <td class="py-4 px-3 font-medium text-slate-600">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="py-4 px-3">
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-bold {{ $item->jalur == 'Simlitabkes' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $item->jalur }}
                                </span>
                            </td>
                            <td class="py-4 px-3">
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-bold {{ $badgeColor }} shadow-sm whitespace-nowrap inline-block">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.pengajuan.dokumen', $item->id) }}" target="_blank"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-100 text-slate-700 hover:bg-emerald-800 hover:text-white transition shadow-sm"
                                        title="Lihat Dokumen Proposal">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.pengajuan.download', $item->id) }}"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-100 text-slate-700 hover:bg-emerald-800 hover:text-white transition shadow-sm"
                                        title="Download Dokumen Proposal">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400 font-medium">Belum ada data pengajuan
                                pengabdian yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div
            class="flex justify-between items-center pt-6 border-t border-slate-200 mt-4 text-xs text-slate-500 font-semibold">
            <span>Menampilkan {{ $pengajuans->firstItem() ?? 0 }} sampai {{ $pengajuans->lastItem() ?? 0 }} dari
                {{ $pengajuans->total() }} data</span>
            <div>
                {{ $pengajuans->links() }}
            </div>
        </div>
    </div>
@endsection
