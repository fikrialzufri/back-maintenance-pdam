<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekanan extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "rekanan";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }


    public function hasKaryawan()
    {
        return $this->belongsToMany(Karyawan::class, 'karyawan_rekanan')->withTimestamps();
    }
}
