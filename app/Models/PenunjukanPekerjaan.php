<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class PenunjukanPekerjaan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'penunjukan_pekerjaan';
    protected $guarded = ['id'];
    protected $fillable = [
        'nomor_pekerjaan',
        'status',
        'aduan_id',
        'rekanan_id',
        'user_id'
    ];

    public function setNomorPekerjaanAttribute($value)
    {
        $this->attributes['nomor_pekerjaan'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasAduan()
    {
        return $this->hasOne(Aduan::class, 'id', 'aduan_id');
    }

    public function hasRekanan()
    {
        return $this->hasOne(Rekanan::class, 'id', 'rekanan_id');
    }

    public function hasPenunjunkanPekerjaan()
    {
        return $this->belongsToMany(PenunjukanPekerjaan::class, 'id', 'penunjukan_pekerjaan_id');
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function hasItem()
    {
        return $this->belongsToMany(Item::class, 'pelaksanaan_item')->withPivot('qty', 'harga')->withTimestamps();
    }

    public function hasUserMany()
    {
        return $this->belongsToMany(User::class, 'penunjukan_user')->withPivot('keterangan')->withTimestamps();
    }

    public function getStatusMobileAttribute()
    {
        switch ($this->status) {
            case 'diterima':
                return 1;
                break;
            case 'proses':
                return 2;
                break;
            case 'proses-akhir':
                return 3;
                break;
            case 'selesai':
                return 4;
                break;
            case 'disetujui':
                return 5;
                break;
            default:
                return 0;
                break;
        }
    }
}