<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonitoringLog extends Model
{
    use HasFactory;

    protected $table = 'monitoring_log';
    
    protected $fillable = [
        'aktivitas',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope untuk mendapatkan log terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope untuk mendapatkan log hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope untuk mendapatkan log bulan ini
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    /**
     * Scope untuk mencari aktivitas
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where('aktivitas', 'like', "%{$keyword}%");
    }

    /**
     * Dapatkan format waktu yang rapi
     */
    public function getFormattedTimeAttribute()
    {
        if (!$this->created_at) {
            return null;
        }
        return $this->created_at->format('d M Y - H:i:s');
    }

    /**
     * Dapatkan waktu relatif (e.g., "2 jam yang lalu")
     */
    public function getRelativeTimeAttribute()
    {
        if (!$this->created_at) {
            return null;
        }
        return $this->created_at->diffForHumans();
    }

    /**
     * Dapatkan tipe aktivitas dari string
     */
    public function getActivityTypeAttribute()
    {
        if (strpos($this->aktivitas, 'Menambah') !== false) {
            return 'create';
        } elseif (strpos($this->aktivitas, 'mengubah') !== false || strpos($this->aktivitas, 'memperbarui') !== false) {
            return 'update';
        } elseif (strpos($this->aktivitas, 'Menghapus') !== false) {
            return 'delete';
        }
        return 'other';
    }

    /**
     * Dapatkan icon berdasarkan tipe aktivitas
     */
    public function getActivityIconAttribute()
    {
        $type = $this->activity_type;
        $icons = [
            'create' => '✅',
            'update' => '🔄',
            'delete' => '🗑️',
            'other' => '📝'
        ];
        return $icons[$type] ?? '📝';
    }
}
