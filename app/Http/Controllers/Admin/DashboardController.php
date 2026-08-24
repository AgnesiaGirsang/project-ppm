<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Skema;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard Admin';

        $stats = (object) [
            'total_pengajuan' => Pengajuan::count(),
            'menunggu_validasi' => Pengajuan::where('status', 'proses')->count(),
            'disetujui' => Pengajuan::where('status', 'disetujui')->count(),
            'revisi_ditolak' => Pengajuan::where('status', 'revisi')->count(),
        ];

        $jumlahPenelitian = Pengajuan::where('jenis', 'penelitian')->count();
        $jumlahPengabdian = Pengajuan::where('jenis', 'pengabdian')->count();
        $totalJenis = $jumlahPenelitian + $jumlahPengabdian;

        $persenPenelitian = $totalJenis > 0 ? round(($jumlahPenelitian / $totalJenis) * 100) : 0;
        $persenPengabdian = $totalJenis > 0 ? round(($jumlahPengabdian / $totalJenis) * 100) : 0;

        $skemaData = Skema::withCount('pengajuan')->get();
        $perSkemaLabels = $skemaData->pluck('nama')->toArray();
        $perSkemaData = $skemaData->pluck('pengajuan_count')->toArray();

        $perTahunLabels = ['2022', '2023', '2024', '2025', '2026'];
        $perTahunData = [];
        foreach ($perTahunLabels as $tahun) {
            $perTahunData[] = Pengajuan::whereYear('created_at', $tahun)->count();
        }

        $pengajuanTerbaru = Pengajuan::with(['pegawai', 'skema'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'title',
            'stats',
            'persenPenelitian',
            'persenPengabdian',
            'perSkemaLabels',
            'perSkemaData',
            'perTahunLabels',
            'perTahunData',
            'pengajuanTerbaru'
        ));
    }
}