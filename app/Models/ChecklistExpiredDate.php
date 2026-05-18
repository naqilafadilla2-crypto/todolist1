<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ChecklistExpiredDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perangkat',
        'tanggal_kadaluarsa',
        'status',
        'keterangan',
    ];

    /**
     * Generate status otomatis
     */
    public static function generateStatus($tanggalKadaluarsa)
    {
        $today = Carbon::today();
        $expired = Carbon::parse($tanggalKadaluarsa);

        $selisihHari = $today->diffInDays($expired, false);

        if ($selisihHari > 30) {
            return 'HIJAU';
        } elseif ($selisihHari <= 7) {
            return 'MERAH';
        } else {
            return 'KUNING';
        }
    }

    /**
     * Sisa hari
     */
    public function getSisaHariAttribute()
    {
        $today = Carbon::today();
        $expired = Carbon::parse($this->tanggal_kadaluarsa);

        $selisihHari = $today->diffInDays($expired, false);

        if ($selisihHari > 0) {
            return $selisihHari . ' Hari Lagi';
        } elseif ($selisihHari == 0) {
            return 'Expired Hari Ini';
        } else {
            return 'Sudah Lewat ' . abs($selisihHari) . ' Hari';
        }
    }

    /**
     * Warning otomatis H-7
     */
    public function getPeringatanAttribute()
    {
        $today = Carbon::today();
        $expired = Carbon::parse($this->tanggal_kadaluarsa);

        $selisihHari = $today->diffInDays($expired, false);

        if ($selisihHari <= 7 && $selisihHari >= 0) {
            return '⚠️ Expired ' . $selisihHari . ' hari lagi';
        }

        if ($selisihHari < 0) {
            return '❌ Sudah Expired';
        }

        return 'AMAN';
    }
}