<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceChecklist extends Model
{
    protected $fillable = [
        'perangkat',
        'q1', 'q2', 'q3', 'q4',
        'status_q1', 'status_q2', 'status_q3', 'status_q4',
        'tanggal_q1', 'tanggal_q2', 'tanggal_q3', 'tanggal_q4',
        'checked_q1', 'checked_q2', 'checked_q3', 'checked_q4',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_q1' => 'date',
        'tanggal_q2' => 'date',
        'tanggal_q3' => 'date',
        'tanggal_q4' => 'date',
    ];

    public function getStatusIconAttribute()
    {
        return [
            'belum' => '❌',
            'proses' => '⏳',
            'selesai' => '✅',
        ];
    }

    public function getStatusColorAttribute()
    {
        return [
            'belum' => '#ff6b6b',
            'proses' => '#ffd93d',
            'selesai' => '#2ecc71',
        ];
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }
}
