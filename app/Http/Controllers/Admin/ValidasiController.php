<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\LaporanKemajuan;
use App\Models\LaporanHasil;
use App\Models\Notification;

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

        $dataUpdate = [
            'status' => $statusBaru,
            'catatan_validator' => $request->catatan,
        ];

        // Kalau proposal disetujui, majukan tahap ke laporan_kemajuan
        // supaya muncul di halaman Laporan Kemajuan milik dosen.
        if ($statusBaru === 'disetujui') {
            $dataUpdate['tahap'] = 'laporan_kemajuan';
        }

        $pengajuan->update($dataUpdate);

        // Kirim notifikasi ke dosen pemilik pengajuan
        Notification::create([
            'user_id' => $pengajuan->pegawai_id,
            'type' => 'validasi',
            'title' => $statusBaru === 'disetujui' ? 'Proposal Disetujui' : 'Proposal Perlu Direvisi',
            'message' => $statusBaru === 'disetujui'
                ? 'Proposal "' . $pengajuan->judul . '" telah disetujui. Silakan lanjutkan ke tahap Laporan Kemajuan.'
                : 'Proposal "' . $pengajuan->judul . '" perlu direvisi. Silakan cek catatan validator.',
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

        // Kalau laporan kemajuan disetujui, majukan tahap pengajuan
        // ke laporan_hasil supaya muncul di halaman Laporan Hasil milik dosen.
        if ($statusBaru === 'disetujui') {
            $laporan->pengajuan()->update(['tahap' => 'laporan_hasil']);
        }

        // Kirim notifikasi ke dosen pemilik laporan
        $pengajuan = $laporan->pengajuan;
        Notification::create([
            'user_id' => $pengajuan->pegawai_id,
            'type' => 'laporan',
            'title' => $statusBaru === 'disetujui' ? 'Laporan Kemajuan Disetujui' : 'Laporan Kemajuan Perlu Direvisi',
            'message' => $statusBaru === 'disetujui'
                ? 'Laporan kemajuan untuk "' . $pengajuan->judul . '" telah disetujui. Silakan lanjutkan ke tahap Laporan Hasil.'
                : 'Laporan kemajuan untuk "' . $pengajuan->judul . '" perlu direvisi. Silakan cek catatan validator.',
        ]);

        return redirect()->route('admin.validasi.laporan-kemajuan')->with('success', 'Keputusan laporan kemajuan berhasil dikirim!');
    }


    // ==========================================
    // BAGIAN: VALIDASI LAPORAN HASIL
    // ==========================================
    public function hasilIndex()
    {
        $title = 'Daftar Validasi Laporan Hasil';

        $laporans = LaporanHasil::with(['pengajuan.pegawai', 'pengajuan.skema'])
            ->latest()
            ->paginate(10);

        return view('admin.validasi.laporan_hasil', compact('title', 'laporans'));
    }

    public function hasilDetail($id)
    {
        $title = 'Validasi Laporan Hasil';

        $selected = LaporanHasil::with(['pengajuan.pegawai', 'pengajuan.skema'])->findOrFail($id);

        return view('admin.validasi.laporan_hasil_detail', compact('title', 'selected'));
    }

    public function hasilUpdate(Request $request, $id)
    {
        $request->validate([
            'keputusan' => 'required|in:setuju,revisi',
            'catatan' => 'nullable|string'
        ]);

        $laporan = LaporanHasil::findOrFail($id);

        $statusBaru = $request->keputusan == 'setuju' ? 'disetujui' : 'revisi';

        $laporan->update([
            'status' => $statusBaru,
            'catatan_validator' => $request->catatan
        ]);

        // Kirim notifikasi ke dosen pemilik laporan
        $pengajuan = $laporan->pengajuan;
        Notification::create([
            'user_id' => $pengajuan->pegawai_id,
            'type' => 'laporan',
            'title' => $statusBaru === 'disetujui' ? 'Laporan Hasil Disetujui' : 'Laporan Hasil Perlu Direvisi',
            'message' => $statusBaru === 'disetujui'
                ? 'Laporan hasil untuk "' . $pengajuan->judul . '" telah disetujui. Kegiatan penelitian/pengabdian ini telah selesai divalidasi.'
                : 'Laporan hasil untuk "' . $pengajuan->judul . '" perlu direvisi. Silakan cek catatan validator.',
        ]);

        return redirect()->route('admin.validasi.laporan_hasil')->with('success', 'Keputusan laporan hasil berhasil dikirim!');
    }
}
