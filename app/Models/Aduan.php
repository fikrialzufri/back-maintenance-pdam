<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Str;

class Aduan extends Model
{
    use UsesUuid;

    protected $table = 'aduan';
    protected $guarded = ['id'];
    protected $appends = ['status_mobile'];
    protected $fillable = [
        'no_ticket',
        'no_aduan',
        'mps',
        'atas_nama',
        'sumber_informasi',
        'keterangan',
        'lokasi',
        'lat_long',
        'status',
        'file',
        'wilayah_id',
        'user_id'
    ];

    public function setNoAduanAttribute($value)
    {
        $this->attributes['no_aduan'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasJenisAduan()
    {
        return $this->belongsToMany(JenisAduan::class, 'aduan_jenis_aduan');
    }

    public function getJenisAttribute()
    {
        $data = [];
        if ($this->hasJenisAduan) {
            foreach ($this->hasJenisAduan as $index => $value) {
                $data[$index] = $value->nama;
            }
        }
        // menjadikan EYD atau comma serta dan di belakang comma
        $data = rtrim(implode(", ", $data), ", ");
        $data = substr_replace($data, ' dan', strrpos($data, ','), 1);;
        return $data;
    }

    public function hasPenunjukanPekerjaan()
    {
        return $this->hasOne(PenunjukanPekerjaan::class, 'aduan_id', 'id')->orderBy('penunjukan_pekerjaan.status', 'desc');
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getUserAttribute()
    {
        if ($this->hasUser) {
            return $this->hasUser->name;
        }
    }
    public function getRekananAttribute()
    {
        if ($this->hasPenunjukanPekerjaan) {
            return $this->hasPenunjukanPekerjaan->rekanan;
        }
    }
    public function getNoSpkAttribute()
    {
        if ($this->hasPenunjukanPekerjaan) {
            return $this->hasPenunjukanPekerjaan->nomor_pekerjaan;
        }
    }

    public function getKeteranganBarangAttribute()
    {
        if ($this->hasPenunjukanPekerjaan) {
            $hasPelaksanaanPekerjaan = $this->hasPenunjukanPekerjaan->hasPelaksanaanPekerjaan;
            if ($hasPelaksanaanPekerjaan) {
                if ($hasPelaksanaanPekerjaan->keterangan_barang != null) {
                    return $hasPelaksanaanPekerjaan->keterangan_barang;
                }
            }
        }
    }

    public function hasWilayah()
    {
        return $this->hasOne(Wilayah::class, 'id', 'wilayah_id');
    }

    public function getWilayahAttribute()
    {
        if ($this->hasWilayah) {
            return $this->hasWilayah->nama;
        }
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
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = "Dikoreksi pengawas";
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
    public function getStatusOrderPengawasAttribute()
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
    public function getBtnAttribute()
    {
        $btn = 'btn-primary';
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $btn  = "btn-primary";
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    if (auth()->user()->hasRole('staf-pengawas')) {
                        $btn  = "btn-danger";
                    }
                    if (auth()->user()->hasRole('staf-perencanaan')) {
                        $btn  = "btn-primary";
                    }
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    if (auth()->user()->hasRole('staf-pengawas')) {
                        $btn  = "btn-primary";
                    }
                    if (auth()->user()->hasRole('staf-perencanaan')) {
                        $btn  = "btn-danger";
                    }
                } else {
                    $btn  = "btn-primary";
                }
            }
        }
        if ($this->status === 'draft') {
            $btn  = "btn-danger";
        }
        return $btn;
    }

    public function getStatusMobileAttribute()
    {
        switch ($this->status) {
            case 'proses':
                return 1;
                break;
            case 'selesai':
                return 2;
                break;
            case 'disetujui':
                return 3;
                break;
            default:
                return 0;
                break;
        }
        return 's';
    }
}
