<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'absensi';
    protected $guarded = ['id'];
    protected $fillable = [
        'jadwal_id', 'karyawan_id', 'status', 'keterangan', 'menit', 'denda'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function getNamaKaryawanAttribute()
    {
        if ($this->karyawan) {
            return $this->karyawan->nama;
        }
    }

    public function getWaktuAttribute()
    {
        if ($this->created_at) {
            return tanggal_indonesia($this->created_at) . ' - ' . $this->created_at->format('h:i:s A ');
        }
    }
    public function getMenitTampilAttribute()
    {
        if ($this->menit) {
            return $this->convertToHoursMins($this->menit, '%02d Jam %02d Menit');
        }
    }

    public function convertToHoursMins($time, $format = '%02d:%02d')
    {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }
}
