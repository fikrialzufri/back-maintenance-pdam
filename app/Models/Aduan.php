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
        'body',
        'lokasi',
        'lat_long',
        'status',
        'file',
        'user_id'
    ];

    public function setNoTicketAttribute($value)
    {
        $this->attributes['no_ticket'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function jenis_aduan()
    {
        return $this->hasOne(JenisAduan::class, 'id', 'jenis_aduan_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function jenisAduan()
    {
        return $this->belongsToMany(JenisAduan::class, 'aduan_jenis_aduan');
    }
}
