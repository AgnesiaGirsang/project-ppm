<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — SIPPM Poltekkes Kemenkes Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark"><img src="{{ asset('img/logo-icon.png') }}" alt="Logo Poltekkes Kemenkes Medan"
                        style="width:100%; height:100%; object-fit:contain; padding:4px;"></div>
                <div class="brand-text"><b>Poltekkes Kemenkes</b><span>Medan · SIPPM</span></div>
            </div>
            <div class="nav">
                <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span
                        class="ic">▦</span>Dashboard</a>
                <a class="{{ request()->routeIs('pengajuan.*') ? 'active' : '' }}"
                    href="{{ route('pengajuan.step1') }}"><span class="ic">✎</span>Pengajuan Baru</a>
                <a class="{{ request()->routeIs('riwayat') || request()->routeIs('pengajuan.detail') ? 'active' : '' }}"
                    href="{{ route('riwayat') }}"><span class="ic">◷</span>Riwayat Pengajuan</a>
                <a class="{{ request()->routeIs('laporan.kemajuan*') ? 'active' : '' }}"
                    href="{{ route('laporan.kemajuan') }}"><span class="ic">▤</span>Laporan Kemajuan</a>
                <a class="{{ request()->routeIs('laporan.*') && !request()->routeIs('laporan.kemajuan*') ? 'active' : '' }}"
                    href="{{ route('laporan.index', 'hasil') }}"><span class="ic">▤</span>Laporan Hasil</a>
                <a class="{{ request()->routeIs('luaran.*') ? 'active' : '' }}"
                    href="{{ route('luaran.index') }}"><span class="ic">★</span>Luaran</a>
                <a href="#"><span class="ic">🔔</span>Notifikasi</a>
                <a class="{{ request()->routeIs('profil*') ? 'active' : '' }}" href="{{ route('profil') }}"><span
                        class="ic">◈</span>Profil</a>
                <a href="{{ route('ubah-password') }}"
                    class="{{ request()->routeIs('ubah-password') ? 'active' : '' }}"><span class="ic">⚿</span>Ubah
                    Password</a>
                <a href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span
                        class="ic">⏻</span>Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            </div>
            <div class="sidebar-foot">Sistem Informasi Pengelolaan<br>Penelitian &amp; Pengabdian Masyarakat<br>&copy;
                {{ date('Y') }}</div>
        </aside>

        <div class="main">
            <div class="topbar">
                <div>
                    <h1>@yield('title', 'Dashboard')</h1>
                    <div class="crumbs">@yield('crumbs', 'Dashboard')</div>
                </div>
                <div class="who">
                    <div class="bell">&#128276;<span class="dot"></span></div>
                    <div class="avatar">{{ auth()->user()->initials() }}</div>
                    <div class="meta">
                        <b>{{ auth()->user()->nama }}</b>
                        <span>Dosen</span>
                    </div>
                </div>
            </div>

            <div class="content">
                @if (session('success'))
                    <div class="alert-box alert-info" style="margin-bottom:16px;">&#9989; {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
