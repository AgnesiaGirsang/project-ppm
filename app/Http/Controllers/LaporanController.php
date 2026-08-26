<?php

namespace App\Http\Controllers;

use App\Models\LaporanHasil;
use App\Models\LaporanKemajuan;
use App\Models\LuaranMaster;
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

        // Persentase dihitung otomatis: jumlah luaran yang dicentang tercapai / total luaran direncanakan
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
            return redirect()->route('laporan.kemajuan.sukses', $pengajuan);
        }

        return redirect()->route('laporan.kemajuan', ['pengajuan_id' => $pengajuan->id])
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
            'kembaliUrl' => route('laporan.kemajuan', ['pengajuan_id' => $pengajuan->id]),
            'kembaliLabel' => 'Kembali ke Laporan Kemajuan',
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

        return redirect()->route('laporan.kemajuan', ['pengajuan_id' => $pengajuan->id])
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

        return redirect()->route('laporan.kemajuan', ['pengajuan_id' => $pengajuan->id])
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }

    /* ===================== LAPORAN HASIL ===================== */

    public function index(string $tipe)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();

        $pengajuanPertama = Pengajuan::where('pegawai_id', $user->id)
            ->where('tahap', 'laporan_hasil')
            ->latest()
            ->first();

        if (!$pengajuanPertama) {
            return view('laporan.form', [
                'tipe' => $tipe,
                'tanpaKegiatan' => true,
                'judulHalaman' => 'Laporan Hasil',
            ]);
        }

        return redirect()->route('laporan.form', [$tipe, $pengajuanPertama]);
    }

    public function form(string $tipe, Pengajuan $pengajuan)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        if ($pengajuan->tahap !== 'laporan_hasil') {
            return redirect()->route('laporan.index', $tipe)->with('error', 'Pengajuan ini belum berada di tahap Laporan Hasil.');
        }

        $daftarKegiatan = Pengajuan::where('pegawai_id', $user->id)
            ->where('tahap', 'laporan_hasil')
            ->latest()
            ->get();

        $luaranList = LuaranMaster::where('jenis', $pengajuan->jenis)
            ->orderByDesc('wajib')
            ->orderBy('id')
            ->get();

        return view('laporan.form', [
            'tipe' => $tipe,
            'pengajuan' => $pengajuan,
            'laporan' => $pengajuan->laporanHasil,
            'daftarKegiatan' => $daftarKegiatan,
            'luaranList' => $luaranList,
            'judulHalaman' => 'Laporan Hasil',
        ]);
    }

    public function store(Request $request, string $tipe, Pengajuan $pengajuan)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $isKirim = $request->input('action') === 'kirim';

        $rules = [
            'ringkasan_hasil' => 'nullable|string',
            'link_inovasi_produk' => 'nullable|string|max:255',
            'no_sk' => 'nullable|string|max:255',
            'luaran' => 'nullable|array',
            'luaran.*.link' => 'nullable|string|max:500',
            'file' => 'nullable|file|mimes:pdf|max:2048',
            'dokumentasi.*' => 'nullable|image|max:5120',
        ];
        if ($isKirim) {
            $rules['file'] = LaporanHasil::where('pengajuan_id', $pengajuan->id)->whereNotNull('file_path')->exists()
                ? 'nullable|file|mimes:pdf|max:2048'
                : 'required|file|mimes:pdf|max:2048';
        }

        $data = $request->validate($rules);

        $luaranTercapai = [];
        foreach ($request->input('luaran', []) as $luaranId => $val) {
            if (!empty($val['checked'])) {
                $luaranTercapai[$luaranId] = ['link' => $val['link'] ?? null];
            }
        }

        $laporan = LaporanHasil::firstOrNew(['pengajuan_id' => $pengajuan->id]);
        $laporan->pengajuan_id = $pengajuan->id;
        $laporan->ringkasan_hasil = $data['ringkasan_hasil'] ?? null;
        $laporan->link_inovasi_produk = $data['link_inovasi_produk'] ?? null;
        $laporan->no_sk = $data['no_sk'] ?? null;
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
