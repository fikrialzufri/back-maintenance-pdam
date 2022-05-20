<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalianPekerjaan extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "galian_pekerjaan";

    public function hasPelaksanaanPekerjaan()
    {
        return $this->belongsTo(PelaksanaanPekerjaan::class, 'pelaksanaan_pekerjaan_id', 'id');
    }

    public function hasItem()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function getPekerjaanAttribute()
    {
        if ($this->hasItem) {
            return $this->hasItem->nama;
        }
    }
}
