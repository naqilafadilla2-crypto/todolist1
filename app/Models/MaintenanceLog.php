<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    protected $fillable = [
        'maintenance_checklist_id',
        'tanggal',
        'pic',
        'foto',
        'keterangan_kesimpulan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function maintenanceChecklist()
    {
        return $this->belongsTo(MaintenanceChecklist::class);
    }
}
