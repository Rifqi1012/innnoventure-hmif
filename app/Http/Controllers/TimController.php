<?php

namespace App\Http\Controllers;

use App\Services\TeamService;
use App\Models\Tim;
use Illuminate\Http\Request;

class TimController extends Controller
{
    protected $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function index()
    {
        $data = $this->teamService->getAllTeams();
        return $this->successResponse($data);
    }

    public function show($id)
    {
        $data = $this->teamService->getTeamById($id);

        if (!$data) {
            return $this->errorResponse('Data not found', 404);
        }

        return $this->successResponse($data);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,Need Revision'
        ]);

        $team = Tim::find($id);

        if (!$team) {
            return $this->errorResponse('Data not found', 404);
        }

        $updatedTeam = $this->teamService->updateStatus($team, $validated['status']);
        
        return $this->successResponse($updatedTeam, 'Status updated successfully');
    }
}
