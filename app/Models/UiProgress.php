<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UiProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'tim_id',
        'email_ketua',
        'judul_proyek',
        'link_figma',
        'ppt',
        'pdf',
    ];

    public function tim()
    {
        return $this->belongsTo(Tim::class);
    }
}
