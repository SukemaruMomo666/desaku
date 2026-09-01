<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LetterType;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        User::updateOrCreate(
            ['nik' => '1234567890123456'],
            [
                'name' => 'Admin Kelurahan',
                'phone' => '081234567890',
                'role' => 'admin',
                'password' => Hash::make('AdminKelurahan123'),
            ]
        );

        // Create Default Letter Types
        LetterType::updateOrCreate(
            ['code' => 'SKTM'],
            [
                'name' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'description' => 'Digunakan untuk keperluan permohonan beasiswa, keringanan biaya rumah sakit, dll.',
                'requirements' => json_encode([
                    'Foto KTP asli',
                    'Foto KK asli',
                    'Surat Pengantar RT/RW'
                ]),
                'is_active' => true
            ]
        );

        LetterType::updateOrCreate(
            ['code' => 'SKU'],
            [
                'name' => 'Surat Keterangan Usaha (SKU)',
                'description' => 'Digunakan untuk pengajuan kredit usaha, perizinan, atau bantuan pemerintah.',
                'requirements' => json_encode([
                    'Foto KTP asli',
                    'Foto KK asli',
                    'Foto Bukti Tempat Usaha',
                    'Surat Pengantar RT/RW'
                ]),
                'is_active' => true
            ]
        );
    }
}
