<?php

use App\Http\Controllers\JuriController;
use App\Http\Controllers\PenilaianSayembaraController;
use App\Http\Controllers\SayembaraController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\WebDevController;
use App\Http\Controllers\CabangLombaController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\TimController;
use App\Http\Controllers\MlMatchController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\DaftarSeminarController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\MedpartController;
use App\Http\Controllers\DaftarSeminar;
use App\Http\Controllers\AspekPenilaianController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::prefix('cabang-lomba')->group(function () {
    Route::get('/', [CabangLombaController::class, 'index']);
    Route::get('/{id}', [CabangLombaController::class, 'show']);
});

Route::prefix('instansi')->group(function () {
    Route::get('/', [InstansiController::class, 'index']);
    Route::get('/{id}', [InstansiController::class, 'show']);
});

Route::prefix('tim')->group(function () {
    Route::get('/', [TimController::class, 'index']);
    Route::get('/{id}', [TimController::class, 'show']);
    Route::patch('/{id}/status', [TimController::class, 'updateStatus']);
});

Route::prefix('ml-match')->group(function () {
    Route::get('/', [\App\Http\Controllers\MlMatchController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\MlMatchController::class, 'show']);
    Route::post('/generate', [\App\Http\Controllers\MlMatchController::class, 'generateBracket']);
    Route::patch('/{id}/score', [\App\Http\Controllers\MlMatchController::class, 'updateScore']);
});


Route::prefix('ui-progress')->group(function () {
    Route::get('/', [\App\Http\Controllers\UiProgressController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\UiProgressController::class, 'show']);
    Route::post('/', [\App\Http\Controllers\UiProgressController::class, 'store']);
});

Route::prefix('webdev-progress')->group(function () {
    Route::get('/', [WebDevController::class, 'index']);
    Route::get('/{id}', [WebDevController::class, 'show']);
    Route::post('/', [WebDevController::class, 'store']);
});

Route::prefix('seminar')->group(function () {
    Route::get('/', [SeminarController::class, 'index']);
});


Route::prefix('sponsor')->group(function () {
    Route::get('/', [SponsorController::class, 'getAllSponsorsByType']);
    Route::get('/{id}', [SponsorController::class, 'getSponsorById']);
});

Route::prefix('medpart')->group(function () {
    Route::get('/', [MedpartController::class, 'getAllMedpartByType']);
    Route::get('/{id}', [MedpartController::class, 'getMedpartById']);
});

Route::prefix('daftarseminar')->group(function () {
    Route::get('/', [\App\Http\Controllers\DaftarSeminarController::class, 'index']);
    Route::get('/{kode_absen}', [\App\Http\Controllers\DaftarSeminarController::class, 'showByAbsen']);
    Route::post('/', [\App\Http\Controllers\DaftarSeminarController::class, 'createDaftarSeminar']);
    Route::post('/attend', [\App\Http\Controllers\DaftarSeminarController::class, 'attendSeminar']);
});

Route::prefix('aspek-penilaian')->group(function () {
    Route::get('/cabang/{cabangLombaId}/progress/{progressId}', [AspekPenilaianController::class, 'aspekPenilaianByCabangLomba']);
});

// Penilaian
Route::get('/penilaian/karya/{progressId}/juri/{juriId}', [PenilaianController::class, 'getScoresByJuri']);
Route::get('/penilaian/karya/{progressId}', [PenilaianController::class, 'getScoresByProgress']);
Route::get('/penilaian', [PenilaianController::class, 'index']);

Route::get('/list-juri', [JuriController::class, 'index']);

// Juri Only
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/juri', function (Request $request) {
        $juri = $request->user();
        $juri->assigned_aspek_ids = $juri->aspekPenilaians()->pluck('aspek_penilaian_id');

        return $juri;
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/penilaian', [PenilaianController::class, 'store']);
    Route::post('/penilaian/catatan-webdev', [PenilaianController::class, 'updateCatatanJuri']);
});
Route::get('/undian-peserta', [DaftarSeminarController::class, 'getUndianData']);
