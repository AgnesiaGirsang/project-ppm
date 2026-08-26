<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BerkasController extends Controller
{
    /**
     * Serve file dari storage (disk 'public' atau 'local') untuk preview/download.
     *
     * Dipanggil lewat route('berkas.show', ['path' => $selected->file_path]).
     * Tambahkan ?download=1 di query string untuk force-download.
     */
    public function show(Request $request): StreamedResponse
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $path = $request->query('path');

        // Cegah path traversal (../../.env dsb).
        $normalized = str_replace('\\', '/', $path);
        if (str_contains($normalized, '..')) {
            abort(403, 'Path tidak valid.');
        }

        // Sesuaikan disk penyimpanan dengan yang dipakai saat upload.
        // Kalau upload pakai Storage::disk('public')->put(...), pakai 'public'.
        // Kalau upload pakai disk 'local' (default), ganti ke 'local'.
        $disk = 'public';

        if (!Storage::disk($disk)->exists($normalized)) {
            abort(404, 'File tidak ditemukan.');
        }

        $filename = basename($normalized);
        $forceDownload = $request->boolean('download');

        return Storage::disk($disk)->response(
            $normalized,
            $filename,
            [],
            $forceDownload ? 'attachment' : 'inline'
        );
    }
}
