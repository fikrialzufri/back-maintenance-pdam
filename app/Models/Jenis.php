<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Jenis extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "jenis";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasKategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function getNamaKategoriAttribute()
    {
        if ($this->hasKategori) {
            return $this->hasKategori->nama;
        }
    }
}
