<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebdevProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'tim_id',
        'email_ketua',
        'judul_proyek',
        'catatan',
        'link_repository',
        'link_demo',
        'link_hosting',
        'ppt',
        'pdf',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['total_skor']; // <-- 1. TAMBAHKAN INI

    public function tim()
    {
        return $this->belongsTo(Tim::class);
    }

    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }

    /**
     * Accessor untuk menghitung total skor akhir.
     *
     * @return float
     */
    public function getTotalSkorAttribute(): float // <-- 2. TAMBAHKAN METHOD INI
    {
        // Mengambil semua penilaian terkait beserta aspeknya untuk efisiensi
        $penilaians = $this->penilaians()->with('aspekPenilaian')->get();

        if ($penilaians->isEmpty()) {
            return 0;
        }

        // Mengelompokkan penilaian berdasarkan juri_id
        $scoresByJuri = $penilaians->groupBy('juri_id');
        $totalAverageScore = 0;

        // Menghitung skor rata-rata dari semua juri
        foreach ($scoresByJuri as $juriScores) {
            $singleJuriScore = 0;
            foreach ($juriScores as $penilaian) {
                if ($penilaian->aspekPenilaian) { // Pastikan aspek penilaian ada
                    $bobot = $penilaian->aspekPenilaian->bobot_penilaian / 100;
                    $singleJuriScore += $penilaian->skor * $bobot;
                }
            }
            $totalAverageScore += $singleJuriScore;
        }

        // Rata-rata dari total skor semua juri
        return $scoresByJuri->count() > 0 ? $totalAverageScore / $scoresByJuri->count() : 0;
    }

    public function isJudgingCompleteFor(Juri $juri): bool
    {
        // 1. Ambil semua ID aspek yang ditugaskan ke juri ini.
        $assignedAspectIds = $juri->aspekPenilaians()->pluck('aspek_penilaian_id');

        // Jika juri tidak punya tugas aspek sama sekali, anggap sudah selesai.
        if ($assignedAspectIds->isEmpty()) {
            return true;
        }

        // 2. Ambil semua ID aspek yang SUDAH dinilai oleh juri ini UNTUK KARYA INI.
        $scoredAspectIds = $this->penilaians()
            ->where('juri_id', $juri->id)
            ->pluck('aspek_penilaian_id');

        // 3. Bandingkan keduanya. Jika jumlahnya sama, berarti sudah selesai.
        // Kita gunakan diff() untuk memastikan semua aspek yang ditugaskan ada di dalam yang sudah dinilai.
        return $assignedAspectIds->diff($scoredAspectIds)->isEmpty();
    }
}
