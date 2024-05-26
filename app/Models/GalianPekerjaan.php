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
    public function hasGalianAsmenPengawas()
    {
        return $this->hasOne(GalianAsmen::class, 'galian_id');
    }
    public function hasGalianPerencanaan()
    {
        return $this->hasOne(GalianPerencanaan::class, 'galian_id');
    }
    public function hasGalianPerencanaanAdjust()
    {
        return $this->hasOne(GalianPerencanaanAdjust::class, 'galian_id');
    }

    public function getPekerjaanAttribute()
    {
        if ($this->hasItem) {
            return $this->hasItem->nama;
        }
    }
    public function getSatuanAttribute()
    {
        if ($this->hasItem) {
            return $this->hasItem->satuan;
        }
    }
    public function getHargaAttribute()
    {
        if ($this->hasItem) {
            return $this->hasItem->harga;
        }
    }

    // pengawas
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
    // Asmen Pengawas
    public function getGalianAsmenPengawasPanjangAttribute()
    {
        if ($this->hasGalianAsmenPengawas) {
            return $this->hasGalianAsmenPengawas->panjang;
        }
    }
    public function getGalianAsmenPengawasLebarAttribute()
    {
        if ($this->hasGalianAsmenPengawas) {
            return $this->hasGalianAsmenPengawas->lebar;
        }
    }
    public function getGalianAsmenPengawasDalamAttribute()
    {
        if ($this->hasGalianAsmenPengawas) {
            return $this->hasGalianAsmenPengawas->dalam;
        }
    }
    public function getGalianAsmenPengawasKeteranganAttribute()
    {
        if ($this->hasGalianAsmenPengawas) {
            return $this->hasGalianAsmenPengawas->keterangan;
        }
    }
    public function getGalianAsmenPengawasHargaSatuanAttribute()
    {
        if ($this->hasGalianAsmenPengawas) {
            return $this->hasGalianAsmenPengawas->harga_satuan;
        }
    }
    public function getGalianAsmenPengawasTotalAttribute()
    {
        if ($this->hasGalianAsmenPengawas) {
            return $this->hasGalianAsmenPengawas->total;
        }
    }

    // PerencanaanAdjust
    public function getGalianPerencanaanAdjustPanjangAttribute()
    {
        if ($this->hasGalianPerencanaanAdjust) {
            return $this->hasGalianPerencanaanAdjust->panjang;
        }
    }
    public function getGalianPerencanaanAdjustLebarAttribute()
    {
        if ($this->hasGalianPerencanaanAdjust) {
            return $this->hasGalianPerencanaanAdjust->lebar;
        }
    }
    public function getGalianPerencanaanAdjustDalamAttribute()
    {
        if ($this->hasGalianPerencanaanAdjust) {
            return $this->hasGalianPerencanaanAdjust->dalam;
        }
    }
    public function getGalianPerencanaanAdjustKeteranganAttribute()
    {
        if ($this->hasGalianPerencanaanAdjust) {
            return $this->hasGalianPerencanaanAdjust->keterangan;
        }
    }
    public function getGalianPerencanaanAdjustHargaSatuanAttribute()
    {
        if ($this->hasGalianPerencanaanAdjust) {
            return $this->hasGalianPerencanaanAdjust->harga_satuan;
        }
    }
    public function getGalianPerencanaanAdjustTotalAttribute()
    {
        if ($this->hasGalianPerencanaanAdjust) {
            return $this->hasGalianPerencanaanAdjust->total;
        }
    }

    // perencanaan

    public function getGalianPerencanaanHargaSatuanAttribute()
    {
        if ($this->hasGalianPerencanaan) {
            return $this->hasGalianPerencanaan->harga_satuan;
        }
    }
    public function getGalianPerencanaanTotalAttribute()
    {
        if ($this->hasGalianPerencanaan) {
            return $this->hasGalianPerencanaan->total;
        }
    }
    public function getGalianPerencanaanKeteranganAttribute()
    {
        if ($this->hasGalianPerencanaan) {
            return $this->hasGalianPerencanaan->keterangan;
        }
    }

    public function getVolumeAttribute()
    {
        $total = 0;
        if ($this->hasGalianPengawas) {
            $total = $this->hasGalianPengawas->dalam === 0.0
                ? $this->hasGalianPengawas->panjang * $this->hasGalianPengawas->lebar
                : $this->hasGalianPengawas->panjang * $this->hasGalianPengawas->lebar * $this->hasGalianPengawas->dalam;
        }
        return  $total;
    }
    public function getVolumeAsmenAttribute()
    {
        $total = 0;
        if ($this->hasGalianAsmenPengawas) {
            $total = $this->hasGalianAsmenPengawas->dalam === 0.0
                ? $this->hasGalianAsmenPengawas->panjang * $this->hasGalianAsmenPengawas->lebar
                : $this->hasGalianAsmenPengawas->panjang * $this->hasGalianAsmenPengawas->lebar * $this->hasGalianAsmenPengawas->dalam;
        }
        return  $total;
    }
    public function getVolumeAdjustAttribute()
    {
        $total = 0;
        if ($this->hasGalianPerencanaanAdjust) {
            $total = $this->hasGalianPerencanaanAdjust->dalam === 0.0
                ? $this->hasGalianPerencanaanAdjust->panjang * $this->hasGalianPerencanaanAdjust->lebar
                : $this->hasGalianPerencanaanAdjust->panjang * $this->hasGalianPerencanaanAdjust->lebar * $this->hasGalianPerencanaanAdjust->dalam;
        }
        return  $total;
    }

    public function getVolumeRekananAttribute()
    {
        $total = 0;
        $total = $this->dalam === 0.0
            ? $this->panjang * $this->lebar
            : $this->panjang * $this->lebar * $this->dalam;
        return  $total;
    }
}
