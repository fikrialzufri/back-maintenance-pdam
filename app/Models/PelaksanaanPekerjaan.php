<?php

namespace App\Models;

use App\Http\Resources\Pekerjaan;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class PelaksanaanPekerjaan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'pelaksanaan_pekerjaan';
    protected $guarded = ['id'];
    protected $appends = ['status_mobile'];
    protected $fillable = [
        'nomor_pelaksanaan_pekerjaan',
        'status',
        'aduan_id',
        'rekanan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'user_id'
    ];

    public function setNomorPelaksanaanPekerjaanAttribute($value)
    {
        $this->attributes['nomor_pelaksanaan_pekerjaan'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasRekanan()
    {
        return $this->hasOne(Rekanan::class, 'id', 'rekanan_id');
    }

    public function getRekananAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->nama;
        }
    }

    public function hasPenunjukanPekerjaan()
    {
        return $this->hasOne(PenunjukanPekerjaan::class, 'id', 'penunjukan_pekerjaan_id');
    }

    public function getNoSpkAttribute()
    {
        if ($this->hasPenunjukanPekerjaan) {
            return $this->hasPenunjukanPekerjaan->nomor_pekerjaan;
        }
    }
    public function getNoSpkSlugAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->slug;
        }
    }

    public function getGalianAttribute()
    {
        if ($this->hasGalianPekerjaan) {
            return $this->hasGalianPekerjaan;
        }
    }
    public function getTotalGalianAttribute()
    {
        if ($this->hasGalianPekerjaan) {
            return $this->hasGalianPekerjaan->sum('total');
        }
    }
    public function getTotalAdjustAttribute()
    {
        if ($this->hasPekerjaanAdjust) {
            return $this->hasPekerjaanAdjust->sum('total');
        }
    }

    public function getLuasGalianAttribute()
    {
        $total = 0;
        if ($this->hasGalianPekerjaan) {
            foreach ($this->hasGalianPekerjaan as $key => $value) {
                $total += $value->panjang * $value->lebar * $value->dalam;
            }
        }
        return $total;
    }

    public function hasAduan()
    {
        return $this->hasOne(Aduan::class, 'id', 'aduan_id');
    }

    public function getWilayahAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->hasWilayah->singkatan;
        }
    }

    public function hasGalianPekerjaan()
    {
        return $this->hasMany(GalianPekerjaan::class, 'pelaksanaan_pekerjaan_id');
    }

    public function getVolumeAttribute()
    {
        $total = 0;
        if ($this->hasGalianPekerjaan) {
            foreach ($this->hasGalianPekerjaan as $value) {
                $total += $value->volume;
            }
        }
        return  $total;
    }

    public function hasPekerjaanAdjust()
    {
        return $this->hasMany(PelaksanaanAdjust::class, 'pelaksanaan_pekerjaan_id');
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function hasItem()
    {
        return $this->belongsToMany(Item::class, 'pelaksanaan_item')->withPivot(
            'qty',
            'harga',
            'total',
            'keterangan',
        )->withTimestamps();
    }
    public function hasItemPengawas()
    {
        return $this->belongsToMany(Item::class, 'item_pengawas')->withPivot(
            'qty',
            'harga',
            'total',
            'keterangan'
        )->withTimestamps();
    }
    public function hasItemPerencanaan()
    {
        return $this->belongsToMany(Item::class, 'item_perencanaan')->withPivot(
            'harga',
            'total',
            'keterangan'
        )->withTimestamps();
    }

    public function getTotalHargaAttribute()
    {
        $total = 0;
        if ($this->hasItem) {
            foreach ($this->hasItem as $value) {
                $total += $value->pivot->total;
            }
        }

        return $total;
    }

    public function getHargaItemAttribute()
    {
        $harga = 0;
        if ($this->hasItem) {
            foreach ($this->hasItem as $value) {
                if ($value->pivot->harga == 0) {
                    $harga = 0;
                }
            }
        }

        return $harga;
    }




    public function getTotalPekerjaanAttribute()
    {
        $total = 0;
        $total_galian = 0;
        $total_harga = 0;
        $total_adjust = 0;
        if ($this->total_galian) {
            $total_galian = $this->total_galian;
        }
        if ($this->total_harga) {
            $total_harga = $this->total_harga;
        }
        $total = $total_galian + $total_harga + $total_adjust;
        return $total;
    }



    public function hasUserMany()
    {
        return $this->belongsToMany(User::class, 'pelaksanaan_user')->withPivot('keterangan')->withTimestamps();
    }

    public function getStatusMobileAttribute()
    {
        switch ($this->status) {
            case 'proses':
                return 2;
                break;
            case 'proses-akhir':
                return 3;
                break;
            case 'selesai':
                return 4;
                break;
            case 'diadjust':
                return 5;
                break;
            case 'dikoreksi':
                return 6;
                break;
            case 'selesai koreksi':
                return 7;
                break;
            case 'disetujui':
                return 8;
                break;
            default:
                return 1;
                break;
        }
    }
}
