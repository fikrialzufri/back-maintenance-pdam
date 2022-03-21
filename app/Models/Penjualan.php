<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Penjualan extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "penjualan";

    public function setNoNotaAttribute($value)
    {
        $this->attributes['no_nota'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasProduk()
    {
        return $this->belongsToMany(Produk::class, 'penjualan_produk')->withPivot(
            'type_diskon',
            'harga_beli',
            'harga_jual',
            'qty',
            'total_harga',
            'diskon_produk',
            'karyawan_id',
            'komisi',
            'promosi_id',
        )->withTimestamps();
    }
}
