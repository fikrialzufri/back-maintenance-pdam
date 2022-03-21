<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Produk extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "produk";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function KategoriHargaJual()
    {
        return $this->belongsToMany(KategoriHargaJual::class, 'produk_kategori_harga')->withPivot('harga_jual');
    }

    public function getHargaJualAttribute()
    {
        if ($this->KategoriHargaJual) {
            if (count($this->KategoriHargaJual) != 0) {
                # code...
                return $this->KategoriHargaJual()->orderBy('harga_jual', 'desc')->first()->pivot->harga_jual;
            }
        }
    }

    public function hasJenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }

    public function getJenisAttribute()
    {
        if ($this->hasJenis) {
            return $this->hasJenis->nama;
        }
    }
    public function hasSatuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function getSatuanAttribute()
    {
        if ($this->hasSatuan) {
            return $this->hasSatuan->nama;
        }
    }


    public function hasPromosi()
    {
        return $this->belongsToMany(Promosi::class, 'promosi_produk')->withPivot('diskon', 'type_diskon');
    }

    public function hasBahan()
    {

        // return $this->belongsToMany(Self::class, 'produk_bahan')->withPivot('qty');
        return $this->belongsToMany(Self::class, 'produk_bahan', 'produk_id', 'bahan_id')->withPivot('qty');
    }
}
