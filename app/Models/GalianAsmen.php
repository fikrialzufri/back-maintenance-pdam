<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalianAsmen extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "galian_asmen_pengawas";

    public function hasItem()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function hasGalianPengawas()
    {
        return $this->hasOne(GalianPekerjaan::class, 'id', 'galian_id');
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
}
