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
        return $this->belongsTo(PelaksanaanPekerjaan::class, 'pelaksanaan_pekerjaan_id', 'id');
    }

    public function hasItem()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function hasGalianPengawas()
    {
        return $this->hasOne(GalianPengawas::class, 'galian_id');
    }

    public function getPekerjaanAttribute()
    {
        if ($this->hasItem) {
            return $this->hasItem->nama;
        }
    }
    public function getHargaAttribute()
    {
        if ($this->hasItem) {
            return $this->hasItem->harga;
        }
    }
    public function getGalianPengawasPanjangAttribute()
    {
        if ($this->hasGalianPengawas) {
            return $this->hasGalianPengawas->panjang;
        }
    }
    public function getGalianPengawasLebarAttribute()
    {
        if ($this->hasGalianPengawas) {
            return $this->hasGalianPengawas->lebar;
        }
    }
    public function getGalianPengawasDalamAttribute()
    {
        if ($this->hasGalianPengawas) {
            return $this->hasGalianPengawas->dalam;
        }
    }
    public function getGalianPengawasKeteranganAttribute()
    {
        if ($this->hasGalianPengawas) {
            return $this->hasGalianPengawas->keterangan;
        }
    }
    public function getGalianPengawasHargaSatuanAttribute()
    {
        if ($this->hasGalianPengawas) {
            return $this->hasGalianPengawas->harga_satuan;
        }
    }
    public function getGalianPengawasTotalAttribute()
    {
        if ($this->hasGalianPengawas) {
            return $this->hasGalianPengawas->total;
        }
    }
}
