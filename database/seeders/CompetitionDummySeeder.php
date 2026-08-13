<?php

namespace Database\Seeders;

use App\Models\CabangLomba;
use App\Models\Instansi;
use App\Models\Tim;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetitionDummySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $webDev = CabangLomba::firstOrCreate(
                ['nama' => 'WEB DEVELOPMENT'],
                [
                    'jenis_penilaian' => 'juri',
                    'tanggal_mulai' => '2026-08-01',
                    'tanggal_berakhir' => '2026-08-31',
                ]
            );

            $uiUx = CabangLomba::firstOrCreate(
                ['nama' => 'UI/UX'],
                [
                    'jenis_penilaian' => 'juri',
                    'tanggal_mulai' => '2026-08-01',
                    'tanggal_berakhir' => '2026-08-31',
                ]
            );
            
            $ml = CabangLomba::firstOrCreate(
                ['nama' => 'MOBILE LEGENDS'],
                [
                    'jenis_penilaian' => 'vs',
                    'tanggal_mulai' => '2026-08-01',
                    'tanggal_berakhir' => '2026-08-31',
                ]
            );

            // Create 10 institutions
            $institutions = [];
            for ($i = 1; $i <= 10; $i++) {
                $institutions[] = Instansi::firstOrCreate(
                    ['nama' => 'Universitas ' . $i],
                    ['logo' => 'logo' . $i . '.png']
                );
            }

            // Create 10 teams for each
            for ($i = 1; $i <= 10; $i++) {
                Tim::create([
                    'cabang_lomba_id' => $webDev->id,
                    'nama' => 'WebDev Team ' . $i,
                    'instansi_id' => $institutions[$i - 1]->id,
                    'status' => 'Approved',
                ]);

                Tim::create([
                    'cabang_lomba_id' => $uiUx->id,
                    'nama' => 'UI/UX Team ' . $i,
                    'instansi_id' => $institutions[$i - 1]->id,
                    'status' => 'Approved',
                ]);
            }
        });
    }
}
