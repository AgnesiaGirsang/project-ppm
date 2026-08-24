<?php

namespace App\Http\Controllers;

use App\Models\LaporanHasil;
use App\Models\LaporanKemajuan;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /* ===================== LAPORAN KEMAJUAN ===================== */

    public function kemajuan(Request $request)
    {
        $user = Auth::user();

        // Dropdown: semua pengajuan dimana dia jadi ketua, yang jalurnya butuh laporan kemajuan (simlitabkes)
        // ATAU yang mandiri (biar bisa nampilin pesan "tidak diperlukan")
        $daftarKegiatan = Pengajuan::with('skema')
            ->where('pegawai_id', $user->id)
            ->whereIn('tahap', ['laporan_kemajuan', 'laporan_hasil'])
            ->orWhere(fn ($q) => $q->where('pegawai_id', $user->id)->where('jalur', 'mandiri'))
            ->latest()
            ->get();

        if ($daftarKegiatan->isEmpty()) {
            return view('laporan.kemajuan', ['tanpaKegiatan' => true]);
        }

        $pengajuanId = $request->get('pengajuan_id', $daftarKegiatan->first()->id);
        $pengajuan = $daftarKegiatan->firstWhere('id', (int) $pengajuanId) ?? $daftarKegiatan->first();

        // Jalur mandiri: laporan kemajuan gak diperlukan
        if ($pengajuan->jalur === 'mandiri') {
            return view('laporan.kemajuan', [
                'daftarKegiatan' => $daftarKegiatan,
                'pengajuan' => $pengajuan,
                'mandiri' => true,
            ]);
        }

        $pengajuan->load('luaran.luaranMaster');
        $laporan = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->first();

        return view('laporan.kemajuan', [
            'daftarKegiatan' => $daftarKegiatan,
            'pengajuan' => $pengajuan,
            'laporan' => $laporan,
            'mandiri' => false,
        ]);
    }

    public function kemajuanStore(Request $request, Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $isKirim = $request->input('action') === 'kirim';

        $rules = [
            'persentase' => 'required|integer|min:0|max:100',
            'kegiatan_dilakukan' => 'nullable|string',
            'kendala' => 'nullable|string',
            'rencana_berikutnya' => 'nullable|string',
            'luaran_tercapai' => 'nullable|array',
            'file' => 'nullable|file|mimes:pdf|max:2048',
            'dokumentasi.*' => 'nullable|image|max:5120',
        ];
        if ($isKirim) {
            $rules['file'] = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->whereNotNull('file_path')->exists()
                ? 'nullable|file|mimes:pdf|max:2048'
                : 'required|file|mimes:pdf|max:2048';
        }

        $data = $request->validate($rules);

        $laporan = LaporanKemajuan::firstOrNew(['pengajuan_id' => $pengajuan->id]);
        $laporan->pengajuan_id = $pengajuan->id;
        $laporan->persentase = $data['persentase'];
        $laporan->kegiatan_dilakukan = $data['kegiatan_dilakukan'] ?? null;
        $laporan->kendala = $data['kendala'] ?? null;
        $laporan->rencana_berikutnya = $data['rencana_berikutnya'] ?? null;
        $laporan->luaran_tercapai = $data['luaran_tercapai'] ?? [];
        $laporan->status = $isKirim ? 'proses' : 'draft';
        $laporan->catatan_validator = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $laporan->file_path = $file->store('laporan-kemajuan', 'public');
            $laporan->file_nama_asli = $file->getClientOriginalName();
            $laporan->file_size = $file->getSize();
        }

        if ($request->hasFile('dokumentasi')) {
            $dok = $laporan->dokumentasi ?? [];
            foreach ($request->file('dokumentasi') as $f) {
                $path = $f->store('laporan-kemajuan/dokumentasi', 'public');
                $dok[] = ['path' => $path, 'nama' => $f->getClientOriginalName()];
            }
            $laporan->dokumentasi = $dok;
        }

        $laporan->save();

        return redirect()->route('laporan.kemajuan', ['pengajuan_id' => $pengajuan->id])
            ->with('success', $isKirim ? 'Laporan kemajuan berhasil dikirim dan menunggu validasi admin.' : 'Draft berhasil disimpan.');
    }

    /* ===================== LAPORAN HASIL (masih versi sederhana, belum diredesign) ===================== */

    public function index(string $tipe)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();

        $daftar = Pengajuan::with(['skema', 'laporanHasil'])
            ->where('pegawai_id', $user->id)
            ->where('tahap', 'laporan_hasil')
            ->latest()
            ->get();

        return view('laporan.index', ['tipe' => $tipe, 'daftar' => $daftar, 'judulHalaman' => 'Laporan Hasil']);
    }

    public function form(string $tipe, Pengajuan $pengajuan)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        if ($pengajuan->tahap !== 'laporan_hasil') {
            return redirect()->route('laporan.index', $tipe)->with('error', 'Pengajuan ini belum berada di tahap Laporan Hasil.');
        }

        return view('laporan.form', [
            'tipe' => $tipe,
            'pengajuan' => $pengajuan,
            'laporan' => $pengajuan->laporanHasil,
            'judulHalaman' => 'Laporan Hasil',
        ]);
    }

    public function store(Request $request, string $tipe, Pengajuan $pengajuan)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $data = $request->validate(['file' => 'required|file|mimes:pdf|max:5120']);

        $laporan = LaporanHasil::firstOrNew(['pengajuan_id' => $pengajuan->id]);
        $file = $request->file('file');
        $laporan->pengajuan_id = $pengajuan->id;
        $laporan->file_path = $file->store('laporan-hasil', 'public');
        $laporan->file_nama_asli = $file->getClientOriginalName();
        $laporan->file_size = $file->getSize();
        $laporan->status = 'proses';
        $laporan->catatan_validator = null;
        $laporan->save();

        return redirect()->route('laporan.index', $tipe)->with('success', 'Laporan Hasil berhasil diunggah dan menunggu validasi admin.');
    }
}
