@extends('layouts.admin')
@section('title', 'Laporan 1 - Rekapitulasi per Jurusan')

@section('content')
<style>
.l1-header-box{display:flex;align-items:center;justify-content:space-between;padding:20px 4px 24px}
.l1-header-left{display:flex;align-items:center;gap:16px}
.l1-header-icon{width:52px;height:52px;background:#f0faf4;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;color:#1a6b3c;flex-shrink:0}
.l1-title{font-size:1.25rem;font-weight:700;color:#1a1a2e;margin:0 0 3px}
.l1-bread{font-size:12px;color:#999;margin:0}
.l1-bread span{color:#1a6b3c;font-weight:600}
.btn-xls{display:inline-flex;align-items:center;gap:8px;padding:9px 20px;border:2px solid #1a6b3c;background:white;color:#1a6b3c;border-radius:10px;font-weight:600;font-size:13px;text-decoration:none;transition:all .2s}
.btn-xls:hover{background:#1a6b3c;color:white;transform:translateY(-1px)}
.btn-pdf{display:inline-flex;align-items:center;gap:8px;padding:9px 20px;border:2px solid #e74c3c;background:white;color:#e74c3c;border-radius:10px;font-weight:600;font-size:13px;text-decoration:none;transition:all .2s}
.btn-pdf:hover{background:#e74c3c;color:white;transform:translateY(-1px)}
.l1-filter{background:white;border-radius:16px;padding:24px 28px;margin-bottom:20px;box-shadow:0 2px 12px rgba(0,0,0,.07);position:relative;overflow:hidden}
.l1-filter-bg{position:absolute;right:20px;bottom:0;width:200px;height:140px;opacity:.07;font-size:120px;color:#27ae60;pointer-events:none;display:flex;align-items:center;justify-content:center}
.l1-filter-lbl{font-size:13px;font-weight:700;color:#1a6b3c;display:flex;align-items:center;gap:8px;margin-bottom:14px}
.l1-filter-icon{width:30px;height:30px;background:#e8f5e9;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;color:#1a6b3c}
.l1-sel-wrap{position:relative;max-width:320px}
.l1-sel-wrap select{width:100%;padding:11px 40px 11px 16px;border:1.5px solid #e0e0e0;border-radius:12px;font-size:13px;color:#555;background:white;appearance:none;cursor:pointer;transition:border-color .2s,box-shadow .2s}
.l1-sel-wrap select:focus{outline:none;border-color:#27ae60;box-shadow:0 0 0 3px rgba(39,174,96,.15)}
.l1-sel-wrap .l1-chev{position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#aaa;font-size:12px;pointer-events:none}
.btn-tampil{display:inline-flex;align-items:center;gap:8px;margin-top:16px;padding:11px 24px;background:linear-gradient(135deg,#1a6b3c,#27ae60);color:white;border:none;border-radius:12px;font-weight:600;font-size:13px;cursor:pointer;transition:all .2s;box-shadow:0 4px 12px rgba(26,107,60,.3)}
.btn-tampil:hover{transform:translateY(-2px);box-shadow:0 6px 18px rgba(26,107,60,.4)}
.btn-rst{display:inline-flex;align-items:center;gap:6px;margin-top:16px;margin-left:10px;padding:11px 18px;background:white;color:#666;border:1.5px solid #ddd;border-radius:12px;font-size:13px;cursor:pointer;transition:all .2s;text-decoration:none}
.btn-rst:hover{border-color:#aaa;background:#f5f5f5;color:#333}
.stat-bar{border-radius:16px;padding:0 28px;margin-bottom:14px;height:90px;display:flex;align-items:center;gap:20px;position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s;cursor:default}
.stat-bar:hover{transform:translateY(-2px)}
.stat-bar .sb-circle{width:54px;height:54px;background:rgba(255,255,255,.25);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;color:white;flex-shrink:0;border:2px solid rgba(255,255,255,.35)}
.stat-bar .sb-center{flex:1;text-align:center}
.stat-bar .sb-num{font-size:2.3rem;font-weight:800;color:white;line-height:1;margin-bottom:4px}
.stat-bar .sb-lbl{font-size:13px;color:rgba(255,255,255,.88);font-weight:500}
.stat-bar .sb-bg{position:absolute;right:30px;top:50%;transform:translateY(-50%);font-size:72px;color:rgba(255,255,255,.12);pointer-events:none}
.sb-green{background:linear-gradient(135deg,#2ecc71,#27ae60);box-shadow:0 5px 18px rgba(39,174,96,.45)}
.sb-blue{background:linear-gradient(135deg,#5b9bd5,#4a90d9);box-shadow:0 5px 18px rgba(74,144,217,.45)}
.sb-orange{background:linear-gradient(135deg,#f5a623,#e67e22);box-shadow:0 5px 18px rgba(230,126,34,.45)}
.sb-purple{background:linear-gradient(135deg,#9b59b6,#8e44ad);box-shadow:0 5px 18px rgba(142,68,173,.45)}
.sb-teal{background:linear-gradient(135deg,#1abc9c,#16a085);box-shadow:0 5px 18px rgba(26,188,156,.45)}
.sb-pink{background:linear-gradient(135deg,#e91e8c,#c0166a);box-shadow:0 5px 18px rgba(233,30,140,.45)}
.tbl-card{background:white;border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.07);overflow:hidden;margin-top:24px}
.tbl-head-bar{padding:18px 24px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between}
.tbl-head-title{font-size:15px;font-weight:700;color:#1a1a2e;display:flex;align-items:center;gap:10px}
.tbl-head-icon{width:36px;height:36px;background:#e8f5e9;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;color:#1a6b3c}
.tbl-count{background:#e8f5e9;color:#1a6b3c;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.l1-table{font-size:13px}
.l1-table thead th{background:#fafafa;color:#777;font-weight:600;font-size:10.5px;text-transform:uppercase;letter-spacing:.5px;padding:13px 16px;border-top:none;border-bottom:2px solid #f0f0f0;white-space:nowrap}
.l1-table tbody tr{border-bottom:1px solid #f5f5f5;transition:background .15s}
.l1-table tbody tr:hover{background:#fafffe}
.l1-table tbody td{padding:14px 16px;vertical-align:middle;border:none;border-bottom:1px solid #f5f5f5}
.l1-table tbody tr:last-child td{border-bottom:none}
.jur-av{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#1a6b3c,#27ae60);color:white;font-weight:800;font-size:15px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.np{display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:28px;padding:0 10px;border-radius:20px;font-size:12px;font-weight:700}
.np-tot{background:linear-gradient(135deg,#1a6b3c,#27ae60);color:white}
.np-pen{background:#e8f5e9;color:#1a6b3c}
.np-peng{background:#e8f0fd;color:#3949ab}
.np-sim{background:#fff3e0;color:#e65100}
.np-man{background:#f3e5f5;color:#7b1fa2}
.skema-ch{display:inline-block;padding:2px 8px;margin:2px;background:#f1f8e9;color:#33691e;border:1px solid #c5e1a5;border-radius:6px;font-size:11px;font-weight:500}
.btn-tog{width:32px;height:32px;background:#f0faf4;color:#1a6b3c;border:1px solid #c8e6c9;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;font-size:12px}
.btn-tog:hover,.btn-tog.on{background:#1a6b3c;color:white;border-color:#1a6b3c}
.det-wrap td{padding:0!important;border:none!important}
.det-inner{background:#f8fffe;border-left:4px solid #27ae60;border-bottom:1px solid #e8f5e9}
.det-ih{padding:12px 20px;background:linear-gradient(90deg,#e8f5e9,#f1f8f4);border-bottom:1px solid #c8e6c9;display:flex;align-items:center;gap:10px}
.det-ih-ic{width:28px;height:28px;background:#1a6b3c;border-radius:8px;color:white;font-size:12px;display:flex;align-items:center;justify-content:center}
.det-tbl{font-size:12px}
.det-tbl th{background:#1a6b3c;color:white;padding:9px 14px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.3px;border:none}
.det-tbl td{padding:9px 14px;border-bottom:1px solid #e8f5e9;border-top:none}
.det-tbl tbody tr:hover td{background:#f0f9f3}
.det-tbl tbody tr:last-child td{border-bottom:none}
.tfoot-tot{background:#f1f8e9;border-top:2px solid #a5d6a7}
.tfoot-tot td{padding:13px 16px!important;font-weight:700;border:none!important}
.st-p{background:#fff3e0;color:#e65100;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600}
.st-d{background:#e8f5e9;color:#1a6b3c;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600}
.st-r{background:#fce4ec;color:#c62828;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600}
.empty-box{padding:64px 20px;text-align:center}
</style>

<div class="container-fluid px-2">

{{-- HEADER --}}
<div class="l1-header-box">
    <div class="l1-header-left">
        <div class="l1-header-icon">
            <i class="bi bi-file-earmark-bar-graph-fill"></i>
        </div>
        <div>
            <div class="l1-title">Laporan 1 – Rekapitulasi Penelitian per Jurusan</div>
            <div class="l1-bread">
                Dashboard &rsaquo; Laporan &rsaquo; <span>Laporan 1</span>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.laporan.laporan1.export.excel', request()->query()) }}" class="btn-xls">
            <i class="bi bi-file-earmark-excel-fill text-success" style="font-size:18px"></i> Export Excel
        </a>
        <a href="{{ route('admin.laporan.laporan1.export.pdf', request()->query()) }}" class="btn-pdf">
            <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size:18px"></i> Export PDF
        </a>
    </div>
</div>

{{-- FILTER --}}
<div class="l1-filter">
    <div class="l1-filter-bg"><i class="bi bi-calendar3-week"></i></div>
    <div class="l1-filter-lbl">
        <div class="l1-filter-icon"><i class="bi bi-funnel-fill"></i></div>
        Filter Tahun
    </div>
    <form method="GET" action="{{ route('admin.laporan.laporan1') }}">
        <div class="l1-sel-wrap">
            <select name="tahun">
                <option value="">Pilih Tahun</option>
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down l1-chev"></i>
        </div>
        <div>
            <button type="submit" class="btn-tampil">
                <i class="bi bi-funnel-fill"></i> Tampilkan
            </button>
            @if($tahun)
            <a href="{{ route('admin.laporan.laporan1') }}" class="btn-rst">
                <i class="bi bi-x-circle"></i> Reset
            </a>
            @endif
        </div>
    </form>
</div>

{{-- STAT BANNERS --}}
@php
$banners = [
    ['val'=>$rekapJurusan->sum('total'),           'lbl'=>'Total Pengajuan',    'cls'=>'sb-green',  'ic'=>'bi-people-fill',       'bg'=>'bi-collection-fill'],
    ['val'=>$rekapJurusan->count(),                 'lbl'=>'Jurusan Aktif',      'cls'=>'sb-blue',   'ic'=>'bi-building-fill',     'bg'=>'bi-building'],
    ['val'=>$totalPerJalur['simlitabkes']??0,       'lbl'=>'Jalur Simlitabkes', 'cls'=>'sb-orange', 'ic'=>'bi-diagram-3-fill',    'bg'=>'bi-diagram-3'],
    ['val'=>$totalPerJalur['mandiri']??0,            'lbl'=>'Jalur Mandiri',     'cls'=>'sb-purple', 'ic'=>'bi-person-fill-check', 'bg'=>'bi-signpost-2'],
    ['val'=>$totalPerJenis['penelitian']??0,         'lbl'=>'Penelitian',        'cls'=>'sb-teal',   'ic'=>'bi-journal-text',      'bg'=>'bi-journal-richtext'],
    ['val'=>$totalPerJenis['pengabdian']??0,         'lbl'=>'Pengabdian',        'cls'=>'sb-pink',   'ic'=>'bi-clipboard2-heart-fill','bg'=>'bi-clipboard2-heart'],
];
@endphp
@foreach($banners as $b)
<div class="stat-bar {{ $b['cls'] }}">
    <div class="sb-circle"><i class="bi {{ $b['ic'] }}"></i></div>
    <div class="sb-center">
        <div class="sb-num">{{ $b['val'] }}</div>
        <div class="sb-lbl">{{ $b['lbl'] }}</div>
    </div>
    <i class="bi {{ $b['bg'] }} sb-bg"></i>
</div>
@endforeach

{{-- TABLE --}}
<div class="tbl-card">
    <div class="tbl-head-bar">
        <div class="tbl-head-title">
            <div class="tbl-head-icon"><i class="bi bi-table"></i></div>
            Rekapitulasi per Jurusan
            @if($tahun)<span style="font-size:12px;color:#999;font-weight:400;">– Tahun {{ $tahun }}</span>@endif
        </div>
        <span class="tbl-count">{{ $rekapJurusan->count() }} Jurusan</span>
    </div>

    @if($rekapJurusan->isEmpty())
    <div class="empty-box">
        <div style="font-size:48px;margin-bottom:14px;">📭</div>
        <h6 class="fw-bold text-muted">Belum ada data</h6>
        <p class="text-muted small">Pilih tahun atau reset filter</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table l1-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4" width="50">No</th>
                    <th>Jurusan</th>
                    <th class="text-center" width="80">Total</th>
                    <th class="text-center" width="110">Penelitian</th>
                    <th class="text-center" width="110">Pengabdian</th>
                    <th class="text-center" width="120">Simlitabkes</th>
                    <th class="text-center" width="100">Mandiri</th>
                    <th>Per Skema</th>
                    <th class="text-center" width="70">Detail</th>
                </tr>
            </thead>
            <tbody>
                @php $no=1; @endphp
                @foreach($rekapJurusan as $jurusan => $data)
                <tr>
                    <td class="ps-4 text-muted fw-semibold">{{ $no++ }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="jur-av">{{ strtoupper(substr($jurusan,0,1)) }}</div>
                            <div>
                                <div class="fw-semibold" style="color:#1a1a2e;">{{ $jurusan }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $data['total'] }} penelitian</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center"><span class="np np-tot">{{ $data['total'] }}</span></td>
                    <td class="text-center"><span class="np np-pen">{{ $data['per_jenis']['penelitian']??0 }}</span></td>
                    <td class="text-center"><span class="np np-peng">{{ $data['per_jenis']['pengabdian']??0 }}</span></td>
                    <td class="text-center"><span class="np np-sim">{{ $data['per_jalur']['simlitabkes']??0 }}</span></td>
                    <td class="text-center"><span class="np np-man">{{ $data['per_jalur']['mandiri']??0 }}</span></td>
                    <td>
                        @foreach($data['per_skema'] as $sk=>$jml)
                            <span class="skema-ch">{{ $sk }} <b>{{ $jml }}</b></span>
                        @endforeach
                    </td>
                    <td class="text-center">
                        <button class="btn-tog" id="btog-{{ $loop->index }}"
                                data-bs-toggle="collapse"
                                data-bs-target="#det-{{ $loop->index }}"
                                onclick="var el=document.getElementById('btog-{{ $loop->index }}');el.classList.toggle('on');var ic=el.querySelector('i');ic.className=el.classList.contains('on')?'bi bi-chevron-up':'bi bi-chevron-down'">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </td>
                </tr>
                <tr class="det-wrap">
                    <td colspan="9">
                        <div class="collapse" id="det-{{ $loop->index }}">
                            <div class="det-inner">
                                <div class="det-ih">
                                    <div class="det-ih-ic"><i class="bi bi-list-ul"></i></div>
                                    <span class="fw-bold text-success" style="font-size:13px;">{{ $jurusan }}</span>
                                    <span class="badge ms-2" style="background:#1a6b3c;color:white;font-size:10px;border-radius:6px;">{{ $data['total'] }} data</span>
                                </div>
                                <div class="p-3">
                                    <div class="table-responsive">
                                        <table class="table det-tbl mb-0" style="border-radius:10px;overflow:hidden">
                                            <thead>
                                                <tr>
                                                    <th width="35">No</th>
                                                    <th>Judul</th>
                                                    <th width="140">Ketua</th>
                                                    <th class="text-center" width="85">Jenis</th>
                                                    <th class="text-center" width="100">Jalur</th>
                                                    <th width="120">Skema</th>
                                                    <th class="text-center" width="65">Tahun</th>
                                                    <th class="text-center" width="100">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data['items'] as $idx=>$item)
                                                <tr>
                                                    <td class="text-muted">{{ $idx+1 }}</td>
                                                    <td title="{{ $item->judul }}">{{ Str::limit($item->judul,60) }}</td>
                                                    <td>{{ $item->pegawai?->nama??'-' }}</td>
                                                    <td class="text-center">
                                                        <span style="{{ $item->jenis==='penelitian'?'background:#e8f5e9;color:#1a6b3c':'background:#e8f0fd;color:#3949ab' }};padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600">
                                                            {{ ucfirst($item->jenis) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span style="{{ $item->jalur==='mandiri'?'background:#f3e5f5;color:#7b1fa2':'background:#fff3e0;color:#e65100' }};padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600">
                                                            {{ ucfirst($item->jalur) }}
                                                        </span>
                                                    </td>
                                                    <td><small class="text-muted">{{ $item->skema?->nama??'-' }}</small></td>
                                                    <td class="text-center fw-semibold">{{ $item->{$kolomTahun}??$item->created_at?->year }}</td>
                                                    <td class="text-center">
                                                        @php $stm=['proses'=>'st-p','disetujui'=>'st-d','revisi'=>'st-r'];$stl=['proses'=>'Dalam Proses','disetujui'=>'Disetujui','revisi'=>'Revisi']; @endphp
                                                        <span class="{{ $stm[$item->status]??'' }}">{{ $stl[$item->status]??ucfirst($item->status) }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="tfoot-tot">
                <tr>
                    <td colspan="2" class="ps-4 text-end" style="color:#1a6b3c;">GRAND TOTAL</td>
                    <td class="text-center"><span class="np np-tot">{{ $rekapJurusan->sum('total') }}</span></td>
                    <td class="text-center"><span class="np np-pen">{{ $rekapJurusan->sum(fn($d)=>$d['per_jenis']['penelitian']??0) }}</span></td>
                    <td class="text-center"><span class="np np-peng">{{ $rekapJurusan->sum(fn($d)=>$d['per_jenis']['pengabdian']??0) }}</span></td>
                    <td class="text-center"><span class="np np-sim">{{ $rekapJurusan->sum(fn($d)=>$d['per_jalur']['simlitabkes']??0) }}</span></td>
                    <td class="text-center"><span class="np np-man">{{ $rekapJurusan->sum(fn($d)=>$d['per_jalur']['mandiri']??0) }}</span></td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>

</div>
@endsection