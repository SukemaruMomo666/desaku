<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LetterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Pengantar Nikah',
                'code' => 'NA',
                'description' => 'Surat Pengantar Nikah (NA) diajukan sebagai salah satu syarat penting untuk mendaftarkan Pernikahan di KUA.',
                'requirements' => json_encode([
                    ['name' => 'Fotokopi KK calon pengantin laki-laki / perempuan', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi KTP calon pengantin laki-laki / Perempuan', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi KTP kedua orang tua calon pengantin laki-laki / Perempuan', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pengantar RT RW', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Keterangan Pengantar Nikah dari RT RW', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi Akta Kelahiran atau Ijazah calon pengantin', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pernyataan Permohonan Pengantar Nikah (Materai 10.000, lampiran KTP 2 saksi)', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi Akta Cerai dari Pengadilan (Bila pernah cerai)', 'type' => 'file', 'is_required' => false],
                    ['name' => 'Fotokopi Akta Kematian (Bila pasangan sebelumnya meninggal)', 'type' => 'file', 'is_required' => false],
                    ['name' => 'Fotokopi Akta Kematian orang tua (Bila orang tua meninggal)', 'type' => 'file', 'is_required' => false],
                    ['name' => 'Surat Keterangan Sehat dari Puskesmas/Dokter', 'type' => 'file', 'is_required' => true],
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Surat Keterangan Belum Memiliki Rumah',
                'code' => 'SKBMR',
                'description' => 'Surat keterangan yang menyatakan bahwa warga yang bersangkutan belum memiliki rumah tinggal pribadi.',
                'requirements' => json_encode([
                    ['name' => 'Fotokopi KTP', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi KK', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pengantar RT/RW', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pernyataan Belum Memiliki Rumah (Bermaterai)', 'type' => 'file', 'is_required' => true],
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Surat Keterangan Belum Menikah',
                'code' => 'SKBM',
                'description' => 'Surat keterangan untuk menyatakan bahwa warga yang bersangkutan berstatus belum pernah menikah.',
                'requirements' => json_encode([
                    ['name' => 'Fotokopi KTP', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi KK', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pengantar RT/RW', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pernyataan Belum Menikah (Bermaterai 10.000)', 'type' => 'file', 'is_required' => true],
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Surat Keterangan Ghoib',
                'code' => 'SKG',
                'description' => 'Surat keterangan yang menyatakan bahwa salah satu anggota keluarga (suami/istri) pergi meninggalkan rumah tanpa diketahui keberadaannya.',
                'requirements' => json_encode([
                    ['name' => 'Fotokopi KTP Pemohon', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi KK', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pengantar RT/RW', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi Buku Nikah', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pernyataan Kehilangan (Dari Kepolisian jika ada)', 'type' => 'file', 'is_required' => false],
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Surat Keterangan Janda atau Duda',
                'code' => 'SKJD',
                'description' => 'Surat keterangan untuk menerangkan status seseorang sebagai janda atau duda (baik cerai mati maupun cerai hidup).',
                'requirements' => json_encode([
                    ['name' => 'Fotokopi KTP', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi KK', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pengantar RT/RW', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi Akta Cerai / Akta Kematian Pasangan', 'type' => 'file', 'is_required' => true],
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Surat Keterangan Orang yang Sama',
                'code' => 'SKOYS',
                'description' => 'Surat keterangan untuk menjelaskan perbedaan identitas (nama, tanggal lahir, dll) pada dua dokumen yang berbeda milik satu orang yang sama.',
                'requirements' => json_encode([
                    ['name' => 'Fotokopi KTP', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi KK', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pengantar RT/RW', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi Dokumen Pertama (Misal: Ijazah)', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi Dokumen Kedua (Misal: Akta Kelahiran)', 'type' => 'file', 'is_required' => true],
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Surat Keterangan Penghasilan',
                'code' => 'SKP',
                'description' => 'Surat keterangan yang menjelaskan rincian penghasilan/gaji warga (biasanya bagi pekerja sektor informal/non-formal).',
                'requirements' => json_encode([
                    ['name' => 'Fotokopi KTP', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi KK', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pengantar RT/RW', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pernyataan Penghasilan per Bulan (Bermaterai)', 'type' => 'file', 'is_required' => true],
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Surat Keterangan Usaha',
                'code' => 'SKU',
                'description' => 'Surat Keterangan Usaha (SKU) digunakan sebagai bukti legalitas keberadaan suatu usaha milik warga di kelurahan.',
                'requirements' => json_encode([
                    ['name' => 'Fotokopi KTP', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Fotokopi KK', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Surat Pengantar RT/RW', 'type' => 'file', 'is_required' => true],
                    ['name' => 'Foto Tempat Usaha', 'type' => 'file', 'is_required' => true],
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert only if code does not exist
        foreach ($types as $type) {
            DB::table('letter_types')->updateOrInsert(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
