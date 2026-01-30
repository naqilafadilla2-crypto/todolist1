<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $fillable = [
        'nama_aplikasi',
        'status',
        'tanggal',
        'file',
        'deskripsi',
        'type',
        'url',
    ];
}
