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
        'lampiran',
        'keterangan_kesimpulan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'foto' => 'array', // Cast to array for multiple files
        'lampiran' => 'array', // Cast to array for multiple files
    ];

    public function maintenanceChecklist()
    {
        return $this->belongsTo(MaintenanceChecklist::class);
    }
}
