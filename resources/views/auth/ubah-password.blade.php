@extends('layouts.app')

@section('title', 'Ubah Password')
@section('crumbs', 'Ubah Password')

@section('content')
<div class="card" style="max-width:480px;">
  @if (auth()->user()->must_change_password)
    <div class="alert-box alert-amber">&#9888;&#65039; Ini adalah login pertama Anda. Silakan buat password baru sebelum melanjutkan.</div>
  @endif

  <h3>Ganti Password</h3>
  <div class="sub">Gunakan password yang kuat dan mudah Anda ingat.</div>

  @if ($errors->any())
    <div class="login-alert" style="display:flex;">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('ubah-password.submit') }}">
    @csrf
    <div class="field">
      <label>Password Baru</label>
      <input type="password" name="password_baru" placeholder="Minimal 6 karakter" required>
    </div>
    <div class="field">
      <label>Konfirmasi Password Baru</label>
      <input type="password" name="password_baru_confirmation" placeholder="Ulangi password baru" required>
    </div>
    <button class="btn btn-primary" type="submit">Simpan Password</button>
  </form>
</div>
@endsection
