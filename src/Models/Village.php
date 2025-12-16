<?php

namespace EdiPrasetyo\IndonesiaLocation\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $fillable = [
        'province_id',
        'regency_id',
        'district_id',
        'province_code',
        'regency_code',
        'district_code',
        'code',
        'name',
        'postal_code',
        'latitude',
        'longitude'
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
