<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Tagihan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'tagihan';
    protected $guarded = ['id'];
    protected $fillable = [
        'nomor_tagihan',
        'nomor_bap',
        'kode_vocher',
        'aduan_id',
        'rekanan_id',
        'penunjukan_pekerjaan_id',
        'user_id'
    ];

    public function setNomorTagihanAttribute($value)
    {
        $this->attributes['nomor_tagihan'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasRekanan()
    {
        return $this->hasOne(Rekanan::class, 'id', 'rekanan_id');
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function hasPelaksanaanPekerjaan()
    {
        return $this->belongsToMany(PelaksanaanPekerjaan::class,  'pelaksanaan_pekerjaan_id')->withPivot('total')->withTimestamps();;
    }

    public function hasUserMany()
    {
        return $this->belongsToMany(User::class, 'tagihan_user')->withTimestamps();
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
