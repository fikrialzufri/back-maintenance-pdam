<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Cabang extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "cabang";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasPusat()
    {
        return $this->belongsTo(Pusat::class, 'pusat_id');
    }

    public function getPusatAttribute()
    {
        if ($this->hasPusat) {
            return $this->hasPusat->nama;
        }
    }
}
