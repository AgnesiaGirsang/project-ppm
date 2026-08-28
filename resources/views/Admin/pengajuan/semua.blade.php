@extends('layouts.admin')

@section('title', 'Semua Pengajuan - SIPPM Poltekkes Kemenkes Medan')
@section('header_title', 'Semua Pengajuan')
@section('header_breadcrumb', 'Menu Admin / Pengajuan / Semua Pengajuan')

@section('content')
    <div class="space-y-6 pb-10">

        {{-- HEADER SECTION --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div>
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200/60 text-emerald-700 text-[11px] font-bold uppercase tracking-wider mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Pusat Monitoring Dokumen
                </div>
                <h2 class="text-xl lg:text-2xl font-black text-slate-900 tracking-tight">Manajemen Semua Pengajuan</h2>
                <p class="text-xs text-slate-500 font-medium mt-1">Kelola, pantau, dan verifikasi seluruh proposal penelitian
                    serta pengabdian masyarakat dosen secara terpusat.</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <span
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-extrabold bg-slate-900 text-white shadow-md shadow-slate-900/10">
                    <i class="fa-solid fa-database text-emerald-400"></i> Total: {{ $pengajuans->total() }} Dokumen
                </span>
            </div>
        </div>

        {{-- STAT CARDS (RINGKASAN STATUS) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Semua --}}
            <div
                class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                <div
                    class="absolute top-0 right-0 w-20 h-20 bg-slate-100 rounded-bl-full -z-0 transition-transform group-hover:scale-110">
                </div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase">Semua Pengajuan</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight">{{ $totalSemua ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-11 h-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold shadow-sm border border-slate-200/60">
                        <i class="fa-solid fa-file-lines text-base"></i>
                    </div>
                </div>
                <div
                    class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                    <span>Keseluruhan database</span>
                    <span class="text-slate-700 font-bold">Aktif</span>
                </div>
            </div>

            {{-- Proses --}}
            <div
                class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                <div
                    class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-bl-full -z-0 transition-transform group-hover:scale-110">
                </div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[11px] font-extrabold tracking-wider text-amber-600 uppercase">Dalam Proses</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight">{{ $totalProses ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shadow-sm border border-amber-100/60">
                        <i class="fa-solid fa-clock-rotate-left text-base"></i>
                    </div>
                </div>
                <div
                    class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                    <span>Menunggu validasi</span>
                    <span class="text-amber-600 font-bold">Pending</span>
                </div>
            </div>

            {{-- Disetujui --}}
            <div
                class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                <div
                    class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-bl-full -z-0 transition-transform group-hover:scale-110">
                </div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[11px] font-extrabold tracking-wider text-emerald-600 uppercase">Disetujui</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight">{{ $totalDisetujui ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shadow-sm border border-emerald-100/60">
                        <i class="fa-solid fa-circle-check text-base"></i>
                    </div>
                </div>
                <div
                    class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                    <span>Proposal lolos seleksi</span>
                    <span class="text-emerald-600 font-bold">Valid</span>
                </div>
            </div>

            {{-- Revisi --}}
            <div
                class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                <div
                    class="absolute top-0 right-0 w-20 h-20 bg-rose-50 rounded-bl-full -z-0 transition-transform group-hover:scale-110">
                </div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[11px] font-extrabold tracking-wider text-rose-600 uppercase">Perlu Revisi</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight">{{ $totalRevisi ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold shadow-sm border border-rose-100/60">
                        <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    </div>
                </div>
                <div
                    class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                    <span>Butuh perbaikan</span>
                    <span class="text-rose-600 font-bold">Action</span>
                </div>
            </div>

        </div>

        {{-- FILTER & SEARCH TOOLBAR --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
            <form method="GET" action="{{ route('admin.semua-pengajuan') }}"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-7 gap-3">

                {{-- Search --}}
                <div class="md:col-span-2">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul atau kode..."
                            class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs text-slate-900 font-medium focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10 bg-slate-50/50 transition">
                    </div>
                </div>

                {{-- Jenis --}}
                <div>
                    <select name="jenis"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-slate-50/50 text-slate-700 font-semibold focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10 transition">
                        <option value="">-- Jenis --</option>
                        <option value="penelitian" {{ request('jenis') == 'penelitian' ? 'selected' : '' }}>Penelitian
                        </option>
                        <option value="pengabdian" {{ request('jenis') == 'pengabdian' ? 'selected' : '' }}>Pengabdian
                        </option>
                    </select>
                </div>

                {{-- Jalur --}}
                <div>
                    <select name="jalur"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-slate-50/50 text-slate-700 font-semibold focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10 transition">
                        <option value="">-- Jalur --</option>
                        <option value="Simlitabkes" {{ request('jalur') == 'Simlitabkes' ? 'selected' : '' }}>Simlitabkes
                        </option>
                        <option value="Mandiri" {{ request('jalur') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <select name="status"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-xs bg-slate-50/50 text-slate-700 font-semibold focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10 transition">
                        <option value="">-- Status --</option>
                        <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Dalam Proses</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui
                        </option>
                        <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Direvisi</option>
                    </select>
                </div>

                {{-- Tahun --}}
                <div>
                    <input type="text" name="tahun" value="{{ request('tahun') }}" placeholder="Tahun (cth: 2026)"
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-xs text-slate-900 font-medium focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10 bg-slate-50/50 transition">
                </div>

                {{-- Buttons Action --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-emerald-700 text-white rounded-xl text-xs font-bold py-2.5 hover:bg-emerald-800 transition shadow-md shadow-emerald-700/20 flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-filter text-[10px]"></i> Filter
                    </button>
                    <a href="{{ route('admin.semua-pengajuan') }}"
                        class="w-10 bg-slate-200/80 text-slate-700 rounded-xl text-xs font-bold py-2.5 hover:bg-slate-300 transition flex items-center justify-center shadow-xs"
                        title="Reset Filter">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- TABLE SECTION --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs min-w-[950px]">
                    <thead>
                        <tr
                            class="bg-slate-50/80 border-b border-slate-200 text-slate-400 uppercase tracking-wider font-black">
                            <th class="py-4 px-5">Kode Dokumen</th>
                            <th class="py-4 px-5">Judul Proposal</th>
                            <th class="py-4 px-5">Nama Dosen Pengusul</th>
                            <th class="py-4 px-5">Jenis</th>
                            <th class="py-4 px-5 text-center">Jalur Pembiayaan</th>
                            <th class="py-4 px-5">Tgl Pengajuan</th>
                            <th class="py-4 px-5 text-center">Status</th>
                            <th class="py-4 px-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($pengajuans as $item)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-4 px-5 font-mono font-bold text-slate-500">
                                    <div class="flex flex-col gap-1">
                                        <span
                                            class="bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200/60 w-fit">{{ $item->kode_dokumen ?? '-' }}</span>
                                        {{-- Badge Tipe Dokumen --}}
                                        @php
                                            $badgeTipeColor = match ($item->tipe_dokumen ?? 'Proposal') {
                                                'Proposal' => 'bg-sky-50 text-sky-700 border-sky-200',
                                                'Laporan Kemajuan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Laporan Hasil' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                                            };
                                        @endphp
                                        <span
                                            class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase border {{ $badgeTipeColor }} w-fit">
                                            {{ $item->tipe_dokumen ?? 'Proposal' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-5 font-bold text-slate-900 max-w-xs truncate"
                                    title="{{ $item->judul_dokumen }}">
                                    {{ $item->judul_dokumen }}
                                </td>
                                <td class="py-4 px-5 font-semibold text-slate-700">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60 flex items-center justify-center font-bold text-[10px] shrink-0">
                                            {{ strtoupper(substr($item->nama_pengusul ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="truncate max-w-[160px]">{{ $item->nama_pengusul }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-5 font-medium text-slate-600 capitalize">
                                    <span class="inline-flex items-center gap-1.5 font-bold text-slate-700">
                                        <i
                                            class="fa-solid {{ $item->jenis_dokumen == 'penelitian' ? 'fa-flask text-sky-600' : 'fa-hands-holding-child text-indigo-600' }}"></i>
                                        {{ $item->jenis_dokumen ?? '-' }}
                                    </span>
                                </td>

                                {{-- Jalur Pembiayaan --}}
                                <td class="py-4 px-5 text-center">
                                    @if (strtolower($item->jalur_dokumen) == 'simlitabkes')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200/60 shadow-2xs">
                                            <i class="fa-solid fa-award mr-1"></i> Simlitabkes
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200/60 shadow-2xs">
                                            <i class="fa-solid fa-user-shield mr-1"></i> Mandiri
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-5 font-medium text-slate-500">
                                    {{ isset($item->created_at) && $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                                </td>

                                {{-- Badge Status --}}
                                <td class="py-4 px-5 text-center">
                                    @php
                                        $statusLower = strtolower($item->status ?? '');
                                        if (
                                            str_contains($statusLower, 'revisi') ||
                                            str_contains($statusLower, 'ditolak') ||
                                            str_contains($statusLower, 'perbaikan')
                                        ) {
                                            $badgeColor = 'bg-rose-50 text-rose-700 border-rose-200';
                                        } elseif (
                                            str_contains($statusLower, 'disetujui') ||
                                            str_contains($statusLower, 'setuju')
                                        ) {
                                            $badgeColor = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        } else {
                                            $badgeColor = 'bg-amber-50 text-amber-700 border-amber-200';
                                        }
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold border {{ $badgeColor }} uppercase tracking-wider">
                                        {{ ucfirst($item->status ?? 'Proses') }}
                                    </span>
                                </td>

                                {{-- Tombol Aksi --}}
                                <td class="py-4 px-5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @php
                                            $tipe = $item->tipe_dokumen ?? 'Proposal';

                                            // Menentukan rute berdasarkan tipe dokumen secara dinamis
                                            if ($tipe == 'Proposal') {
                                                $routeView = route('admin.pengajuan.dokumen', $item->id);
                                                $routeDownload = route('admin.pengajuan.download', $item->id);
                                            } elseif ($tipe == 'Laporan Kemajuan') {
                                                $routeView = route('admin.laporan-kemajuan.dokumen', $item->id);
                                                $routeDownload = route('admin.laporan-kemajuan.download', $item->id);
                                            } else {
                                                $routeView = route('admin.laporan-hasil.dokumen', $item->id);
                                                $routeDownload = route('admin.laporan-hasil.download', $item->id);
                                            }
                                        @endphp

                                        {{-- Tombol Lihat / Pratinjau --}}
                                        <a href="{{ $routeView }}" target="_blank"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-emerald-700 hover:text-white transition shadow-2xs"
                                            title="Pratinjau Dokumen {{ $tipe }}">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>

                                        {{-- Tombol Unduh / Download --}}
                                        <a href="{{ $routeDownload }}"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-emerald-700 hover:text-white transition shadow-2xs"
                                            title="Unduh Dokumen {{ $tipe }}">
                                            <i class="fa-solid fa-download text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"
                                    class="py-16 text-center text-slate-400 font-medium italic bg-slate-50/30">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-300 flex items-center justify-center text-xl">
                                            <i class="fa-regular fa-folder-open"></i>
                                        </div>
                                        <p class="text-xs text-slate-500 font-bold">Tidak ada data pengajuan atau laporan
                                            yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div
                class="flex flex-col sm:flex-row justify-between items-center p-5 border-t border-slate-100 bg-slate-50/50 text-xs text-slate-500 font-semibold gap-3">
                <div>
                    Menampilkan
                    <span class="text-slate-900 font-bold">{{ $pengajuans->firstItem() ?? 0 }}</span>
                    sampai
                    <span class="text-slate-900 font-bold">{{ $pengajuans->lastItem() ?? 0 }}</span>
                    dari
                    <span class="text-slate-900 font-bold">{{ $pengajuans->total() }}</span> total data
                </div>
                <div>
                    {{ $pengajuans->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
