<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Str;

class Shift extends Model
{
    use UsesUuid;
    protected $table = 'shifts';
    protected $guarded = ['id'];
    protected $fillable = [
        'name'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasJabatan()
    {
        return $this->belongsToMany(Jabatan::class, 'shifts_jabatans');
    }
}
