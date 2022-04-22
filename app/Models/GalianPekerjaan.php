<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalianPekerjaan extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "galian_pekerjaan";

    public function hasPelaksanaanPekerjaan()
    {
        return $this->belongsTo(PelaksanaanPekerjaan::class, 'penunjukan_pekerjaan_id', 'id');
    }
}
