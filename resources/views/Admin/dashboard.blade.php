@extends('layouts.admin')

@section('title', 'Dashboard Admin - SIPPM Poltekkes Kemenkes Medan')
@section('header_title', 'Dashboard')
@section('header_breadcrumb', 'Menu Admin / Dashboard')

@section('content')
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Pengajuan -->
        <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition border border-slate-200/80">
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold tracking-wider text-slate-600 uppercase">Total Pengajuan</span>
                <span class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-file-lines text-base"></i>
                </span>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-3">{{ $stats->total_pengajuan ?? 0 }}</div>
            <a href="{{ route('admin.semua-pengajuan') }}" class="text-xs font-bold text-slate-600 hover:text-emerald-800 transition mt-2 inline-block">Lihat Semua &rarr;</a>
        </div>

        <!-- Dalam Proses -->
        <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition border border-slate-200/80">
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold tracking-wider text-slate-600 uppercase">Dalam Proses</span>
                <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-clock text-base"></i>
                </span>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-3">{{ $stats->menunggu_validasi ?? 0 }}</div>
            <a href="{{ route('admin.semua-pengajuan', ['status' => 'proses']) }}" class="text-xs font-bold text-slate-600 hover:text-emerald-800 transition mt-2 inline-block">Lihat detail &rarr;</a>
        </div>

        <!-- Disetujui -->
        <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition border border-slate-200/80">
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold tracking-wider text-slate-600 uppercase">Disetujui</span>
                <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-check text-base"></i>
                </span>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-3">{{ $stats->disetujui ?? 0 }}</div>
            <a href="{{ route('admin.semua-pengajuan', ['status' => 'disetujui']) }}" class="text-xs font-bold text-slate-600 hover:text-emerald-800 transition mt-2 inline-block">Lihat detail &rarr;</a>
        </div>

        <!-- Perlu Revisi -->
        <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition border border-slate-200/80">
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold tracking-wider text-slate-600 uppercase">Perlu Revisi</span>
                <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-rotate-right text-base"></i>
                </span>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-3">{{ $stats->revisi_ditolak ?? 0 }}</div>
            <a href="{{ route('admin.semua-pengajuan', ['status' => 'revisi']) }}" class="text-xs font-bold text-slate-600 hover:text-emerald-800 transition mt-2 inline-block">Lihat detail &rarr;</a>
        </div>
    </div>

    <!-- Chart Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mt-5">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <h3 class="font-extrabold text-slate-900 text-sm">Pengajuan per Jenis Kegiatan</h3>
            <p class="text-xs text-slate-500 font-semibold mt-0.5 mb-4">Distribusi tahun {{ now()->year }}</p>
            <div class="flex items-center gap-6">
                <div class="relative w-32 h-32 shrink-0 flex items-center justify-center">
                    <canvas id="chartJenis"></canvas>
                </div>
                <div class="space-y-3 text-xs font-bold text-slate-700">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-800"></span>Penelitian — {{ $persenPenelitian ?? 0 }}%</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-slate-300"></span>Pengabdian — {{ $persenPengabdian ?? 0 }}%</div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <h3 class="font-extrabold text-slate-900 text-sm">Pengajuan per Skema</h3>
            <p class="text-xs text-slate-500 font-semibold mt-0.5 mb-4">Statistik Skema</p>
            <div class="h-44">
                <canvas id="chartSkema"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <h3 class="font-extrabold text-slate-900 text-sm">Pengajuan per Tahun</h3>
            <p class="text-xs text-slate-500 font-semibold mt-0.5 mb-4">2022–2026</p>
            <div class="h-44">
                <canvas id="chartTahun"></canvas>
            </div>
        </div>
    </div>

    <!-- Table & Activity Section (Grid 3 Kolom: 2 untuk Tabel, 1 untuk Aktivitas) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mt-5">
        <!-- Pengajuan Terbaru (Span 2 Kolom) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Pengajuan Terbaru</h3>
                    <p class="text-xs text-slate-400 font-medium">Daftar proposal penelitian & pengabdian yang baru masuk.</p>
                </div>
                <a href="{{ route('admin.semua-pengajuan') }}" class="text-xs font-bold text-emerald-800 hover:underline">Lihat Semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 uppercase tracking-wider font-extrabold border-b border-slate-100">
                            <th class="py-3 px-3">Judul</th>
                            <th class="py-3 px-3">Dosen</th>
                            <th class="py-3 px-3">Skema</th>
                            <th class="py-3 px-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse(($pengajuanTerbaru ?? []) as $p)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-3 font-bold text-slate-900 max-w-xs truncate">{{ $p->judul }}</td>
                                <td class="py-3.5 px-3 font-medium text-slate-700">{{ $p->pegawai->nama ?? '-' }}</td>
                                <td class="py-3.5 px-3 font-medium text-slate-700">{{ $p->skema->nama ?? '-' }}</td>
                                <td class="py-3.5 px-3 text-right">
                                    <span class="bg-slate-100 text-slate-800 px-3 py-1 rounded-full font-bold text-[10px]">{{ ucfirst($p->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 font-medium italic">Belum ada data pengajuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Log Aktivitas Terbaru (Span 1 Kolom) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Aktivitas Sistem</h3>
                    <p class="text-xs text-slate-400 font-medium">Log riwayat aktivitas terbaru.</p>
                </div>
            </div>
            
            <div class="space-y-4 pt-1">
                @forelse(($aktivitasTerbaru ?? []) as $aktif)
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-100 last:border-0 last:pb-0">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 font-bold text-xs mt-0.5">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <div class="text-xs">
                            <p class="font-bold text-slate-800">{{ $aktif->deskripsi ?? 'Aktivitas baru tercatat' }}</p>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $aktif->created_at ? $aktif->created_at->diffForHumans() : 'Baru saja' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-400 font-medium text-xs italic">
                        <i class="fa-solid fa-history text-2xl mb-2 text-slate-300 block"></i>
                        Belum ada aktivitas tercatat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const emeraldDark = '#065f46';
        const emeraldShades = ['#065f46', '#047857', '#059669', '#10b981', '#34d399'];

        new Chart(document.getElementById('chartJenis'), {
            type: 'doughnut',
            data: {
                labels: ['Penelitian', 'Pengabdian'],
                datasets: [{
                    data: [{{ $persenPenelitian ?? 0 }}, {{ $persenPengabdian ?? 0 }}],
                    backgroundColor: ['#065f46', '#10b981'],
                    borderWidth: 0,
                }]
            },
            options: { cutout: '72%', plugins: { legend: { display: false } } }
        });

        new Chart(document.getElementById('chartSkema'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($perSkemaLabels ?? []) !!},
                datasets: [{
                    data: {!! json_encode($perSkemaData ?? []) !!},
                    backgroundColor: emeraldShades,
                    borderRadius: 6,
                }]
            },
            options: { plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { display: false } } }
        });

        new Chart(document.getElementById('chartTahun'), {
            type: 'line',
            data: {
                labels: {!! json_encode($perTahunLabels ?? []) !!},
                datasets: [{
                    data: {!! json_encode($perTahunData ?? []) !!},
                    borderColor: emeraldDark,
                    tension: 0.35,
                    borderWidth: 3,
                }]
            },
            options: { plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { display: false } } }
        });
    </script>
@endpush