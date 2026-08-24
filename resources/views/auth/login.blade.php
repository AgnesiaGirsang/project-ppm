<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — SIPPM Poltekkes Kemenkes Medan</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div id="loginScreen">
  <div class="login-wrap">
    <div class="login-side">
      <div class="login-brand">
        <div class="brand-mark"><img src="{{ asset('img/logo-icon.png') }}" alt="Logo" style="width:100%; height:100%; object-fit:contain; padding:5px;"></div>
        <div><b>SIPPM</b><span>Poltekkes Kemenkes Medan</span></div>
      </div>
      <div class="login-hero">
        <h2>Kelola Penelitian &amp; Pengabdian Masyarakat dalam Satu Sistem</h2>
        <p>Ajukan proposal, pantau progres validasi, unggah laporan, hingga catat luaran penelitian dan pengabdian Anda secara terintegrasi.</p>
        <ul class="login-feat">
          <li><div class="ic">✎</div><div><b>Pengajuan Digital</b>Ajukan proposal penelitian & pengabdian tanpa kertas.</div></li>
          <li><div class="ic">◷</div><div><b>Pantau Real-time</b>Lihat status validasi setiap tahap pengajuan Anda.</div></li>
          <li><div class="ic">★</div><div><b>Catat Luaran</b>Rekam seluruh capaian & luaran kegiatan Anda.</div></li>
        </ul>
      </div>
      <div class="login-foot">&copy; {{ date('Y') }} Poltekkes Kemenkes Medan — SIPPM</div>
    </div>

    <div class="login-form-side">
      <div class="login-box">
        <h1>Masuk ke Akun Anda</h1>
        <div class="sub">Gunakan NIP dan password yang telah terdaftar di sistem.</div>

        <div class="login-alert" style="{{ $errors->has('login') ? 'display:flex;' : '' }}">
          {{ $errors->first('login') }}
        </div>

        <form method="POST" action="{{ route('login.submit') }}">
          @csrf
          <div class="field">
            <label>NIP</label>
            <input type="text" name="nip" placeholder="Masukkan NIP Anda" value="{{ old('nip') }}" autocomplete="username" required>
          </div>
          <div class="field">
            <label>Password</label>
            <div class="pw-wrap">
              <input id="loginPass" type="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
              <button type="button" onclick="togglePw()">&#128065;</button>
            </div>
          </div>
          <div class="login-remember">
            <label><input type="checkbox" name="remember"> Ingat saya</label>
            <a href="#" onclick="alert('Silakan hubungi Admin SIPPM untuk reset password.'); return false;">Lupa password?</a>
          </div>
          <button class="btn btn-primary" style="width:100%; justify-content:center;" type="submit">Masuk</button>
        </form>

        <div style="text-align:center; margin-top:18px; font-size:11.5px; color:var(--ink-500);">
          Login sebagai Admin? <a href="{{ route('admin.login') }}" style="color:var(--green-700); font-weight:700; text-decoration:none;">Masuk lewat Portal Admin &rarr;</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function togglePw(){
  const i = document.getElementById('loginPass');
  i.type = i.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
