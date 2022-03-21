<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Str;

class Roster extends Model
{
    use UsesUuid;
    protected $table = 'rosters';
    protected $guarded = ['id'];
    protected $fillable = [
        'shift', 'jam_masuk', 'jam_pulang'
    ];
    protected $dates = ['tanggal'];

    public function getJadwalAttribute()
    {
        return tanggal_indonesia($this->tanggal);
    }
    public function getTanggalAttribute()
    {
        return $this->attributes['tanggal'];
    }

    public function hasShift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function getNameShiftAttribute()
    {
        if ($this->hasShift) {
            return $this->hasShift->name;
        }
    }
}
