<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenunjukanPekerjaan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'penunjukan_pekerjaan';
    protected $guarded = ['id'];
    protected $fillable = [
        'nomor_pekerjaan',
        'status',
        'aduan_id',
        'rekanan_id',
        'user_id'
    ];

    public function hasRekanan()
    {
        return $this->hasOne(Rekanan::class, 'id', 'rekanan_id');
    }

    public function hasAduan()
    {
        return $this->hasOne(Aduan::class, 'id', 'aduan_id');
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
