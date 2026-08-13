<?php

namespace Database\Seeders;

use App\Models\AspekPenilaian;
use App\Models\CabangLomba;
use Illuminate\Database\Seeder;

class UiUxAspekPenilaianSeeder extends Seeder
{
    public function run(): void
    {
        $uiUx = CabangLomba::firstOrCreate(
            ['nama' => 'UI/UX'],
            [
                'jenis_penilaian' => 'juri',
                'tanggal_mulai' => '2026-08-01',
                'tanggal_berakhir' => '2026-08-31',
            ]
        );

        $aspekList = [
            ['nama' => 'Kesesuaian tema dan konsep', 'bobot_penilaian' => 15],
            ['nama' => 'Visual hierarchy dan tataletak', 'bobot_penilaian' => 25],
            ['nama' => 'Harmonisasi warna / color palette', 'bobot_penilaian' => 15],
            ['nama' => 'Typography', 'bobot_penilaian' => 15],
            ['nama' => 'Kekayaan komponen', 'bobot_penilaian' => 15],
            ['nama' => 'Estetika dan keunikan visual', 'bobot_penilaian' => 15],
        ];

        foreach ($aspekList as $aspek) {
            AspekPenilaian::updateOrCreate(
                [
                    'id_cabang_lomba' => $uiUx->id,
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
