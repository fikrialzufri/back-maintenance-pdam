<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use PHPUnit\Framework\Constraint\Count;
use Str;

class Aduan extends Model
{
    use UsesUuid;

    protected $table = 'aduan';
    protected $guarded = ['id'];
    protected $appends = ['status_mobile', 'total_pekerjaan'];
    protected $fillable = [
        'no_ticket',
        'no_aduan',
        'nps',
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
            if (count($this->hasJenisAduan) > 1) {
                $data = rtrim(implode(", ", $data), ", ");
                $data = substr_replace($data, ' dan', strrpos($data, ','), 1);
            } else {
                $data = rtrim(implode(", ", $data), ", ");
            }
        }
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
    public function getIdRekananAttribute()
    {
        if ($this->hasPenunjukanPekerjaan) {
            return $this->hasPenunjukanPekerjaan->rekanan_id;
        }
    }
    public function getTanggalPekerjaanAttribute()
    {
        if ($this->hasPenunjukanPekerjaan) {

            if ($this->hasPenunjukanPekerjaan->tanggal_pekerjaan != '') {
                return $this->hasPenunjukanPekerjaan->tanggal_pekerjaan;
            }
        }
    }
    public function getTagihanAttribute()
    {
        if ($this->hasPenunjukanPekerjaan) {

            if ($this->hasPenunjukanPekerjaan->hasPelaksanaanPekerjaan != '') {
                return $this->hasPenunjukanPekerjaan->hasPelaksanaanPekerjaan->tagihan;
            }
        }
    }
    public function getTotalPekerjaanAttribute()
    {
        if ($this->hasPenunjukanPekerjaan) {

            if ($this->hasPenunjukanPekerjaan->hasPelaksanaanPekerjaan != '') {
                return $this->hasPenunjukanPekerjaan->hasPelaksanaanPekerjaan->total_pekerjaan;
            }
        }
    }
    public function getBukanRekananAttribute()
    {
        $bukan = false;
        if ($this->hasPenunjukanPekerjaan) {
            if ($this->hasPenunjukanPekerjaan->hasPelaksanaanPekerjaan != '') {
                if ($this->hasPenunjukanPekerjaan->hasPelaksanaanPekerjaan->rekanan_id == null) {
                    $bukan = true;
                }
            }
        }
        return $bukan;
    }
    public function getRekananAttribute()
    {
        if ($this->hasPenunjukanPekerjaan) {
            return $this->hasPenunjukanPekerjaan->rekanan;
        }
    }
    public function getOprAttribute()
    {
        if ($this->hasPenunjukanPekerjaan) {
            return $this->hasPenunjukanPekerjaan->opr;
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
