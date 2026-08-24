<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        // Cek role berdasarkan logika aplikasi Anda:
        // Misal: Jika route meminta 'admin', kita bisa cek apakah NIP-nya adalah admin (000000000000000000) 
        // atau jika ada kolom role/is_admin di tabel users/pegawais.
        
        $isAdminRoute = (strtolower(trim($role)) === 'admin');
        
        // Contoh pengecekan berdasarkan NIP admin di tabel pegawai atau properti user
        $isUserAdmin = isset($user->nip) && $user->nip === '000000000000000000'; 
        // Atau jika menggunakan kolom role di tabel users:
        // $userRole = strtolower(trim($user->role ?? ''));

        if ($isAdminRoute && !$isUserAdmin) {
            // Jika mencoba akses route admin tapi bukan admin
            abort(403, 'Anda tidak memiliki akses ke halaman admin ini.');
        }

        return $next($request);
    }
}