@extends('layouts.app')

@section('title', 'Notifikasi')
@section('crumbs', 'Notifikasi')

@section('content')
    <div class="card">
        <div
            style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:16px; padding-bottom:16px; border-bottom:1px solid #eee;">
            <div class="pill-tab">
                @php $currentFilter = request('filter', 'semua'); @endphp
                <a href="{{ route('notifikasi', ['filter' => 'semua']) }}">
                    <button type="button" class="{{ $currentFilter == 'semua' ? 'active' : '' }}">Semua</button>
                </a>
                <a href="{{ route('notifikasi', ['filter' => 'pengajuan']) }}">
                    <button type="button" class="{{ $currentFilter == 'pengajuan' ? 'active' : '' }}">Pengajuan</button>
                </a>
                <a href="{{ route('notifikasi', ['filter' => 'validasi']) }}">
                    <button type="button" class="{{ $currentFilter == 'validasi' ? 'active' : '' }}">Validasi</button>
                </a>
                <a href="{{ route('notifikasi', ['filter' => 'laporan']) }}">
                    <button type="button" class="{{ $currentFilter == 'laporan' ? 'active' : '' }}">Laporan</button>
                </a>
            </div>

            <form action="{{ route('notifikasi.readAll') }}" method="POST">
                @csrf
                <button type="submit"
                    style="border:none; background:none; cursor:pointer; font-size:12px; font-weight:700; color:#555;">
                    &#9989; Tandai semua dibaca
                </button>
            </form>
        </div>

        <div style="display:flex; flex-direction:column; gap:10px;">
            @forelse($notifications as $notif)
                <div
                    style="padding:14px 16px; border-radius:10px; border:1px solid {{ is_null($notif->read_at) ? '#bfe3d0' : '#eee' }}; background:{{ is_null($notif->read_at) ? '#f2fbf6' : '#fff' }}; display:flex; gap:12px; align-items:flex-start;">
                    <span
                        style="margin-top:5px; width:9px; height:9px; border-radius:50%; flex-shrink:0; background:{{ is_null($notif->read_at) ? '#1f9d5c' : '#ccc' }};"></span>
                    <div style="flex-grow:1;">
                        <h3 style="font-size:13px; font-weight:800; margin:0;">{{ $notif->title }}</h3>
                        <p style="font-size:12px; color:#666; margin:4px 0 0;">{{ $notif->message }}</p>
                        <span style="display:block; font-size:11px; color:#999; margin-top:6px;">
                            {{ $notif->created_at ? $notif->created_at->translatedFormat('d M Y H:i') : '' }}
                        </span>
                    </div>
                    @if (is_null($notif->read_at))
                        <form action="{{ route('notifikasi.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                style="border:none; background:none; cursor:pointer; font-size:11px; color:#1f9d5c; font-weight:700;">Tandai
                                dibaca</button>
                        </form>
                    @endif
                </div>
            @empty
                <div style="text-align:center; padding:48px 0; color:#999; font-size:13px;">
                    Belum ada notifikasi.
                </div>
            @endforelse
        </div>
    </div>
@endsection
