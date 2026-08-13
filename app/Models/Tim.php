<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tim extends Model
{
    use HasFactory;

    protected $fillable = [
        'cabang_lomba_id',
        'nama',
        'instansi_id',
        'status',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }


    public function cabangLomba()
    {
        return $this->belongsTo(CabangLomba::class);
    }

    public function webdevProgress()
    {
        return $this->hasOne(WebdevProgress::class);
    }

    public function matchesAsTim1()
    {
        return $this->hasMany(MlMatch::class, 'tim1_id');
    }

    public function matchesAsTim2()
    {
        return $this->hasMany(MlMatch::class, 'tim2_id');
    }

    public function matchesWon()
    {
        return $this->hasMany(MlMatch::class, 'winner_id');
    }
}
