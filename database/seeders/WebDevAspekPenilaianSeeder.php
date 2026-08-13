<?php

namespace Database\Seeders;

use App\Models\AspekPenilaian;
use App\Models\CabangLomba;
use Illuminate\Database\Seeder;

class WebDevAspekPenilaianSeeder extends Seeder
{
    public function run(): void
    {
        $webDev = CabangLomba::firstOrCreate(
            ['nama' => 'WEB DEVELOPMENT'],
            ['jenis_penilaian' => 'karya']
        );

        $aspekList = [
            ['nama' => 'Fungsionalitas Website', 'bobot_penilaian' => 30],
            ['nama' => 'Design Interface', 'bobot_penilaian' => 25],
            ['nama' => 'Struktur Code dan Dokumentasi', 'bobot_penilaian' => 15],
            ['nama' => 'Kreatifitas dan Inovasi', 'bobot_penilaian' => 15],
            ['nama' => 'Keaslian Proyek', 'bobot_penilaian' => 15],
        ];

        foreach ($aspekList as $aspek) {
            AspekPenilaian::updateOrCreate(
                [
                    'id_cabang_lomba' => $webDev->id,
                    'nama' => $aspek['nama'],
                ],
                [
                    'bobot_penilaian' => $aspek['bobot_penilaian'],
                    'keterangan' => 'Penilaian untuk aspek ' . $aspek['nama'],
                ]
            );
        }
    }
}
