<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenunjukanPekerjaan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'penunjukan_pekerjaan';
    protected $guarded = ['id'];
    protected $fillable = [
        'nomor_pelaksanaan_pekerjaan',
        'status',
        'aduan_id',
        'rekanan_id',
        'user_id'
    ];

    public function setNomorPelaksanaanPekerjaanAttribute($value)
    {
        $this->attributes['nomor_pelaksanaan_pekerjaan'] = $value;
        $this->attributes['slug'] = Str::slug($value);
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
        return $this->belongsToMany(Item::class, 'id', 'item_id')->withPivot('qty','harga')->withTimestamps();
    }

    public function hasUserMany()
    {
        return $this->belongsToMany(User::class, 'id', 'user_id')->withTimestamps();
    }
}
