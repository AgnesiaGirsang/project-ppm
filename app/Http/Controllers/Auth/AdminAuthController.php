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
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $pegawai = Pegawai::where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if (!$pegawai || !Hash::check($request->password, $pegawai->password)) {
            return back()
                ->withErrors(['login' => 'Alamat email atau kata sandi salah. Silakan periksa kembali.'])
                ->withInput($request->only('email'));
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