<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Pengajuan yang diketuai oleh dosen yang login
        $milikSaya = Pengajuan::where('pegawai_id', $user->id);

        $stats = [
            'total' => (clone $milikSaya)->count(),
            'proses' => (clone $milikSaya)->where('status', 'proses')->count(),
            'disetujui' => (clone $milikSaya)->where('status', 'disetujui')->count(),
            'revisi' => (clone $milikSaya)->where('status', 'revisi')->count(),
        ];

        $riwayatTerbaru = (clone $milikSaya)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($p) => [
                'judul' => $p->judul,
                'jenis' => ucfirst($p->jenis),
                'status' => $p->status,
            ]);

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'riwayatTerbaru' => $riwayatTerbaru,
        ]);
    }
}
