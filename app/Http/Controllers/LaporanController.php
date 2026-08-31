<?php

namespace App\Http\Controllers;

use App\Models\LaporanHasil;
use App\Models\LaporanKemajuan;
use App\Models\LuaranMaster;
use App\Models\Notification;
use App\Models\Pegawai;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /* ===================== LAPORAN KEMAJUAN ===================== */

    public function kemajuan()
    {
        $user = Auth::user();

        $daftarKegiatan = Pengajuan::with('skema')
            ->where('pegawai_id', $user->id)
            ->where('jalur', 'simlitabkes')
            ->whereIn('tahap', ['laporan_kemajuan', 'laporan_hasil'])
            ->latest()
            ->get();

        if ($daftarKegiatan->isEmpty()) {
            return view('laporan.kemajuan', ['tanpaKegiatan' => true]);
        }

        $laporanByPengajuan = LaporanKemajuan::whereIn('pengajuan_id', $daftarKegiatan->pluck('id'))
            ->get()
            ->keyBy('pengajuan_id');

        return view('laporan.kemajuan', [
            'daftarKegiatan' => $daftarKegiatan,
            'laporanByPengajuan' => $laporanByPengajuan,
        ]);
    }

    public function kemajuanForm(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        if ($pengajuan->jalur === 'mandiri') {
            return redirect()->route('laporan.kemajuan')
                ->with('error', 'Kegiatan jalur Mandiri tidak memerlukan Laporan Kemajuan.');
        }

        $pengajuan->load('luaran.luaranMaster');
        $laporan = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->first();

        if ($laporan && in_array($laporan->status, ['disetujui', 'proses'])) {
            return redirect()->route('laporan.kemajuan.detail', $pengajuan);
        }

        return view('laporan.kemajuan-form', [
            'pengajuan' => $pengajuan,
            'laporan' => $laporan,
        ]);
    }

    public function kemajuanDetail(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $pengajuan->load('luaran.luaranMaster');
        $laporan = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->firstOrFail();

        return view('laporan.kemajuan-detail', [
            'pengajuan' => $pengajuan,
            'laporan' => $laporan,
        ]);
    }

    public function kemajuanStore(Request $request, Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $isKirim = $request->input('action') === 'kirim';

        $rules = [
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

        $totalLuaran = $pengajuan->luaran()->count();
        $luaranTercapai = $data['luaran_tercapai'] ?? [];
        $persentaseOtomatis = $totalLuaran > 0 ? (int) round(count($luaranTercapai) / $totalLuaran * 100) : 0;

        $laporan = LaporanKemajuan::firstOrNew(['pengajuan_id' => $pengajuan->id]);
        $laporan->pengajuan_id = $pengajuan->id;
        $laporan->persentase = $persentaseOtomatis;
        $laporan->kegiatan_dilakukan = $data['kegiatan_dilakukan'] ?? null;
        $laporan->kendala = $data['kendala'] ?? null;
        $laporan->rencana_berikutnya = $data['rencana_berikutnya'] ?? null;
        $laporan->luaran_tercapai = $luaranTercapai;
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

        if ($isKirim) {
            // Kirim notifikasi ke semua admin bahwa ada laporan kemajuan baru masuk
            $adminIds = Pegawai::where('role', 'admin')->pluck('id');
            foreach ($adminIds as $adminId) {
                Notification::create([
                    'user_id' => $adminId,
                    'type' => 'laporan_kemajuan',
                    'title' => 'Laporan Kemajuan Baru Masuk',
                    'message' => 'Laporan kemajuan untuk "' . $pengajuan->judul . '" dari ' . Auth::user()->nama . ' menunggu validasi.',
                ]);
            }

            return redirect()->route('laporan.kemajuan.sukses', $pengajuan);
        }

        return redirect()->route('laporan.kemajuan.form', $pengajuan)
            ->with('success', 'Draft berhasil disimpan.');
    }

    public function kemajuanSukses(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->first();

        return view('laporan.sukses', [
            'judulHalaman' => 'Laporan Kemajuan',
            'pengajuan' => $pengajuan,
            'laporan' => $laporan,
            'kembaliUrl' => route('laporan.kemajuan'),
            'kembaliLabel' => 'Kembali ke Daftar Laporan Kemajuan',
        ]);
    }

    public function kemajuanHapusFile(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->first();

        if ($laporan && $laporan->file_path) {
            Storage::disk('public')->delete($laporan->file_path);
            $laporan->file_path = null;
            $laporan->file_nama_asli = null;
            $laporan->file_size = null;
            $laporan->save();
        }

        return redirect()->route('laporan.kemajuan.form', $pengajuan)
            ->with('success', 'Dokumen kemajuan berhasil dihapus.');
    }

    public function kemajuanHapusDokumentasi(Pengajuan $pengajuan, int $index)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->first();

        if ($laporan && !empty($laporan->dokumentasi[$index])) {
            Storage::disk('public')->delete($laporan->dokumentasi[$index]['path']);
            $dok = $laporan->dokumentasi;
            unset($dok[$index]);
            $laporan->dokumentasi = array_values($dok);
            $laporan->save();
        }

        return redirect()->route('laporan.kemajuan.form', $pengajuan)
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }

    /* ===================== LAPORAN HASIL ===================== */

    public function index(string $tipe)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();

        $daftarKegiatan = Pengajuan::with('skema')
            ->where('pegawai_id', $user->id)
            ->where('tahap', 'laporan_hasil')
            ->latest()
            ->get();

        if ($daftarKegiatan->isEmpty()) {
            return view('laporan.hasil', ['tanpaKegiatan' => true]);
        }

        $laporanByPengajuan = LaporanHasil::whereIn('pengajuan_id', $daftarKegiatan->pluck('id'))
            ->get()
            ->keyBy('pengajuan_id');

        return view('laporan.hasil', [
            'daftarKegiatan' => $daftarKegiatan,
            'laporanByPengajuan' => $laporanByPengajuan,
        ]);
    }

    public function form(string $tipe, Pengajuan $pengajuan)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        if ($pengajuan->tahap !== 'laporan_hasil') {
            return redirect()->route('laporan.index', $tipe)->with('error', 'Pengajuan ini belum berada di tahap Laporan Hasil.');
        }

        $pengajuan->load('luaran.luaranMaster');
        $laporan = $pengajuan->laporanHasil;
        $readonly = $laporan && in_array($laporan->status, ['proses', 'disetujui']);

        return view('laporan.form', [
            'tipe' => $tipe,
            'pengajuan' => $pengajuan,
            'laporan' => $laporan,
            'luaranList' => $pengajuan->luaran,
            'readonly' => $readonly,
            'judulHalaman' => 'Laporan Hasil',
        ]);
    }

    /**
     * Simpan Laporan Hasil. Ringkasan Hasil & Dokumentasi Kegiatan opsional.
     * Dokumen laporan, Link Inovasi Produk, dan No. SK wajib diisi — tapi
     * hanya divalidasi wajib saat action=kirim (draft boleh belum lengkap).
     */
    public function store(Request $request, string $tipe, Pengajuan $pengajuan)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $existing = LaporanHasil::where('pengajuan_id', $pengajuan->id)->first();
        abort_if(
            $existing && in_array($existing->status, ['proses', 'disetujui']),
            403,
            'Laporan sudah dikirim dan tidak bisa diubah. Hubungi admin untuk membuka akses.'
        );

        $isKirim = $request->input('action') === 'kirim';

        $rules = [
            'ringkasan_hasil' => 'nullable|string',
            'luaran' => 'nullable|array',
            'luaran.*.link' => 'nullable|string|max:500',
            'file' => 'nullable|file|mimes:pdf|max:2048',
            'dokumentasi.*' => 'nullable|image|max:5120',
        ];

        if ($isKirim) {
            $rules['file'] = ($existing && $existing->file_path)
                ? 'nullable|file|mimes:pdf|max:2048'
                : 'required|file|mimes:pdf|max:2048';
            $rules['link_inovasi_produk'] = 'required|string|max:255';
            $rules['no_sk'] = 'required|string|max:255';
        } else {
            $rules['link_inovasi_produk'] = 'nullable|string|max:255';
            $rules['no_sk'] = 'nullable|string|max:255';
        }

        $data = $request->validate($rules);

        $luaranTercapai = [];
        foreach ($request->input('luaran', []) as $luaranId => $val) {
            $link = trim($val['link'] ?? '');
            if ($link !== '') {
                $luaranTercapai[$luaranId] = ['link' => $link];
            }
        }

        $totalLuaran = $pengajuan->luaran()->count();
        $persentaseOtomatis = $totalLuaran > 0 ? (int) round(count($luaranTercapai) / $totalLuaran * 100) : 0;

        $laporan = LaporanHasil::firstOrNew(['pengajuan_id' => $pengajuan->id]);
        $laporan->pengajuan_id = $pengajuan->id;
        $laporan->persentase = $persentaseOtomatis;
        $laporan->ringkasan_hasil = $data['ringkasan_hasil'] ?? null;
        $laporan->link_inovasi_produk = $data['link_inovasi_produk'] ?? $laporan->link_inovasi_produk;
        $laporan->no_sk = $data['no_sk'] ?? $laporan->no_sk;
        $laporan->luaran_tercapai = $luaranTercapai;
        $laporan->status = $isKirim ? 'proses' : 'draft';
        $laporan->catatan_validator = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $laporan->file_path = $file->store('laporan-hasil', 'public');
            $laporan->file_nama_asli = $file->getClientOriginalName();
            $laporan->file_size = $file->getSize();
        }

        if ($request->hasFile('dokumentasi')) {
            $dok = $laporan->dokumentasi ?? [];
            foreach ($request->file('dokumentasi') as $f) {
                $path = $f->store('laporan-hasil/dokumentasi', 'public');
                $dok[] = ['path' => $path, 'nama' => $f->getClientOriginalName()];
            }
            $laporan->dokumentasi = $dok;
        }

        $laporan->save();

        if ($isKirim) {
            // Kirim notifikasi ke semua admin bahwa ada laporan hasil baru masuk
            $adminIds = Pegawai::where('role', 'admin')->pluck('id');
            foreach ($adminIds as $adminId) {
                Notification::create([
                    'user_id' => $adminId,
                    'type' => 'laporan_hasil',
                    'title' => 'Laporan Hasil Baru Masuk',
                    'message' => 'Laporan hasil untuk "' . $pengajuan->judul . '" dari ' . Auth::user()->nama . ' menunggu validasi.',
                ]);
            }

            return redirect()->route('laporan.hasil.sukses', $pengajuan);
        }

        return redirect()->route('laporan.form', [$tipe, $pengajuan])
            ->with('success', 'Draft berhasil disimpan.');
    }

    public function sukses(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanHasil::where('pengajuan_id', $pengajuan->id)->first();

        return view('laporan.sukses', [
            'judulHalaman' => 'Laporan Hasil',
            'pengajuan' => $pengajuan,
            'laporan' => $laporan,
            'kembaliUrl' => route('riwayat'),
            'kembaliLabel' => 'Lihat Riwayat Pengajuan',
        ]);
    }

    public function hasilHapusFile(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanHasil::where('pengajuan_id', $pengajuan->id)->first();

        abort_if($laporan && in_array($laporan->status, ['proses', 'disetujui']), 403, 'Laporan sudah dikirim dan tidak bisa diubah.');

        if ($laporan && $laporan->file_path) {
            Storage::disk('public')->delete($laporan->file_path);
            $laporan->file_path = null;
            $laporan->file_nama_asli = null;
            $laporan->file_size = null;
            $laporan->save();
        }

        return redirect()->route('laporan.form', ['hasil', $pengajuan])->with('success', 'Dokumen berhasil dihapus.');
    }

    public function hasilHapusDokumentasi(Pengajuan $pengajuan, int $index)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanHasil::where('pengajuan_id', $pengajuan->id)->first();

        abort_if($laporan && in_array($laporan->status, ['proses', 'disetujui']), 403, 'Laporan sudah dikirim dan tidak bisa diubah.');

        if ($laporan && !empty($laporan->dokumentasi[$index])) {
            Storage::disk('public')->delete($laporan->dokumentasi[$index]['path']);
            $dok = $laporan->dokumentasi;
            unset($dok[$index]);
            $laporan->dokumentasi = array_values($dok);
            $laporan->save();
        }

        return redirect()->route('laporan.form', ['hasil', $pengajuan])->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
