<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    protected $fillable = [
        'name',
        'description',
        'total_units',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class)->orderBy('rack_unit');
    }
}
