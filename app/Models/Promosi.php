<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Promosi extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "promosi";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasProduk()
    {
        return $this->belongsToMany(Produk::class, 'promosi_produk')->withPivot('diskon', 'type_diskon');
    }

    public function getDiskonTampilAttribute()
    {
        if ($this->jenis_diskon == "produk") {
            # code...
            if ($this->type_diskon == "persen") {
                return $this->diskon . " % ";
            }
            if ($this->type_diskon == "nominal") {
                return "Rp. " . format_uang($this->diskon);
            }
        } else {
            $listProduk = [];
            if ($this->hasProduk()) {
                foreach ($this->hasProduk as $key => $value) {
                    if ($value->pivot->type_diskon == "persen") {
                        $listProduk[$key] = $value->nama . " | Diskon : " . $value->pivot->diskon . " % ";
                    }
                    if ($value->pivot->type_diskon == "nominal") {
                        $listProduk[$key] = $value->nama . " | Diskon : Rp. " . format_uang($this->diskon);
                    }
                }
                $str =  implode(" ", $listProduk);
                return rtrim($str, ',');
            }
        }
    }

    public function getTypeDiskonTampilAttribute()
    {
        return ucfirst($this->type_diskon);
    }
}
