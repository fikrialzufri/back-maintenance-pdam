<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class PenunjukanPekerjaan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'penunjukan_pekerjaan';
    protected $guarded = ['id'];
    protected $appends = ['status_mobile'];
    protected $fillable = [
        'nomor_pekerjaan',
        'status',
        'aduan_id',
        'rekanan_id',
        'user_id'
    ];

    public function setNomorPekerjaanAttribute($value)
    {
        $this->attributes['nomor_pekerjaan'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasAduan()
    {
        return $this->hasOne(Aduan::class, 'id', 'aduan_id');
    }

    public function getLokasiAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->lokasi;
        }
    }
    public function getJenisAduanAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->jenis;
        }
    }
    public function getAtasNamaAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->atas_nama;
        }
    }

    public function getSumberInformasiAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->sumber_informasi;
        }
    }
    public function getKeteranganAduanAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->keterangan;
        }
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
    public function getMediaAttribute()
    {
        if ($this->hasPelaksanaanPekerjaan) {
            return $this->hasPelaksanaanPekerjaan->hasMedia;
        }
    }

    public function hasPelaksanaanPekerjaan()
    {
        return $this->hasOne(PelaksanaanPekerjaan::class, 'penunjukan_pekerjaan_id', 'id');
    }

    public function getLokasiPekerjaanAttribute()
    {
        if ($this->hasPelaksanaanPekerjaan) {
            return $this->hasPelaksanaanPekerjaan->lokasi;
        }
    }

    public function getIdPelaksanaanPekerjaanAttribute()
    {
        if ($this->hasPelaksanaanPekerjaan) {
            return $this->hasPelaksanaanPekerjaan->id;
        }
    }

    public function getNomorPelaksanaanPekerjaanAttribute()
    {
        if ($this->hasPelaksanaanPekerjaan) {
            return $this->hasPelaksanaanPekerjaan->nomor_pelaksanaan_pekerjaan;
        }
    }

    public function getLatLongAttribute()
    {
        if ($this->hasPelaksanaanPekerjaan) {
            return $this->hasPelaksanaanPekerjaan->lat_long;
        }
    }
    public function getListItemAttribute()
    {
        $item = [];
        return $this->hasPelaksanaanPekerjaan->hasItem;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan->hasItem()->get() as $key => $value) {
                $item[$key] = [
                    'id' => $value->id,
                    'nama' => $value->nama,
                    'jumlah' => $value->pivot->qty,
                    'keterangan' => $value->pivot->keterangan,
                ];
            }
        }
        return $item;
    }
    public function getFotoLokasiAttribute()
    {
        $media = Media::where('modul',  'pelaksanaan_kerja')->where('modul_id', $this->id)->orderBy('created_at', 'desc')->get();
        $foto = [];
        if ($media) {
            foreach ($media as $key => $value) {
                $foto[$key] = [
                    'id' => $value->id,
                    'url' => asset('storage/proses/' . rawurlencode($value->file)),
                ];
            }
        }
        return $foto;
    }

    public function getFotoBahanAttribute()
    {
        $media = Media::where('modul',  'bahan_pekerjaan')->where('modul_id', $this->id)->orderBy('created_at', 'desc')->get();
        $foto = [];
        if ($media) {
            foreach ($media as $key => $value) {
                $foto[$key] = [
                    'id' => $value->id,
                    'url' => asset('storage/proses/' . rawurlencode($value->file)),
                ];
            }
        }
        return $foto;
    }

    public function getFotoPenyelesaianAttribute()
    {
        $media = Media::where('modul',  'penyelesaian_kerja')->where('modul_id', $this->id)->orderBy('created_at', 'desc')->get();
        $foto = [];
        if ($media) {
            foreach ($media as $key => $value) {
                $foto[$key] = [
                    'id' => $value->id,
                    'url' => asset('storage/proses/' . rawurlencode($value->file)),
                ];
            }
        }
        return $foto;
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function hasUserMany()
    {
        return $this->belongsToMany(User::class, 'penunjukan_user')->withPivot('keterangan')->withTimestamps();
    }

    public function getStatusMobileAttribute()
    {

        if ($this->hasPelaksanaanPekerjaan) {
            return $this->hasPelaksanaanPekerjaan->status_mobile;
        } else {
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
        }
    }
}
