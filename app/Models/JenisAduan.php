<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Str;

class JenisAduan extends Model
{
    use UsesUuid;

    protected $table = 'jenis_aduan';
    protected $guarded = ['id'];
    protected $fillable = [
        'nama'
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
