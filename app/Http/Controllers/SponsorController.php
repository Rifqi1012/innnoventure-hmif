<?php

namespace App\Http\Controllers;

use App\Models\External;
use Illuminate\Http\Request;

class SponsorController extends Controller
{

    public function getAllSponsorsByType()
    {
        $sponsors = External::where('jenis', 'sponsor')
                            ->where('is_aktif', true)
                            ->get();

        return response()->json([
            'code'    => 200,
            'message' => 'success',
            'payload' => $sponsors
        ]);
    }
    public function getSponsorById(int $id)
    {
        $sponsor = External::where('jenis', 'sponsor')
                           ->where('is_aktif', true)
                           ->find($id);
                           
        if (!$sponsor) {
            return response()->json([
                'code'    => 404,
                'message' => 'Sponsor tidak ditemukan atau tidak aktif',
                'payload' => null
            ], 404);
        }

        return response()->json([
            'code'    => 200,
            'message' => 'success',
            'payload' => $sponsor
        ]);
    }
}

