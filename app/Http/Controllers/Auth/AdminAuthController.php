<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        $pegawai = Pegawai::where('nip', $request->nip)
            ->where('role', 'admin')
            ->first();

        if (!$pegawai || !Hash::check($request->password, $pegawai->password)) {
            return back()
                ->withErrors(['login' => 'NIP atau kata sandi salah. Silakan periksa kembali.'])
                ->withInput($request->only('nip'));
        }

        Auth::login($pegawai, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect('/admin/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
