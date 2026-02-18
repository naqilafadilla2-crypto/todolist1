<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    protected $fillable = [
        'name',
        'description',
        'total_units',
        'status_online',
        'last_checked_at',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class)->orderBy('rack_unit');
    }

    public function getStatusTextAttribute()
    {
        return $this->status_online === 'online' ? 'Online' : 'Offline';
    }

    public function getStatusColorAttribute()
    {
        return $this->status_online === 'online' ? 'green' : 'red';
    }

    public function getOnlineDevicesCount()
    {
        return $this->devices()->where('status', 'online')->count();
    }

    public function getOfflineDevicesCount()
    {
        return $this->devices()->where('status', 'offline')->count();
    }

    public function getTotalDevicesCount()
    {
        return $this->devices()->count();
    }
}
