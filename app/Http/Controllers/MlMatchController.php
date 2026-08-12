<?php

namespace App\Http\Controllers;

use App\Models\MlMatch;
use App\Services\TournamentService;
use Illuminate\Http\Request;

class MlMatchController extends Controller
{
    protected $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService = $tournamentService;
    }

    public function index()
    {
        return $this->successResponse(
            MlMatch::with(['tim1', 'tim2', 'winner'])
                ->orderBy('round')
                ->orderBy('id')
                ->get()
        );
    }

    public function show($id)
    {
        $data = MlMatch::with(['tim1', 'tim2', 'winner'])->find($id);

        if (!$data) {
            return $this->errorResponse('Data not found', 404);
        }

        return $this->successResponse($data);
    }

    public function generateBracket(Request $request)
    {
        $validated = $request->validate([
            'cabang_lomba_id' => 'required|exists:cabang_lombas,id',
            'shuffle'         => 'boolean'
        ]);

        try {
            $matches = $this->tournamentService->generateSingleEliminationBracket(
                $validated['cabang_lomba_id'],
                $validated['shuffle'] ?? true
            );
            return $this->successResponse($matches, 'Bracket berhasil dibuat', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function updateScore(Request $request, $id)
    {
        $validated = $request->validate([
            'tim1_score' => 'required|integer|min:0',
            'tim2_score' => 'required|integer|min:0'
        ]);

        $match = $this->tournamentService->updateScore($id, $validated['tim1_score'], $validated['tim2_score']);
        
        return $this->successResponse($match, 'Skor berhasil diupdate');
    }
}
