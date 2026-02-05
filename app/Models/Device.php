<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'name',
        'ip_address',
        'image',
        'status',
        'rack_id',
        'rack_unit',
        'height_units',
        'description',
        'last_checked_at',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
    ];

    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function logs()
    {
        return $this->hasMany(DeviceLog::class)->orderBy('logged_at', 'desc');
    }
}
