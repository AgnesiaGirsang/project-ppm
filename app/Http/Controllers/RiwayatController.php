<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Pengajuan::with(['skema'])
            ->where(function ($sub) use ($user) {
                $sub->where('pegawai_id', $user->id)
                    ->orWhereHas('tim', fn ($q) => $q->where('pegawai_id', $user->id));
            });

        // Filter tab status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // Filter jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter jalur
        if ($request->filled('jalur')) {
            $query->where('jalur', $request->jalur);
        }

        // Pencarian judul/kode
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn ($sub) => $sub->where('judul', 'like', "%{$q}%")->orWhere('kode', 'like', "%{$q}%"));
        }

        $daftar = $query->latest()->paginate(10)->withQueryString();

        // Hitung jumlah per status buat badge di tab (tanpa filter status biar akurat)
        $baseQuery = fn () => Pengajuan::where(function ($sub) use ($user) {
            $sub->where('pegawai_id', $user->id)
                ->orWhereHas('tim', fn ($q) => $q->where('pegawai_id', $user->id));
        });
        $counts = [
            'semua' => $baseQuery()->count(),
            'proses' => $baseQuery()->where('status', 'proses')->count(),
            'disetujui' => $baseQuery()->where('status', 'disetujui')->count(),
            'revisi' => $baseQuery()->where('status', 'revisi')->count(),
        ];

        return view('pengajuan.riwayat', [
            'daftar' => $daftar,
            'counts' => $counts,
            'filterStatus' => $request->get('status', 'semua'),
            'filterJenis' => $request->get('jenis', ''),
            'filterJalur' => $request->get('jalur', ''),
            'q' => $request->get('q', ''),
        ]);
    }

    public function detail(Pengajuan $pengajuan)
    {
        $user = Auth::user();

        $bolehLihat = $pengajuan->pegawai_id === $user->id || $pengajuan->tim()->where('pegawai_id', $user->id)->exists();
        abort_unless($bolehLihat, 403, 'Anda tidak punya akses ke pengajuan ini.');

        $pengajuan->load(['skema', 'rumpunIlmu', 'pegawai', 'tim.pegawai', 'luaran.luaranMaster']);

        return view('pengajuan.detail', ['p' => $pengajuan]);
    }
}
