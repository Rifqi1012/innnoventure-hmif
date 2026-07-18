<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DaftarSeminar extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'daftar_seminars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'instansi',
        'email',
        'no_hp',
        'no_undian',
        'kode_absen',
        'bukti_follow_ig',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate a unique no_undian with format VENTURE-YYYYXXXX.
     *
     * @return string
     */
    public static function generateNoUndian()
    {
        $year = now()->format('Y');
        do {
            $randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $noUndian = "VENTURE-{$year}{$randomNumber}";
        } while (self::where('no_undian', $noUndian)->exists());

        return $noUndian;
    }

    /**
     * Generate a unique kode_absen.
     *
     * @return string
     */
    public static function generateKodeAbsen()
    {
        do {
            $kodeAbsen = Str::upper(Str::random(6));
        } while (self::where('kode_absen', $kodeAbsen)->exists());

        return $kodeAbsen;
    }

    /**
     * Boot the model and set default values for no_undian and kode_absen.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->no_undian)) {
                $model->no_undian = self::generateNoUndian();
            }
            if (empty($model->kode_absen)) {
                $model->kode_absen = self::generateKodeAbsen();
            }
        });
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'daftar_seminar_id');
    }
}
