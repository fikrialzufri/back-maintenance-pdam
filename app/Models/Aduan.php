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
    protected $appends = ['status_mobile'];
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

    public function setNoAduanAttribute($value)
    {
        $this->attributes['no_aduan'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasJenisAduan()
    {
        return $this->belongsToMany(JenisAduan::class, 'aduan_jenis_aduan');
    }

    public function getJenisAttribute()
    {
        $data = [];
        if ($this->hasJenisAduan) {
            foreach ($this->hasJenisAduan as $index => $value) {
                $data[$index] = $value->nama;
            }
        }
        // menjadikan EYD atau comma serta dan di belakang comma
        $data = rtrim(implode(", ", $data), ", ");
        $data = substr_replace($data, ' dan', strrpos($data, ','), 1);;
        return $data;
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getUserAttribute()
    {
        if ($this->hasUser) {
            return $this->hasUser->name;
        }
    }

    public function hasWilayah()
    {
        return $this->hasOne(Wilayah::class, 'id', 'wilayah_id');
    }

    public function getWilayahAttribute()
    {
        if ($this->hasWilayah) {
            return $this->hasWilayah->nama;
        }
    }

    public function getStatusMobileAttribute()
    {
        switch ($this->status) {
            case 'proses':
                return 1;
                break;
            case 'selesai':
                return 2;
                break;
            case 'disetujui':
                return 3;
                break;
            default:
                return 0;
                break;
        }
        return 's';
    }
}
