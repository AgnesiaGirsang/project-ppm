<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        return view('profil.index', [
            'pegawai' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'email' => 'nullable|email|max:255',
            'hp' => 'nullable|string|max:20',
        ]);

        Auth::user()->update($data);

        return redirect()->route('profil')->with('success', 'Data kontak berhasil diperbarui.');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pegawai = Auth::user();

        if ($pegawai->foto) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        $path = $request->file('foto')->store('foto-profil', 'public');
        $pegawai->update(['foto' => $path]);

        return redirect()->route('profil')->with('success', 'Foto profil berhasil diperbarui.');
    }
}
