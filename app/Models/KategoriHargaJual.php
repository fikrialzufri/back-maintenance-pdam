<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class KategoriHargaJual extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "kategori_harga_jual";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function produk()
    {
        return $this->belongsToMany(Produk::class, 'produk_kategori_harga')->withPivot('harga_jual');
    }
}
