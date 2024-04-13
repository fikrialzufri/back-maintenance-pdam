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
        'kode_anggaran',
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

    public function getRekananPkpAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->pkp;
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
    // public function getLokasiAttribute()
    // {
    //     if ($this->hasPenunjukanPekerjaan) {
    //         return $this->hasPenunjukanPekerjaan->lokasi;
    //     }
    // }
    // public function getNoTiketAttribute()
    // {
    //     if ($this->hasPenunjukanPekerjaan) {
    //         return $this->hasPenunjukanPekerjaan->no_tiket;
    //     }
    // }
    // public function getKeteranganAduanAttribute()
    // {
    //     if ($this->hasPenunjukanPekerjaan) {
    //         return $this->hasPenunjukanPekerjaan->keterangan_aduan;
    //     }
    // }
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
    public function getTotalVolumeGalianAttribute()
    {
        $total = 0;
        if ($this->hasGalianPekerjaan) {
            foreach ($this->hasGalianPekerjaan as $key => $value) {
                if ($this->status === 'diadjust') {
                    $total += $value->volume_adjust;
                } else {
                    $total += $value->volume;
                }
            }
        }
        return  $total;
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

    public function hasItemAsmenPengawas()
    {
        return $this->belongsToMany(Item::class, 'item_asmen_pengawas')->withPivot(
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

    public function hasItemPerencanaanAdujst()
    {
        return $this->belongsToMany(Item::class, 'item_perencanaan_adjust')->withPivot(
            'qty',
            'harga',
            'total',
            'keterangan',
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

    public function getDangerAttribute()
    {
        $total = false;
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
    public function getStatusAduanAttribute()
    {
        $status = "Belum ditunjuk";
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = "Belum dikerjakan";
                } else if ($this->hasPenunjukanPekerjaan->status == 'proses') {
                    $status  = "Sedang dikerjakan";
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = "Selesai dikerjakan";
                } else if ($this->hasPenunjukanPekerjaan->status == 'approve') {
                    $status  = "Approve Asisten Manajer";
                } else if ($this->hasPenunjukanPekerjaan->status == 'approve manajer') {
                    $status  = "Approve Manajer";
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = "Dikoreksi Pengawas";
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi asmen') {
                    $status  = "Dikoreksi Asmen Pengawas";
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = "Disetujui Manajer";
                } else {
                    $status  = $this->hasPenunjukanPekerjaan->status;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderAttribute()
    {
        $status = 5;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 4;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderManajerAttribute()
    {
        $status = 6;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'approve') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'approve manajer') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 6;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 7;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderPengawasAttribute()
    {
        $status = 6;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'approve manajer') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'approve') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 6;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 7;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderAsemPengawasAttribute()
    {
        $status = 5;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi asmen') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'diadjust') {
                    $status  = 6;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 7;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderAsmenAttribute()
    {
        $status = 5;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi asmen') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'diadjust') {
                    $status  = 6;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 7;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderManajerPengawasAttribute()
    {
        $status = 5;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'koreksi asmen') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'diadjust') {
                    $status  = 6;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 7;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderAllAttribute()
    {
        $status = 7;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'disetujui') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'proses') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 6;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderPerencanaanAttribute()
    {
        $status = 4;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi' && $this->hasPenunjukanPekerjaan->tagihan = 'tidak') {
                    $status  = 1;
                }
                if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi' && $this->hasPenunjukanPekerjaan->tagihan = 'tidak') {
                    $status  = 2;
                }
                if ($this->hasPenunjukanPekerjaan->status == 'diadjust' && $this->hasPenunjukanPekerjaan->tagihan = 'tidak') {
                    $status  = 3;
                }
            }
        }
        return $status;
    }
    public function getBtnAttribute()
    {
        $btn = 'btn-primary';
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $btn  = "btn-primary";
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    if (auth()->user()->hasRole('asisten-manajer-distribusi')) {
                        $btn  = "btn-danger";
                    }
                    if (auth()->user()->hasRole('asisten-manajer-pengendalian-kehilangan-air')) {
                        $btn  = "btn-danger";
                    }
                } else if ($this->hasPenunjukanPekerjaan->status == 'approve') {
                    if (auth()->user()->hasRole('manajer-distribusi')) {
                        $btn  = "btn-danger";
                    }
                    if (auth()->user()->hasRole('manajer-pengendalian-kehilangan-air')) {
                        $btn  = "btn-danger";
                    }
                } else if ($this->hasPenunjukanPekerjaan->status == 'approve manajer') {
                    if (auth()->user()->hasRole('staf-pengawas')) {
                        $btn  = "btn-danger";
                    }
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    if (auth()->user()->hasRole('asisten-manajer-pengawas')) {
                        $btn  = "btn-danger";
                    }
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi asmen') {
                    if (auth()->user()->hasRole('manajer-perawatan')) {
                        $btn  = "btn-danger";
                    }
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {

                    if (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
                        $btn  = "btn-danger";
                    }
                } else {
                    $btn  = "btn-primary";
                }
            }
        }
        if ($this->status === 'draft') {
            if (auth()->user()->hasRole('asisten-manajer-distribusi')) {
                $btn  = "btn-danger";
            }
            if (auth()->user()->hasRole('asisten-manajer-pengendalian-kehilangan-air')) {
                $btn  = "btn-danger";
            }
        }
        return $btn;
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
            case 'approve':
                return 5;
                break;
            case 'approve manajer':
                return 6;
                break;
            case 'diadjust':
                return 7;
                break;
            case 'koreksi pengawas':
                return 8;
                break;
            case 'koreksi asmen':
                return 9;
                break;
            case 'dikoreksi':
                return 10;
                break;
            case 'selesai koreksi':
                return 11;
                break;
            case 'disetujui':
                return 12;
                break;
            default:
                return 1;
                break;
        }
    }
}
