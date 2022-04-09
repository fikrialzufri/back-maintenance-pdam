<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class PerintahPelaksana extends Model
{
    use UsesUuid;

    protected $table = 'perintah_pelaksana';
    protected $guarded = ['id'];
    protected $fillable = [
        'rekanan_id',
        'aduan_id',
        'manager_id',
        'asisten_manager_id',
        'dikeluarkan_di'
    ];

    protected $dates = ['tanggal'];

    public function hasRekanan()
    {
        return $this->hasOne(Rekanan::class, 'id', 'rekanan_id');
    }

    public function hasAduan()
    {
        return $this->hasOne(Aduan::class, 'id', 'aduan_id');
    }

    public function hasManager()
    {
        return $this->hasOne(User::class, 'id', 'manager_id');
    }

    public function hasAsistenManager()
    {
        return $this->hasOne(User::class, 'id', 'asisten_manager_id');
    }
}
