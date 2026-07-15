<?php

namespace App\Services;

use App\Models\Tim;
use Illuminate\Database\Eloquent\Collection;

class TeamService
{
    public function updateStatus(Tim $tim, string $status): Tim
    {
        $tim->status = $status;
        $tim->save();
        return $tim;
    }

    public function getAllTeams(): Collection
    {
        return Tim::with('cabangLomba')->get();
    }

    public function getTeamById(int $id): ?Tim
    {
        return Tim::with('cabangLomba')->find($id);
    }
}
