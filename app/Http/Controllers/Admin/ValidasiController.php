<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\LaporanKemajuan;

class ValidasiController extends Controller
{
    // ==========================================
    // BAGIAN: VALIDASI PROPOSAL
    // ==========================================
    public function index()
    {
        $title = 'Daftar Validasi Proposal';
        
        $pengajuans = Pengajuan::with(['pegawai', 'skema', 'rumpunIlmu'])
            ->where('status', 'proses')
            ->latest()
            ->paginate(10);

        return view('admin.validasi.proposal', compact('title', 'pengajuans'));
    }

    public function proposal($id)
    {
        $title = 'Validasi Proposal';
        
        $selected = Pengajuan::with(['pegawai', 'skema', 'rumpunIlmu', 'tim.pegawai', 'luaran'])->findOrFail($id);

        return view('admin.validasi.proposal_detail', compact('title', 'selected'));
    }

    public function updateValidasi(Request $request, $id)
    {
        $request->validate([
            'keputusan' => 'required|in:setuju,revisi',
            'catatan' => 'nullable|string'
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        $statusBaru = $request->keputusan == 'setuju' ? 'disetujui' : 'revisi';
        
        $pengajuan->update([
            'status' => $statusBaru,
            'catatan_validator' => $request->catatan
        ]);

        return redirect()->route('admin.validasi.proposal')->with('success', 'Keputusan proposal berhasil dikirim dan status diperbarui!');
    }


    // ==========================================
    // BAGIAN: VALIDASI LAPORAN KEMAJUAN
    // ==========================================
    public function kemajuanIndex()
    {
        $title = 'Daftar Validasi Laporan Kemajuan';
        
        // Menggunakan LaporanKemajuan beserta relasinya
        $laporans = LaporanKemajuan::with(['pengajuan.pegawai', 'pengajuan.skema'])
            ->latest()
            ->paginate(10);

        return view('admin.validasi.laporan_kemajuan', compact('title', 'laporans'));
    }

    public function kemajuanDetail($id)
    {
        $title = 'Validasi Laporan Kemajuan';
        
        // Mencari data laporan kemajuan beserta relasi pengajuan dan pegawai
        $selected = LaporanKemajuan::with(['pengajuan.pegawai', 'pengajuan.skema'])->findOrFail($id);

        return view('admin.validasi.laporan_kemajuan_detail', compact('title', 'selected'));
    }

    public function kemajuanUpdate(Request $request, $id)
    {
        $request->validate([
            'keputusan' => 'required|in:setuju,revisi',
            'catatan' => 'nullable|string'
        ]);

        $laporan = LaporanKemajuan::findOrFail($id);

        $statusBaru = $request->keputusan == 'setuju' ? 'disetujui' : 'revisi';
        
        // Memperbarui status dan catatan pada tabel laporan_kemajuan
        $laporan->update([
            'status' => $statusBaru,
            'catatan_validator' => $request->catatan
        ]);

        return redirect()->route('admin.validasi.laporan-kemajuan')->with('success', 'Keputusan laporan kemajuan berhasil dikirim!');
    }
}