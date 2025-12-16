<?php

namespace EdiPrasetyo\IndonesiaLocation\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['code', 'name', 'latitude', 'longitude'];

    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }
}
