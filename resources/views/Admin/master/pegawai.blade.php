@extends('admin.dashboard')

@section('title', 'Data Pegawai - SIPPM')
@section('header_title', 'Data Pegawai')
@section('header_breadcrumb', 'Menu Admin / Master Data / Data Pegawai')

@section('content')
<style>
    .btn-gradient-animate {
        background: linear-gradient(135deg, #065f46 0%, #047857 100%);
        transition: all 0.3s ease;
    }
    .btn-gradient-animate:hover {
        background: linear-gradient(135deg, #047857 0%, #064e3b 100%);
        box-shadow: 0 6px 20px rgba(4, 120, 87, 0.25);
        transform: translateY(-1px);
    }
    .custom-card-shadow {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }

    /* Styling Modern & Profesional untuk Pagination */
    .pagination-modern nav {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .pagination-modern nav svg {
        width: 1.1rem;
        height: 1.1rem;
    }
    .pagination-modern nav span[aria-current="page"] span,
    .pagination-modern nav a,
    .pagination-modern nav span[aria-disabled="true"] span {
        font-size: 0.8rem !important;
        font-weight: 500;
        padding: 0.45rem 0.85rem !important;
        border-radius: 0.75rem !important;
        border: 1px solid #e5e7eb !important;
        background-color: #ffffff;
        color: #4b5563;
        transition: all 0.25s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }
    .pagination-modern nav span[aria-current="page"] span {
        background: linear-gradient(135deg, #065f46 0%, #047857 100%) !important;
        border-color: transparent !important;
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(4, 120, 87, 0.25);
    }
    .pagination-modern nav a:hover {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border-color: #a7f3d0 !important;
        transform: translateY(-1px);
    }
    .pagination-modern nav span[aria-disabled="true"] span {
        background-color: #f9fafb !important;
        color: #9ca3af !important;
        border-color: #f3f4f6 !important;
        box-shadow: none !important;
    }
</style>

<div class="container-fluid px-6 py-8">
    <!-- Header Page with Accent (Disamakan dengan Master Luaran & Skema) -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-gray-100 gap-4">
        <div>
            <div class="flex items-center gap-2 text-emerald-600 font-semibold text-xs tracking-wider uppercase mb-1">
                <i class="fa-solid fa-layer-group"></i> Master Data Sistem
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Data Pegawai</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data seluruh pegawai dan lakukan import massal via Excel.</p>
        </div>

        <div class="flex items-center gap-3 self-start md:self-auto">
            <a href="{{ route('admin.master.pegawai.template') }}" class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200 transition flex items-center gap-2">
                <i class="fa-solid fa-download text-gray-500"></i> Unduh Template
            </a>
            <button onclick="document.getElementById('modalImport').classList.remove('hidden')" class="px-5 py-2.5 btn-gradient-animate text-white text-xs font-semibold rounded-xl shadow-md flex items-center justify-center gap-2">
                <i class="fa-solid fa-file-excel text-emerald-300"></i> Import Excel
            </button>
        </div>
    </div>

    <!-- Main Card Container -->
    <div class="bg-white rounded-2xl custom-card-shadow border border-gray-100 overflow-hidden">
        
        <!-- Notifikasi Sukses / Error -->
        @if(session('success'))
            <div class="m-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="m-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabel Data Pegawai -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/75 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6">NIP / NIDN</th>
                        <th class="py-4 px-6">Nama Lengkap</th>
                        <th class="py-4 px-6">Jabatan / Pangkat</th>
                        <th class="py-4 px-6">Jurusan / Prodi</th>
                        <th class="py-4 px-6">Kontak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm text-gray-600">
                    @forelse($pegawais as $index => $pegawai)
                        <tr class="hover:bg-emerald-50/30 transition duration-150 group">
                            <td class="py-4 px-6 font-medium text-gray-500">{{ $pegawais->firstItem() + $index }}</td>
                            <td class="py-4 px-6 font-bold text-gray-900">
                                {{ $pegawai->nip }}
                                @if($pegawai->nidn)
                                    <span class="block text-[11px] text-gray-400 font-normal">NIDN: {{ $pegawai->nidn }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-900">
                                {{ $pegawai->nama }}
                                <span class="block text-[11px] text-emerald-700 font-semibold uppercase">Role: {{ $pegawai->role }}</span>
                            </td>
                            <td class="py-4 px-6">
                                {{ $pegawai->jabatan ?? '-' }}
                                <span class="block text-[11px] text-gray-400">{{ $pegawai->pangkat ?? '' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                {{ $pegawai->jurusan ?? '-' }}
                                <span class="block text-[11px] text-gray-400">Prodi: {{ $pegawai->prodi ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                {{ $pegawai->email ?? '-' }}
                                <span class="block text-[11px] text-gray-400">HP: {{ $pegawai->hp ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400">
                                <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-users-slash text-lg"></i>
                                </div>
                                <p class="font-bold text-gray-700 text-sm">Belum ada data pegawai</p>
                                <p class="text-xs text-gray-400 mt-0.5">Silakan import data pegawai menggunakan file Excel.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="flex flex-col md:flex-row justify-between items-center px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-xs text-gray-500 gap-4">
            <div>
                Menampilkan <span class="font-semibold text-gray-700">{{ $pegawais->firstItem() ?? 0 }}</span> sampai <span class="font-semibold text-gray-700">{{ $pegawais->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-700">{{ $pegawais->total() }}</span> data
            </div>
            <div class="pagination-modern">
                {{ $pegawais->onEachSide(1)->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Pop-up Import Excel -->
<div id="modalImport" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100">
        <div class="bg-emerald-900 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="text-sm font-bold flex items-center gap-2"><i class="fa-solid fa-file-excel"></i> Import Data Pegawai</h3>
            <button onclick="document.getElementById('modalImport').classList.add('hidden')" class="text-emerald-200 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.master.pegawai.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pilih File Excel (.xlsx / .csv)</label>
                <input type="file" name="file" accept=".xlsx, .xls, .csv" required class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 border border-gray-200 rounded-xl p-1.5">
                <p class="text-[11px] text-gray-400 mt-1.5">Kolom excel yang didukung: <b>nip, nama, password, role, jabatan, pangkat, jurusan, prodi, email, hp, nidn</b>.</p>
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modalImport').classList.add('hidden')" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-xs font-bold">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 btn-gradient-animate text-white rounded-xl text-xs font-bold shadow-md">
                    Upload & Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection