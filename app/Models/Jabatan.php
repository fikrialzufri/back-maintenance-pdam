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
}
