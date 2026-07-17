<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Massage extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'no_tlp',
        'subjek',
        'massage',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $massage): void {
            if (! empty($massage->id_laporan)) {
                return;
            }

            do {
                $idLaporan = 'LAP-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
            } while (self::where('id_laporan', $idLaporan)->exists());

            $massage->id_laporan = $idLaporan;
        });
    }
}
