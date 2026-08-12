<?php

namespace App\Services;

use App\Models\MlMatch;
use App\Models\Tim;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TournamentService
{
    public function generateSingleEliminationBracket(int $cabangLombaId, bool $shuffle = true): Collection
    {
        $teams = Tim::query()
            ->where('cabang_lomba_id', $cabangLombaId)
            ->where('status', 'Approved')
            ->get();

        if ($teams->count() < 2) {
            throw new RuntimeException('Minimal 2 tim untuk membuat bracket.');
        }

        if ($shuffle) {
            $teams = $teams->shuffle()->values();
        }

        return DB::transaction(function () use ($teams): Collection {
            // DELETE remains transactional, unlike TRUNCATE on MySQL.
            MlMatch::query()->delete();

            $bracketSize = 1;
            while ($bracketSize < $teams->count()) {
                $bracketSize *= 2;
            }

            $slots = $teams->concat(array_fill(0, $bracketSize - $teams->count(), null))->values();
            $roundCount = (int) log($bracketSize, 2);
            $created = collect();

            for ($round = 1; $round <= $roundCount; $round++) {
                $matchCount = (int) ($bracketSize / (2 ** $round));

                for ($position = 0; $position < $matchCount; $position++) {
                    $tim1 = $round === 1 ? $slots[$position * 2] : null;
                    $tim2 = $round === 1 ? $slots[($position * 2) + 1] : null;
                    $winnerId = $tim1 && ! $tim2 ? $tim1->id : null;
                    $emptyMatch = $round === 1 && ! $tim1 && ! $tim2;

                    $match = MlMatch::withoutEvents(fn () => MlMatch::create([
                        'round' => $round,
                        'tim1_id' => $tim1?->id,
                        'tim2_id' => $tim2?->id,
                        'best_of' => $round === $roundCount ? 5 : 3,
                        'tim1_score' => $winnerId ? 1 : 0,
                        'tim2_score' => 0,
                        'status' => ($winnerId || $emptyMatch) ? 'finished' : 'upcoming',
                        'winner_id' => $winnerId,
                    ]));

                    $created->push($match);
                }
            }

            // Seed every automatic bye into its next match.
            foreach ($created->whereNotNull('winner_id')->sortBy('round') as $match) {
                $this->advanceWinner($match);
            }

            return MlMatch::with(['tim1', 'tim2', 'winner'])
                ->orderBy('round')
                ->orderBy('id')
                ->get();
        });
    }

    public function updateScore(int $matchId, int $tim1Score, int $tim2Score): MlMatch
    {
        return DB::transaction(function () use ($matchId, $tim1Score, $tim2Score): MlMatch {
            $match = MlMatch::lockForUpdate()->findOrFail($matchId);
            $match->tim1_score = $tim1Score;
            $match->tim2_score = $tim2Score;

            if ($match->status !== 'finished') {
                $match->status = 'live';
            }

            $match->save();

            if ($match->winner_id) {
                $this->advanceWinner($match);
            }

            return $match->fresh(['tim1', 'tim2', 'winner']);
        });
    }

    private function advanceWinner(MlMatch $match): void
    {
        $currentRound = MlMatch::query()
            ->where('round', $match->round)
            ->orderBy('id')
            ->pluck('id');
        $position = $currentRound->search($match->id);

        if ($position === false) {
            return;
        }

        $nextMatch = MlMatch::query()
            ->where('round', $match->round + 1)
            ->orderBy('id')
            ->get()
            ->get(intdiv($position, 2));

        if (! $nextMatch) {
            return; // The winner of the last round is the champion.
        }

        $slot = $position % 2 === 0 ? 'tim1_id' : 'tim2_id';
        $nextMatch->{$slot} = $match->winner_id;
        $nextMatch->tim1_score = 0;
        $nextMatch->tim2_score = 0;
        $nextMatch->winner_id = null;
        $nextMatch->status = 'upcoming';
        $nextMatch->saveQuietly();

        // If only one feeder exists, carry that team through the empty side.
        $otherSlot = $slot === 'tim1_id' ? 'tim2_id' : 'tim1_id';
        $otherFeederPosition = $position % 2 === 0 ? $position + 1 : $position - 1;
        $otherFeeder = $currentRound->get($otherFeederPosition);
        $otherFeederFinished = $otherFeeder
            ? MlMatch::query()->whereKey($otherFeeder)->where('status', 'finished')->exists()
            : true;

        if ($otherFeederFinished && ! $nextMatch->{$otherSlot}) {
            $nextMatch->winner_id = $nextMatch->{$slot};
            $nextMatch->status = 'finished';
            $nextMatch->saveQuietly();
            $this->advanceWinner($nextMatch);
        }
    }
}
