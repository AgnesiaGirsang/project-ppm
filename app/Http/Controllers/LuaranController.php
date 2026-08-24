<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\PengajuanLuaran;
use App\Models\LuaranRealisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LuaranController extends Controller
{
    // Daftar pengajuan yang sudah SELESAI (laporan hasil disetujui admin), siap diisi realisasi luaran
    public function index()
    {
        $user = Auth::user();

        $daftar = Pengajuan::with(['skema', 'luaran.luaranMaster', 'luaran.realisasi', 'laporanHasil'])
            ->where('pegawai_id', $user->id)
            ->whereHas('laporanHasil', fn ($q) => $q->where('status', 'disetujui'))
            ->latest()
            ->get();

        return view('luaran.index', ['daftar' => $daftar]);
    }

    public function form(PengajuanLuaran $luaran)
    {
        $user = Auth::user();
        $pengajuan = $luaran->pengajuan;
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $realisasi = $luaran->realisasi;

        return view('luaran.form', [
            'luaran' => $luaran,
            'pengajuan' => $pengajuan,
            'realisasi' => $realisasi,
        ]);
    }

    public function store(Request $request, PengajuanLuaran $luaran)
    {
        $user = Auth::user();
        abort_unless($luaran->pengajuan->pegawai_id === $user->id, 403);

        $data = $request->validate([
            'keterangan' => 'required|string',
            'link_bukti' => 'nullable|url',
            'tanggal_realisasi' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $realisasi = LuaranRealisasi::firstOrNew(['pengajuan_luaran_id' => $luaran->id]);
        $realisasi->pengajuan_luaran_id = $luaran->id;
        $realisasi->keterangan = $data['keterangan'];
        $realisasi->link_bukti = $data['link_bukti'] ?? null;
        $realisasi->tanggal_realisasi = $data['tanggal_realisasi'];
        $realisasi->status = 'proses';
        $realisasi->catatan_validator = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $realisasi->file_path = $file->store('luaran-realisasi', 'public');
            $realisasi->file_nama_asli = $file->getClientOriginalName();
            $realisasi->file_size = $file->getSize();
        }

        $realisasi->save();

        return redirect()->route('luaran.index')->with('success', 'Realisasi luaran berhasil disimpan dan menunggu validasi admin.');
    }
}
