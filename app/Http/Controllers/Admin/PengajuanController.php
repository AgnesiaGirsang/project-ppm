<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;

class PengajuanController extends Controller
{
    public function semua(Request $request)
    {
        $title = 'Semua Pengajuan';
        
        $totalSemua = Pengajuan::count();
        $totalProses = Pengajuan::where('status', 'proses')->count();
        $totalDisetujui = Pengajuan::where('status', 'disetujui')->count();
        $totalRevisi = Pengajuan::where('status', 'revisi')->count();

        $query = Pengajuan::with(['pegawai', 'skema', 'rumpunIlmu']);

        if ($request->has('status') && $request->status != '') {
            $status = $request->status == 'menunggu' ? 'proses' : $request->status;
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        $pengajuans = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pengajuan.semua', compact(
            'title', 
            'pengajuans', 
            'totalSemua', 
            'totalProses', 
            'totalDisetujui', 
            'totalRevisi'
        ));
    }

    public function penelitian(Request $request)
    {
        $title = 'Pengajuan Penelitian';
        $query = Pengajuan::with(['pegawai', 'skema'])
            ->where('jenis', 'penelitian');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%");
        }

        if ($request->filled('jalur')) {
            $query->where('jalur', $request->jalur);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('skema')) {
            $query->where('skema_id', $request->skema);
        }

        $pengajuans = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pengajuan.penelitian', compact('title', 'pengajuans'));
    }

    public function pengabdian(Request $request)
    {
        $title = 'Pengajuan Pengabdian';
        $query = Pengajuan::with(['pegawai', 'skema'])
            ->where('jenis', 'pengabdian');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%");
        }

        if ($request->filled('jalur')) {
            $query->where('jalur', $request->jalur);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pengajuan.pengabdian', compact('title', 'pengajuans'));
    }

    public function showDokumen($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        $filePath = public_path('storage/' . $pengajuan->file_proposal); 

        if (file_exists($filePath)) {
            return response()->file($filePath);
        }

        return redirect(asset('storage/' . $pengajuan->file_proposal));
    }
}