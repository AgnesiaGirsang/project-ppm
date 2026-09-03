<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan 2 - Detail Penelitian</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            font-size: 8.5px;
            color: #333;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1a6b3c;
        }
        .header h2 {
            font-size: 13px;
            color: #1a6b3c;
            margin-bottom: 3px;
        }
        .header p {
            font-size: 9px;
            color: #666;
        }
        .filter-info {
            background: #f1f8e9;
            border: 1px solid #c5e1a5;
            border-left: 4px solid #1a6b3c;
            padding: 5px 10px;
            margin-bottom: 10px;
            font-size: 8px;
            border-radius: 3px;
        }
        .filter-info strong { color: #1a6b3c; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th {
            background-color: #1a6b3c;
            color: white;
            padding: 6px 5px;
            text-align: center;
            font-size: 8px;
            border: 1px solid #145a32;
        }
        td {
            padding: 5px 4px;
            border: 1px solid #e0e0e0;
            vertical-align: top;
            font-size: 8px;
            line-height: 1.4;
        }
        tr:nth-child(even) td {
            background-color: #f9fbe7;
        }
        tr:nth-child(odd) td {
            background-color: #ffffff;
        }
        .badge-penelitian {
            background: #e8f5e9;
            color: #1a6b3c;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        .badge-pengabdian {
            background: #e3f2fd;
            color: #1565c0;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        .badge-jalur {
            background: #fff3e0;
            color: #e65100;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-muted { color: #9e9e9e; }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #9e9e9e;
            font-style: italic;
        }
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #e0e0e0;
            font-size: 8px;
            color: #9e9e9e;
            display: flex;
            justify-content: space-between;
        }
        .total-info {
            background: #e8f5e9;
            border: 1px solid #a5d6a7;
            padding: 5px 10px;
            margin-bottom: 10px;
            border-radius: 3px;
            font-size: 9px;
            color: #1a6b3c;
            font-weight: bold;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h2>LAPORAN 2 – DETAIL PENELITIAN DAN CAPAIAN LUARAN</h2>
        <p>Sistem Informasi Penelitian & Pengabdian – Poltekkes Kemenkes</p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Filter Info --}}
    @if(array_filter($filters, fn($v) => !empty($v) && $v !== null))
    <div class="filter-info">
        <strong>Filter yang diterapkan:</strong>
        @if(!empty($filters['tahun']))
            &nbsp;Tahun: <strong>{{ $filters['tahun'] }}</strong>
        @endif
        @if(!empty($filters['jurusan']))
            &nbsp;| Jurusan: <strong>{{ $filters['jurusan'] }}</strong>
        @endif
        @if(!empty($filters['jenis']))
            &nbsp;| Jenis: <strong>{{ ucfirst($filters['jenis']) }}</strong>
        @endif
        @if(!empty($filters['jalur']))
            &nbsp;| Jalur: <strong>{{ ucfirst($filters['jalur']) }}</strong>
        @endif
    </div>
    @endif

    {{-- Total Info --}}
    <div class="total-info">
        Total Data: {{ $data->count() }} penelitian/pengabdian
    </div>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th width="18">No</th>
                <th width="32">Tahun</th>
                <th width="120">Judul</th>
                <th width="75">Ketua Peneliti</th>
                <th width="65">Jurusan</th>
                <th width="75">Anggota Tim</th>
                <th width="52">Jenis</th>
                <th width="50">Jalur</th>
                <th width="60">Skema</th>
                <th width="95">Luaran Diusulkan</th>
                <th width="95">Luaran Tercapai</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;

                /**
                 * Helper: konversi nilai luaran (string/array/null) → string aman
                 */
                $toLuaranStr = function($val): string {
                    if (is_null($val)) {
                        return '-';
                    }
                    if (is_string($val)) {
                        return $val === '' ? '-' : $val;
                    }
                    if (is_array($val)) {
                        $parts = [];
                        foreach ($val as $item) {
                            if (is_array($item)) {
                                $parts[] = $item['nama']
                                    ?? $item['jenis']
                                    ?? $item['judul']
                                    ?? $item['keterangan']
                                    ?? implode(', ', array_map('strval', array_values($item)));
                            } else {
                                $parts[] = (string) $item;
                            }
                        }
                        return implode('; ', array_filter($parts)) ?: '-';
                    }
                    // integer, float, dll
                    return (string) $val;
                };

                $kolomTahun = $filters['kolomTahun'] ?? 'tahun_pengajuan';
            @endphp

            @forelse($data as $p)
            @php
                $lk = $p->laporanKemajuan;

                // Cari nilai luaran diusulkan
                $rawDiusulkan = null;
                if ($lk) {
                    $rawDiusulkan = $lk->luaran_diusulkan
                        ?? $lk->target_luaran
                        ?? $lk->luaran
                        ?? null;
                }
                $diusulkan = $toLuaranStr($rawDiusulkan);

                // Cari nilai luaran tercapai
                $rawTercapai = null;
                if ($lk) {
                    $rawTercapai = $lk->luaran_tercapai
                        ?? $lk->capaian_luaran
                        ?? $lk->capaian
                        ?? null;
                }
                $tercapai = $toLuaranStr($rawTercapai);

                // Anggota tim
                $anggotaList = $p->anggotas
                    ->map(fn($a) => $a->pegawai?->nama ?? '-')
                    ->filter()
                    ->values();

                // Nilai tahun
                $tahunVal = '-';
                try {
                    $tahunVal = $p->{$kolomTahun} ?? $p->created_at?->year ?? '-';
                } catch (\Exception $e) {
                    $tahunVal = $p->created_at?->year ?? '-';
                }

                // Pastikan semua string
                $judulStr    = is_string($p->judul) ? $p->judul : (string)($p->judul ?? '-');
                $ketuaNama   = is_string($p->pegawai?->nama) ? $p->pegawai->nama : '-';
                $ketuaJurusan = is_string($p->pegawai?->jurusan) ? $p->pegawai->jurusan : '-';
                $jenisStr    = is_string($p->jenis) ? $p->jenis : '-';
                $jalurStr    = is_string($p->jalur) ? $p->jalur : '-';
                $skemaNama   = is_string($p->skema?->nama) ? $p->skema->nama : '-';
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $tahunVal }}</td>
                <td>{{ $judulStr }}</td>
                <td>{{ $ketuaNama }}</td>
                <td>{{ $ketuaJurusan }}</td>
                <td>
                    @if($anggotaList->isEmpty())
                        <span class="text-muted">-</span>
                    @else
                        @foreach($anggotaList as $nama)
                            {{ $nama }}@if(!$loop->last)<br>@endif
                        @endforeach
                    @endif
                </td>
                <td class="text-center">
                    <span class="{{ $jenisStr === 'penelitian' ? 'badge-penelitian' : 'badge-pengabdian' }}">
                        {{ ucfirst($jenisStr) }}
                    </span>
                </td>
                <td class="text-center">
                    <span class="badge-jalur">{{ ucfirst($jalurStr) }}</span>
                </td>
                <td>{{ $skemaNama }}</td>
                <td>
                    @if($lk && $diusulkan !== '-')
                        {{ $diusulkan }}
                    @else
                        <span class="text-muted">
                            {{ $lk ? 'Belum diisi' : 'Belum ada laporan' }}
                        </span>
                    @endif
                </td>
                <td>
                    @if($lk && $tercapai !== '-')
                        {{ $tercapai }}
                    @else
                        <span class="text-muted">
                            {{ $lk ? 'Belum diisi' : '-' }}
                        </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="no-data">
                    Tidak ada data yang ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <span>
            Total: {{ $data->count() }} data
            @if(!empty($filters['tahun'])) | Tahun: {{ $filters['tahun'] }} @endif
        </span>
        <span>Dicetak: {{ now()->format('d/m/Y H:i') }}</span>
    </div>

</body>
</html>