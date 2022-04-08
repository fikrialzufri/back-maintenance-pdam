<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Divisi extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "divisi";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasDepartemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    public function getDepartemenAttribute()
    {
        if ($this->hasDepartemen) {
            return $this->hasDepartemen->nama;
        }
    }
}
