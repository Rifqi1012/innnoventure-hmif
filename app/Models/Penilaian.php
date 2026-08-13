<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penilaians';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'webdev_progress_id',
        'ui_progress_id',
        'aspek_penilaian_id',
        'juri_id',
        'skor',
    ];

    /**
     * Get the webdev project submission that owns the score.
     */
    public function webdevProgress(): BelongsTo
    {
        return $this->belongsTo(WebdevProgress::class);
    }

    /**
     * Get the uiux project submission that owns the score.
     */
    public function uiProgress(): BelongsTo
    {
        return $this->belongsTo(UiProgress::class);
    }

    /**
     * Get the assessment aspect that owns the score.
     */
    public function aspekPenilaian(): BelongsTo
    {
        return $this->belongsTo(AspekPenilaian::class);
    }

    /**
     * Get the user (judge) who gave the score.
     */
    public function juri(): BelongsTo
    {
        return $this->belongsTo(User::class, 'juri_id');
    }
}