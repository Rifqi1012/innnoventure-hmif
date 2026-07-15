<?php

namespace App\Http\Controllers;

use App\Services\SeminarService;
use App\Models\DaftarSeminar;
use Illuminate\Http\Request;

class DaftarSeminarController extends Controller
{
    protected $seminarService;

    public function __construct(SeminarService $seminarService)
    {
        $this->seminarService = $seminarService;
    }

    public function index()
    {
        return $this->successResponse(DaftarSeminar::all());
    }

    public function createDaftarSeminar(Request $request)
    {
        $validated = $request->validate([
            'nama'            => 'required|string|max:255',
            'instansi'        => 'required|string|max:255',
            'email'           => 'required|email|unique:daftar_seminars,email',
            'no_hp'           => 'required|string|max:20',
            'bukti_follow_ig' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
        ]);

        $peserta = $this->seminarService->register($validated);

        return $this->successResponse($peserta, 'Berhasil Daftar Seminar', 201);
    }

    public function showByAbsen($kode_absen)
    {
        $data = DaftarSeminar::where('kode_absen', $kode_absen)->first();

        if (!$data) {
            return $this->errorResponse('Data pendaftar tidak ditemukan.', 404);
        }

        return $this->successResponse($data);
    }

    public function attendSeminar(Request $request)
    {
        $validated = $request->validate([
            'kode_absen' => 'required|string'
        ]);

        $peserta = $this->seminarService->attend($validated['kode_absen']);

        if (!$peserta) {
            return $this->errorResponse('Kode absen tidak valid atau tidak ditemukan.', 404);
        }

        return $this->successResponse($peserta, 'Berhasil melakukan absensi (Regis Ulang)');
    }

    public function getUndianData()
    {
        $peserta = DaftarSeminar::whereNotNull('no_undian')
            ->select('nama', 'no_undian')
            ->get();

        return $this->successResponse($peserta);
    }
}
