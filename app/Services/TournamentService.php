<?php

namespace App\Services;

use App\Models\Tim;
use App\Models\MlMatch;
use Illuminate\Support\Facades\DB;

class TournamentService
{
    public function generateSingleEliminationBracket(int $cabangLombaId, bool $shuffle = true)
    {
        // Ambil semua tim yang sudah Approved di cabang lomba tersebut
        $teams = Tim::where('cabang_lomba_id', $cabangLombaId)
                    ->where('status', 'Approved')
                    ->get();

        if ($teams->count() < 2) {
            throw new \Exception("Minimal 2 tim untuk membuat bracket.");
        }

        if ($shuffle) {
            $teams = $teams->shuffle();
        }

        $matches = [];
        $round = 1;

        DB::beginTransaction();
        try {
            // Hapus bracket lama jika mau generate ulang (bisa diubah logic-nya sesuai kebutuhan)
            MlMatch::truncate();

            // Pasangkan dua-dua
            for ($i = 0; $i < $teams->count(); $i += 2) {
                $tim1 = $teams[$i];
                // Jika ganjil dan ini tim terakhir, dia dapat 'bye' (otomatis menang)
                $tim2 = isset($teams[$i + 1]) ? $teams[$i + 1] : null;

                $match = MlMatch::create([
                    'round' => $round,
                    'tim1_id' => $tim1->id,
                    'tim2_id' => $tim2 ? $tim2->id : null,
                    'best_of' => 3,
                    'tim1_score' => $tim2 ? 0 : 2, // Menang WO jika bye
                    'tim2_score' => 0,
                    'status' => $tim2 ? 'upcoming' : 'finished',
                    'winner_id' => $tim2 ? null : $tim1->id
                ]);

                $matches[] = $match;
            }
            DB::commit();

            return $matches;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateScore(int $matchId, int $tim1Score, int $tim2Score)
    {
        $match = MlMatch::findOrFail($matchId);
        
        $match->tim1_score = $tim1Score;
        $match->tim2_score = $tim2Score;
        
        if ($match->status !== 'finished') {
            $match->status = 'live';
        }

        $match->save(); // Event 'saving' di model akan mengecek winner

        return $match;
    }
}
