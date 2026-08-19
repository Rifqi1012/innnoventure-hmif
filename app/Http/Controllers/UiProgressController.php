<?php

namespace App\Http\Controllers;

use App\Models\UiProgress;
use App\Services\CompetitionService;
use Illuminate\Http\Request;

class UiProgressController extends Controller
{
    protected $competitionService;

    public function __construct(CompetitionService $competitionService)
    {
        $this->competitionService = $competitionService;
    }

    public function index()
    {
        $allProgresses = UiProgress::with(['tim.instansi'])->get();
        return $this->successResponse($allProgresses);
    }

    public function show($id)
    {
        $progress = UiProgress::with('tim.instansi')->find($id);

        if (!$progress) {
            return $this->errorResponse('Data not found', 404);
        }

        return $this->successResponse($progress);
    }

    public function store(Request $request)
    {
        return $this->errorResponse('Mohon maaf, waktu pengumpulan proyek UI/UX sudah ditutup.', 403);

        $validated = $request->validate([
            'tim_id'          => 'required|exists:tims,id|unique:ui_progress,tim_id',
            'email_ketua'     => 'required|email|unique:ui_progress,email_ketua',
            'judul_proyek'    => 'required|string|max:255',
            'link_figma'      => 'nullable|url',
            'pdf'             => 'nullable|file|extensions:pdf|max:51200',
            'ppt'             => 'nullable|file|extensions:ppt,pptx,pdf|max:51200',
        ], [
            'tim_id.unique' => 'Tim Anda sudah melakukan pengumpulan sebelumnya. Pengumpulan hanya dapat dilakukan 1 kali.',
            'email_ketua.unique' => 'Email ketua sudah digunakan untuk pengumpulan sebelumnya.',
        ]);

        $progress = $this->competitionService->submitUi($validated);

        return $this->successResponse($progress->load('tim.instansi'), 'Data berhasil disimpan', 201);
    }
}
