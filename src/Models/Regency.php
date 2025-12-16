<?php

namespace EdiPrasetyo\IndonesiaLocation\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $fillable = [
        'province_id',
        'province_code',
        'code',
        'name',
        'latitude',
        'longitude'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
