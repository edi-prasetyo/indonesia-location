<?php

namespace EdiPrasetyo\IndonesiaLocation\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'province_id',
        'regency_id',
        'province_code',
        'regency_code',
        'code',
        'name',
        'latitude',
        'longitude'
    ];

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
