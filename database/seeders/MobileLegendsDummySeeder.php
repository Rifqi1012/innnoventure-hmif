<?php

namespace Database\Seeders;

use App\Models\CabangLomba;
use App\Models\Instansi;
use App\Models\MlMatch;
use App\Models\Tim;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MobileLegendsDummySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $mobileLegends = CabangLomba::updateOrCreate(
                ['nama' => 'Mobile Legends'],
                [
                    'jenis_penilaian' => 'vs',
                    'tanggal_mulai' => '2026-08-15',
                    'tanggal_berakhir' => '2026-08-17',
                ]
            );

            // Match data belongs exclusively to the Mobile Legends bracket.
            MlMatch::query()->delete();
            Tim::query()->where('cabang_lomba_id', $mobileLegends->id)->delete();

            $teamNames = [
                'Garuda Esports', 'Rajawali Gaming', 'Nusantara Titans', 'Bandung Warriors',
                'Jakarta Phoenix', 'West Java Legends', 'Cyber Knights', 'Digital Wolves',
                'Inferno Esports', 'Thunder Squad', 'Velocity Gaming', 'Shadow Reapers',
                'Nova Titans', 'Crimson Wolves', 'Eclipse Gaming', 'Alpha Hunters',
                'Omega Esports', 'Sky Guardians', 'Night Raiders', 'Storm Breakers',
                'Phoenix Reborn', 'Titan Force', 'Zero Gravity', 'Quantum Gaming',
                'Dark Horizon', 'Eternal Squad', 'Red Vipers', 'Blue Dragons',
                'Golden Eagles', 'Black Panthers', 'Neon Warriors', 'Silent Killers',
                'Vortex Gaming', 'Hypernova', 'Lunar Esports', 'Solar Knights',
                'Mystic Wolves', 'Frost Giants', 'Firestorm Gaming', 'Iron Legion',
                'Royal Guardians', 'Cyber Dragons', 'Infinity Gaming', 'Genesis Esports',
                'Legacy Titans', 'Prime Hunters', 'Vanguard Gaming', 'Spectre Squad',
                'Zenith Esports', 'Horizon Gaming', 'Blitz Warriors', 'Arcane Knights',
                'Predator Gaming', 'Spartan Esports', 'Valkyrie Squad', 'Dominion Gaming',
                'Rebellion Esports', 'Evolution Gaming', 'Astral Knights', 'Final Boss',
            ];

            $cities = [
                'Bandung', 'Jakarta', 'Surabaya', 'Yogyakarta', 'Semarang',
                'Malang', 'Bogor', 'Depok', 'Bekasi', 'Tangerang',
                'Medan', 'Padang', 'Palembang', 'Pekanbaru', 'Lampung',
                'Pontianak', 'Banjarmasin', 'Balikpapan', 'Makassar', 'Manado',
            ];
            $institutionTypes = ['Universitas Teknologi', 'Institut Digital', 'Politeknik Informatika'];
            $institutions = [];
            $teams = [];

            foreach ($teamNames as $index => $teamName) {
                $institutionName = $institutionTypes[intdiv($index, count($cities))]
                    .' '.$cities[$index % count($cities)].' Nusantara';

                $institution = Instansi::updateOrCreate(
                    ['nama' => $institutionName],
                    ['logo' => 'logo.png']
                );
                $institutions[] = $institution;

                $teams[] = Tim::create([
                    'cabang_lomba_id' => $mobileLegends->id,
                    'nama' => $teamName,
                    'instansi_id' => $institution->id,
                    'status' => 'Approved',
                ]);
            }

            $schedule = Carbon::parse('2026-08-15 09:00:00');
            $finishedScores = [[2, 0], [1, 2], [2, 1], [0, 2]];
            $liveScores = [[1, 0], [0, 1]];

            // 56 teams play 28 matches; the remaining four teams receive BYEs.
            for ($matchIndex = 0; $matchIndex < 28; $matchIndex++) {
                $startTime = $schedule->copy()->addHours($matchIndex);
                $status = 'upcoming';
                $scores = [0, 0];

                if ($matchIndex < count($finishedScores)) {
                    $status = 'finished';
                    $scores = $finishedScores[$matchIndex];
                } elseif ($matchIndex < count($finishedScores) + count($liveScores)) {
                    $status = 'live';
                    $scores = $liveScores[$matchIndex - count($finishedScores)];
                }

                MlMatch::create([
                    'round' => 1,
                    'tim1_id' => $teams[$matchIndex * 2]->id,
                    'tim2_id' => $teams[($matchIndex * 2) + 1]->id,
                    'best_of' => 3,
                    'tim1_score' => $scores[0],
                    'tim2_score' => $scores[1],
                    'status' => $status,
                    'start_time' => $startTime,
                    'end_time' => $status === 'finished' ? $startTime->copy()->addMinutes(45) : null,
                ]);
            }

            for ($byeIndex = 0; $byeIndex < 4; $byeIndex++) {
                $startTime = $schedule->copy()->addHours(28 + $byeIndex);

                MlMatch::create([
                    'round' => 1,
                    'tim1_id' => $teams[56 + $byeIndex]->id,
                    'tim2_id' => null,
                    'best_of' => 3,
                    'tim1_score' => 2,
                    'tim2_score' => 0,
                    'status' => 'finished',
                    'start_time' => $startTime,
                    'end_time' => $startTime,
                ]);
            }
        });
    }
}
