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
        return $this->errorResponse('Mohon maaf, waktu pengumpulan proyek Web Development sudah ditutup.', 403);

        $validated = $request->validate([
            'tim_id'          => 'required|exists:tims,id|unique:webdev_progress,tim_id',
            'email_ketua'     => 'required|email|unique:webdev_progress,email_ketua',
            'judul_proyek'    => 'required|string|max:255',
            'link_github'     => 'required|url',
            'link_demo'       => 'required|url',
            'link_hosting'    => 'nullable|url',
            'pdf'             => 'required|file|extensions:pdf|max:51200',
            'ppt'             => 'required|file|extensions:ppt,pptx,pdf|max:51200',
        ], [
            'tim_id.unique' => 'Tim Anda sudah melakukan pengumpulan sebelumnya. Pengumpulan hanya dapat dilakukan 1 kali.',
            'email_ketua.unique' => 'Email ketua sudah digunakan untuk pengumpulan sebelumnya.',
        ]);

        $progress = $this->competitionService->submitWebdev($validated);

        return $this->successResponse($progress->load('tim.instansi'), 'Data berhasil disimpan', 201);
    }
}
