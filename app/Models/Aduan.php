<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Str;

class Aduan extends Model
{
    use UsesUuid;

    protected $table = 'aduan';
    protected $guarded = ['id'];
    protected $fillable = [
        'no_ticket',
        'no_aduan',
        'mps',
        'atas_nama',
        'sumber_informasi',
        'keterangan',
        'lokasi',
        'lat_long',
        'status',
        'file',
        'wilayah_id',
        'user_id'
    ];

    public function setNoTicketAttribute($value)
    {
        $this->attributes['no_ticket'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasJenisAduan()
    {
        return $this->belongsToMany(JenisAduan::class, 'aduan_jenis_aduan');
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getUserAttribute()
    {
        if ($this->hasUser)
        {
            return $this->hasUser->name;
        }
    }

    public function hasWilayah()
    {
        return $this->hasOne(Wilayah::class, 'id', 'wilayah_id');
    }

    public function getWilayahAttribute()
    {
        if ($this->hasWilayah)
        {
            return $this->hasWilayah->nama;
        }
    }
}