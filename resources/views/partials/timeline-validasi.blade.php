{{--
    TIMELINE VALIDASI — gaya tracking paket Shopee
    ------------------------------------------------
    Cara pakai:
    @include('partials.timeline-validasi', [
        'sumber' => [
            ['objek' => $pengajuan,                  'tahap' => 'proposal'],
            ['objek' => $pengajuan->laporanKemajuan, 'tahap' => 'laporan_kemajuan'],
            ['objek' => $pengajuan->laporanHasil,    'tahap' => 'laporan_hasil'],
        ],
        'judul' => 'Lacak Status Validasi',   // opsional
        'batas' => 4,                          // opsional: jumlah item tampil sebelum tombol "Lihat Semua"
    ])
    Objek yang null otomatis dilewati.
--}}
@php
    $sumber = $sumber ?? [];
    $judul  = $judul  ?? 'Lacak Status Validasi';
    $batas  = (int) ($batas ?? 4);
    $uid    = 'tv_' . uniqid();

    $labelTahap = [
        'proposal'         => 'Proposal',
        'laporan_kemajuan' => 'Laporan Kemajuan',
        'laporan_hasil'    => 'Laporan Hasil',
    ];

    $meta = [
        'disetujui'   => ['warna' => '#059669', 'bg' => '#d1fae5', 'icon' => 'check'],
        'revisi'      => ['warna' => '#d97706', 'bg' => '#fef3c7', 'icon' => 'undo'],
        'kirim_ulang' => ['warna' => '#2563eb', 'bg' => '#dbeafe', 'icon' => 'send'],
        'diajukan'    => ['warna' => '#2563eb', 'bg' => '#dbeafe', 'icon' => 'file'],
    ];

    $svg = function (string $n): string {
        return match ($n) {
            'check' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
            'undo'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>',
            'send'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>',
            'file'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
            'clock' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            'chev'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>',
            default => '',
        };
    };

    // ------------------------------------------------------------------
    // Susun semua event dari semua sumber (proposal / kemajuan / hasil)
    // ------------------------------------------------------------------
    $events = [];

    foreach ($sumber as $s) {
        $objek = $s['objek'] ?? null;
        if (!$objek) continue;

        $tahapKey = $s['tahap'] ?? 'proposal';
        $nmTahap  = $labelTahap[$tahapKey] ?? ucfirst(str_replace('_', ' ', $tahapKey));

        $riwayat = $objek->riwayatValidasi; // sudah terurut terbaru dulu (lihat relasi di model)
        $riwayat->loadMissing('admin');

        $adaKirimUlangTercatat = false;

        foreach ($riwayat as $r) {
            if ($r->status === 'kirim_ulang') $adaKirimUlangTercatat = true;

            $aktor = $r->admin->nama ?? null;

            [$jd, $ds] = match ($r->status) {
                'disetujui'   => [$nmTahap . ' Disetujui',           'Divalidasi dan disetujui oleh ' . ($aktor ?? 'Admin') . '.'],
                'revisi'      => [$nmTahap . ' Perlu Revisi',        'Dikembalikan oleh ' . ($aktor ?? 'Admin') . ' untuk diperbaiki.'],
                'kirim_ulang' => ['Revisi ' . $nmTahap . ' Dikirim Ulang', 'Dosen telah memperbaiki dan mengirim ulang. Menunggu validasi admin.'],
                default       => [$nmTahap . ' — ' . ucfirst($r->status), ''],
            };

            $events[] = [
                'tipe'    => $meta[$r->status] ? $r->status : 'diajukan',
                'tahap'   => $nmTahap,
                'judul'   => $jd,
                'desc'    => $ds,
                'aktor'   => in_array($r->status, ['disetujui', 'revisi']) ? $aktor : null,
                'catatan' => $r->catatan,
                'waktu'   => $r->dilakukan_pada ?? $r->created_at,
            ];
        }

        // Event awal: dokumen dikirim (skip kalau masih draft)
        if (($objek->status ?? null) !== 'draft' && $objek->created_at) {
            $events[] = [
                'tipe'    => 'diajukan',
                'tahap'   => $nmTahap,
                'judul'   => $nmTahap . ' Dikirim',
                'desc'    => 'Berhasil dikirim dan menunggu validasi admin.',
                'aktor'   => null,
                'catatan' => null,
                'waktu'   => $objek->created_at,
            ];
        }

        // Heuristik "dikirim ulang": keputusan terakhir revisi, sekarang statusnya proses lagi,
        // dan updated_at lebih baru dari validasi terakhir => dosen sudah kirim ulang.
        $terakhir = $riwayat->first();
        if (
            !$adaKirimUlangTercatat && $terakhir
            && ($objek->status ?? null) === 'proses'
            && $terakhir->status === 'revisi'
            && $objek->updated_at && $terakhir->dilakukan_pada
            && $objek->updated_at->gt($terakhir->dilakukan_pada)
        ) {
            $events[] = [
                'tipe'    => 'kirim_ulang',
                'tahap'   => $nmTahap,
                'judul'   => 'Revisi ' . $nmTahap . ' Dikirim Ulang',
                'desc'    => 'Dosen telah memperbaiki dan mengirim ulang. Menunggu validasi admin.',
                'aktor'   => null,
                'catatan' => null,
                'waktu'   => $objek->updated_at,
            ];
        }
    }

    // Urutkan terbaru di atas (persis tracking Shopee)
    usort($events, fn ($a, $b) => $b['waktu'] <=> $a['waktu']);

    $total          = count($events);
    $jumlahValidasi = collect($events)->whereIn('tipe', ['disetujui', 'revisi'])->count();
    $now            = $events[0] ?? null;

    if ($now) {
        $mNow      = $meta[$now['tipe']];
        $headTitle = match ($now['tipe']) {
            'disetujui' => $now['tahap'] . ' Disetujui',
            'revisi'    => $now['tahap'] . ' Perlu Direvisi',
            default     => 'Menunggu Validasi Admin',
        };
        $headIcon  = in_array($now['tipe'], ['diajukan', 'kirim_ulang']) ? 'clock' : $mNow['icon'];
        $headSub   = match ($now['tipe']) {
            'disetujui' => 'Selamat! Tahap ini sudah lolos validasi.',
            'revisi'    => 'Silakan perbaiki sesuai catatan admin lalu kirim ulang.',
            default     => 'Dokumen sudah masuk antrean validasi admin.',
        };
    }
