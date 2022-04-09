<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Karyawan extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "karyawan";

    public function hasUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUsernameAttribute()
    {
        if ($this->hasUser) {
            return $this->hasUser->username;
        }
    }

    public function getEmailAttribute()
    {
        if ($this->hasUser) {
            return $this->hasUser->email;
        }
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasJabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function getNamaJabatanAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->nama;
        }
    }

    public function getWilayahAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->wilayah;
        }
    }

    public function getIdWilayahAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->id_wilayah;
        }
    }

    public function getDivisiAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->divisi;
        }
    }

    public function getDepartemenAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->departemen;
        }
    }

    public function hasRekanan()
    {
        return $this->belongsToMany(Rekanan::class, 'karyawan_rekanan')->withTimestamps();
    }
}
