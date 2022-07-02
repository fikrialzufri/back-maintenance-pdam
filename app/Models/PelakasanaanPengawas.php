<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelakasanaanPengawas extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "item_pengawas";
}