@endphp

@once
<style>
    .tv-wrap{background:#fff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;font-family:inherit;box-shadow:0 1px 3px rgba(16,24,40,.05);text-align:left}
    .tv-head{display:flex;align-items:center;gap:14px;padding:16px 18px;border-bottom:1px dashed #e5e7eb;background:linear-gradient(135deg,var(--tv-bg) 0%,#fff 75%)}
    .tv-head-ic{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:var(--tv-c);color:#fff;flex:none;box-shadow:0 6px 14px -4px var(--tv-c)}
    .tv-head-ic svg{width:22px;height:22px}
    .tv-head-kicker{font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#6b7280}
    .tv-head-title{font-size:14px;font-weight:800;color:#111827;margin:2px 0;line-height:1.3}
    .tv-head-sub{font-size:11.5px;color:#6b7280;line-height:1.4}
    .tv-head-count{margin-left:auto;text-align:right;font-size:10.5px;color:#6b7280;white-space:nowrap;flex:none}
    .tv-head-count b{display:block;font-size:20px;color:var(--tv-c);line-height:1.1;font-weight:800}
    .tv-body{padding:18px 18px 6px}
    .tv-item{display:grid;grid-template-columns:80px 30px 1fr;column-gap:8px}
    .tv-time{text-align:right;padding-top:3px}
    .tv-time .d{font-size:11.5px;font-weight:700;color:#374151;display:block;white-space:nowrap}
    .tv-time .t{font-size:10.5px;color:#9ca3af;display:block}
    .tv-rail{position:relative;display:flex;justify-content:center}
    .tv-rail::after{content:'';position:absolute;top:20px;bottom:0;left:50%;width:2px;margin-left:-1px;background:#e5e7eb}
    .tv-item:last-child .tv-rail::after{display:none}
    .tv-dot{width:14px;height:14px;border-radius:50%;background:#fff;border:3px solid #cbd5e1;margin-top:4px;position:relative;z-index:1;box-sizing:border-box}
    .tv-item.is-now .tv-dot{width:24px;height:24px;border:none;background:var(--tv-c);margin-top:0;display:flex;align-items:center;justify-content:center;color:#fff;box-shadow:0 0 0 5px var(--tv-bg)}
    .tv-item.is-now .tv-dot svg{width:13px;height:13px}
    .tv-item.is-now .tv-dot::before{content:'';position:absolute;inset:-5px;border-radius:50%;border:2px solid var(--tv-c);opacity:.6;animation:tvPulse 1.8s ease-out infinite}
    @keyframes tvPulse{0%{transform:scale(.8);opacity:.7}100%{transform:scale(1.7);opacity:0}}
    .tv-content{padding-bottom:22px;min-width:0}
    .tv-title{font-size:12.5px;font-weight:700;color:#6b7280;line-height:1.35}
    .tv-item.is-now .tv-title{color:var(--tv-c);font-size:13.5px;font-weight:800}
    .tv-tag{display:inline-block;font-size:9.5px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:2px 8px;border-radius:99px;margin-left:6px;vertical-align:middle;background:var(--tv-bg);color:var(--tv-c)}
    .tv-desc{font-size:11.5px;color:#6b7280;margin-top:3px;line-height:1.5}
    .tv-aktor{display:inline-flex;align-items:center;gap:6px;margin-top:7px;font-size:11px;color:#374151;font-weight:600}
    .tv-aktor .av{width:20px;height:20px;border-radius:50%;background:var(--tv-c);color:#fff;font-size:9.5px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;flex:none}
    .tv-note{margin-top:8px;padding:9px 12px;border-radius:10px;background:#fffbeb;border:1px solid #fde68a;font-size:11.5px;color:#78350f;line-height:1.55;white-space:pre-line}
    .tv-note b{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:#b45309;margin-bottom:2px}
    .tv-item.is-hidden{display:none}
    .tv-more{display:flex;justify-content:center;padding:2px 0 14px}
    .tv-more button{border:1px solid #e5e7eb;background:#fff;color:#374151;font-weight:700;font-size:11.5px;padding:7px 16px;border-radius:99px;cursor:pointer;font-family:inherit;display:inline-flex;align-items:center;gap:6px;transition:background .15s}
    .tv-more button:hover{background:#f9fafb}
    .tv-more button svg{width:14px;height:14px;transition:transform .2s}
    .tv-more button.is-open svg{transform:rotate(180deg)}
    .tv-empty{padding:28px 18px;text-align:center;color:#9ca3af;font-size:12px}
    @media (max-width:520px){.tv-item{grid-template-columns:62px 26px 1fr}.tv-head-count{display:none}}
</style>
@endonce

<div class="tv-wrap" id="{{ $uid }}" @if ($now) style="--tv-c:{{ $mNow['warna'] }};--tv-bg:{{ $mNow['bg'] }};" @endif>

    @if (!$now)
        <div class="tv-head" style="--tv-c:#94a3b8;--tv-bg:#f1f5f9;">
            <div class="tv-head-ic">{!! $svg('clock') !!}</div>
            <div>
                <div class="tv-head-kicker">{{ $judul }}</div>
                <div class="tv-head-title">Belum Ada Aktivitas</div>
                <div class="tv-head-sub">Riwayat akan muncul setelah dokumen dikirim / divalidasi.</div>
            </div>
        </div>
    @else
        {{-- ===== HEADER: status terkini (mirip kartu atas tracking Shopee) ===== --}}
        <div class="tv-head">
            <div class="tv-head-ic">{!! $svg($headIcon) !!}</div>
            <div style="min-width:0;">
                <div class="tv-head-kicker">{{ $judul }} · Status Terkini</div>
                <div class="tv-head-title">{{ $headTitle }}</div>
                <div class="tv-head-sub">
                    {{ $headSub }}
                    <br>Diperbarui {{ $now['waktu']->translatedFormat('d M Y') }}, {{ $now['waktu']->format('H:i') }} WIB
                </div>
            </div>
            <div class="tv-head-count">
                <b>{{ $jumlahValidasi }}x</b>
                validasi admin<br>{{ $total }} aktivitas
            </div>
        </div>

        {{-- ===== BODY: timeline ===== --}}
        <div class="tv-body">
            @foreach ($events as $i => $e)
                @php $m = $meta[$e['tipe']]; @endphp
                <div class="tv-item {{ $i === 0 ? 'is-now' : '' }} {{ $i >= $batas ? 'is-hidden' : '' }}"
                     style="--tv-c:{{ $m['warna'] }};--tv-bg:{{ $m['bg'] }};">

                    <div class="tv-time">
                        <span class="d">{{ $e['waktu']->translatedFormat('d M Y') }}</span>
                        <span class="t">{{ $e['waktu']->format('H:i') }} WIB</span>
                    </div>

                    <div class="tv-rail">
                        <div class="tv-dot">@if ($i === 0){!! $svg($m['icon']) !!}@endif</div>
                    </div>

                    <div class="tv-content">
                        <div class="tv-title">
                            {{ $e['judul'] }}
                            @if (count($sumber) > 1)
                                <span class="tv-tag">{{ $e['tahap'] }}</span>
                            @endif
                        </div>

                        @if ($e['desc'])
                            <div class="tv-desc">{{ $e['desc'] }}</div>
                        @endif

                        @if ($e['aktor'])
                            <div class="tv-aktor">
                                <span class="av">{{ strtoupper(substr($e['aktor'], 0, 1)) }}</span>
                                {{ $e['aktor'] }}
                            </div>
                        @endif

                        @if (!empty($e['catatan']))
                            <div class="tv-note"><b>Catatan Admin</b>{{ $e['catatan'] }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($total > $batas)
            <div class="tv-more">
                <button type="button">
                    <span>Lihat Semua Riwayat ({{ $total - $batas }} lagi)</span>
                    {!! $svg('chev') !!}
                </button>
            </div>
        @endif
    @endif
</div>

@if ($now && $total > $batas)
<script>
    (function () {
        var w = document.getElementById('{{ $uid }}');
        if (!w) return;
        var btn = w.querySelector('.tv-more button');
        if (!btn) return;
        var hidden = w.querySelectorAll('.tv-item.is-hidden');
        var label  = btn.querySelector('span');
        var open   = false;

        btn.addEventListener('click', function () {
            open = !open;
            hidden.forEach(function (el) { el.style.display = open ? 'grid' : 'none'; });
            label.textContent = open ? 'Sembunyikan' : 'Lihat Semua Riwayat ({{ $total - $batas }} lagi)';
            btn.classList.toggle('is-open', open);
        });
    })();
</script>
@endif