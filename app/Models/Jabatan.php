<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Jabatan extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "jabatan";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasDivisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function getDivisiAttribute()
    {
        if ($this->hasDivisi) {
            return $this->hasDivisi->nama;
        }
    }
    public function getDepartemenAttribute()
    {
        if ($this->hasDivisi) {
            return $this->hasDivisi->departemen;
        }
    }

    public function hasWilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function getWilayahAttribute()
    {
        if ($this->hasWilayah) {
            return $this->hasWilayah->nama;
        }
    }

    public function getIdWilayahAttribute()
    {
        if ($this->hasWilayah) {
            return $this->hasWilayah->id;
        }
    }
}
