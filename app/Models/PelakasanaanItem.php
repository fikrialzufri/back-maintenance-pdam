<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelakasanaanItem extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "pelaksanaan_item";
}
