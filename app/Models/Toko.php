<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Toko extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "toko";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasCabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function getNamaCabangAttribute()
    {
        if ($this->hasCabang) {
            return $this->hasCabang->nama;
        }
    }

    public function karyawan() {
        return $this->belongsToMany(Karyawan::class, 'karyawans_tokos');
    }
}
