<?php

namespace App\Http\Controllers;

use App\Models\WebdevProgress;
use App\Services\CompetitionService;
use Illuminate\Http\Request;

class WebDevController extends Controller
{
    protected $competitionService;

    public function __construct(CompetitionService $competitionService)
    {
        $this->competitionService = $competitionService;
    }

    public function index(Request $request)
    {
        $allProgresses = WebdevProgress::with(['tim.instansi'])->get();
        return $this->successResponse($allProgresses);
    }

    public function show($id)
    {
        $progress = WebdevProgress::with('tim.instansi')->find($id);

        if (!$progress) {
            return $this->errorResponse('Data not found', 404);
        }

        return $this->successResponse($progress);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tim_id'          => 'required|exists:tims,id',
            'email_ketua'     => 'required|email|unique:webdev_progress,email_ketua',
            'judul_proyek'    => 'required|string|max:255',
            'link_github'     => 'nullable|url',
            'link_hosting'    => 'nullable|url',
            'pdf'             => 'nullable|file|extensions:pdf|max:51200',
            'ppt'             => 'nullable|file|extensions:ppt,pptx,pdf|max:51200',
        ]);

        $progress = $this->competitionService->submitWebdev($validated);

        return $this->successResponse($progress->load('tim.instansi'), 'Data berhasil disimpan', 201);
    }
}
