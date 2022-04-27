<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Item extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "item";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
        $this->attributes['hapus'] = 'tidak';
    }

    public function hasJenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }

    public function getJenisAttribute()
    {
        if ($this->hasJenis) {
            return $this->hasJenis->nama;
        }
    }

    public function hasSatuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function getSatuanAttribute()
    {
        if ($this->hasSatuan) {
            return $this->hasSatuan->nama;
        }
    }
}
