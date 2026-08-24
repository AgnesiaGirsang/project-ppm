<?php

namespace Database\Seeders;

use App\Models\LuaranMaster;
use App\Models\RumpunIlmu;
use App\Models\Skema;
use Illuminate\Database\Seeder;

class ReferensiSeeder extends Seeder
{
    public function run(): void
    {
        // ===== SKEMA =====
        $skemaList = [
            // Penelitian - Simlitabkes
            ['jenis' => 'penelitian', 'jalur' => 'simlitabkes', 'nama' => 'Penelitian Pemula (CPP)', 'kode' => 'CPP'],
            ['jenis' => 'penelitian', 'jalur' => 'simlitabkes', 'nama' => 'Penelitian Kerja Sama Antar Perguruan Tinggi (PKPT)', 'kode' => 'PKPT'],
            ['jenis' => 'penelitian', 'jalur' => 'simlitabkes', 'nama' => 'Penelitian Dasar Unggulan PT (PDUPT)', 'kode' => 'PDUPT'],
            ['jenis' => 'penelitian', 'jalur' => 'simlitabkes', 'nama' => 'Penelitian Terapan Unggulan PT (PTUPT)', 'kode' => 'PTUPT'],
            // Penelitian - Mandiri
            ['jenis' => 'penelitian', 'jalur' => 'mandiri', 'nama' => 'Penelitian Mandiri', 'kode' => null],
            // Pengabdian - Simlitabkes
            ['jenis' => 'pengabdian', 'jalur' => 'simlitabkes', 'nama' => 'Program Kemitraan Masyarakat (PKM)', 'kode' => 'PKM'],
            ['jenis' => 'pengabdian', 'jalur' => 'simlitabkes', 'nama' => 'Program Kemitraan Wilayah (PKW)', 'kode' => 'PKW'],
            ['jenis' => 'pengabdian', 'jalur' => 'simlitabkes', 'nama' => 'Program Pengembangan Desa Mitra (PPDM)', 'kode' => 'PPDM'],
            // Pengabdian - Mandiri
            ['jenis' => 'pengabdian', 'jalur' => 'mandiri', 'nama' => 'Pengabdian Kepada Masyarakat Mandiri', 'kode' => null],
        ];
        foreach ($skemaList as $s) {
            Skema::create($s + ['aktif' => true]);
        }

        // ===== RUMPUN ILMU =====
        foreach (['Ilmu Biomedik', 'Ilmu Keperawatan', 'Ilmu Kebidanan', 'Ilmu Kesehatan Masyarakat', 'Ilmu Gizi', 'Ilmu Teknologi Informasi Kesehatan'] as $nama) {
            RumpunIlmu::create(['nama' => $nama]);
        }

        // ===== LUARAN MASTER =====
        // Wajib
        LuaranMaster::create([
            'jenis' => 'penelitian', 'nama' => 'Artikel ilmiah dimuat di jurnal', 'wajib' => true,
            'opsi' => ['Nasional', 'Nasional Terakreditasi', 'Internasional', 'Internasional Bereputasi'],
        ]);
        LuaranMaster::create([
            'jenis' => 'pengabdian', 'nama' => 'Artikel ilmiah dimuat di media', 'wajib' => true,
            'opsi' => ['Cetak/Elektronik', 'Jurnal Pengabdian'],
        ]);

        // Tambahan (penelitian)
        $tambahanPenelitian = [
            ['nama' => 'Artikel ilmiah dimuat di prosiding', 'opsi' => ['Nasional', 'Internasional']],
            ['nama' => 'Kekayaan Intelektual (KI)', 'opsi' => ['Hak Cipta', 'Paten', 'Paten Sederhana', 'Merek']],
            ['nama' => 'Buku ber-ISBN', 'opsi' => null],
            ['nama' => 'Book-chapter ber-ISBN', 'opsi' => null],
            ['nama' => 'Dokumen hasil uji coba produk', 'opsi' => null],
            ['nama' => 'Dokumen feasibility study', 'opsi' => null],
            ['nama' => 'Business plan', 'opsi' => null],
            ['nama' => 'Naskah akademik (policy brief, rekomendasi kebijakan, atau model kebijakan strategis)', 'opsi' => null],
            ['nama' => 'Naskah kebijakan', 'opsi' => null],
        ];
        foreach ($tambahanPenelitian as $t) {
            LuaranMaster::create(['jenis' => 'penelitian', 'nama' => $t['nama'], 'wajib' => false, 'opsi' => $t['opsi']]);
        }

        // Tambahan (pengabdian)
        $tambahanPengabdian = [
            ['nama' => 'Peningkatan kapasitas mitra (pengetahuan/keterampilan)', 'opsi' => null],
            ['nama' => 'Buku ber-ISBN', 'opsi' => null],
            ['nama' => 'Video kegiatan', 'opsi' => null],
            ['nama' => 'Kekayaan Intelektual (KI)', 'opsi' => ['Hak Cipta', 'Paten', 'Paten Sederhana', 'Merek']],
            ['nama' => 'Naskah kebijakan', 'opsi' => null],
        ];
        foreach ($tambahanPengabdian as $t) {
            LuaranMaster::create(['jenis' => 'pengabdian', 'nama' => $t['nama'], 'wajib' => false, 'opsi' => $t['opsi']]);
        }
    }
}
