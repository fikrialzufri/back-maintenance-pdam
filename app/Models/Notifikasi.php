<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Notifikasi extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'notifikasi';
    protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'body',
        'modul',
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasAduan()
    {
        return $this->hasOne(Aduan::class, 'id', 'aduan_id');
    }
}
