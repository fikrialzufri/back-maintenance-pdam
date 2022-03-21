<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use UsesUuid;

    protected $table = 'jadwals';
    protected $guarded = ['id'];
    protected $fillable = [
        'roster_id', 'karyawan_id'
    ];

    public function hasRoster()
    {
        return $this->belongsTo(Roster::class, 'roster_id');
    }

    public function getTanggalAttribute()
    {
        if ($this->hasRoster) {
            return $this->hasRoster->tanggal;
        }
    }

    public function getShiftAttribute()
    {
        if ($this->hasRoster) {
            return $this->hasRoster->shift;
        }
    }

    public function getJamMasukAttribute()
    {
        if ($this->hasRoster) {
            return $this->hasRoster->jam_masuk;
        }
    }

    public function getJamPulangAttribute()
    {
        if ($this->hasRoster) {
            return $this->hasRoster->jam_pulang;
        }
    }

    public function hasKaryawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function getKaryawanAttribute()
    {
        if ($this->hasKaryawan) {
            return $this->hasKaryawan->nama;
        }
    }
}
