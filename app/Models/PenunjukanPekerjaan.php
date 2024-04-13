<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use Carbon\Carbon;

class PenunjukanPekerjaan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'penunjukan_pekerjaan';
    protected $guarded = ['id'];
    protected $appends = ['status_mobile', 'total_pekerjaan'];
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

    public function hasKaryawan()
    {
        return $this->hasOne(Karyawan::class, 'id', 'karyawan_id');
    }

    public function getLokasiAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->lokasi;
        }
    }

    public function getDetailLokasiAduanAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->detail_lokasi;
        }
    }
    public function getLatLongAduanAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->lat_long;
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
    public function getNoTiketAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->no_ticket;
        }
    }

    public function getKategoriAduanttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->kategori_aduan;
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
    public function getNoPelangganAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->no_pelanggan;
        }
    }
    public function getNamaPelangganAttribute()
    {
        if ($this->hasAduan) {
            return $this->hasAduan->nama_pelanggan;
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
        if ($this->hasKaryawan) {
            return $this->hasKaryawan->nama;
        }
    }
    public function getOprAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->opr;
        }
    }
    public function getMediaAttribute()
    {
        if ($this->hasPelaksanaanPekerjaan) {
            return $this->hasPelaksanaanPekerjaan->hasMedia;
        }
    }

    public function getTanggalPekerjaanAttribute()
    {
        if ($this->hasPelaksanaanPekerjaan) {

            if ($this->hasPelaksanaanPekerjaan->created_at != '') {
                return tanggal_indonesia($this->hasPelaksanaanPekerjaan->created_at);
            }
        }
    }
    public function getGalianPekerjaanAttribute()
    {
        $result = [];
        if ($this->hasPelaksanaanPekerjaan) {

            if (isset($this->hasPelaksanaanPekerjaan->hasGalianPekerjaan)) {
                foreach ($this->hasPelaksanaanPekerjaan->hasGalianPekerjaan as $key => $value) {
                    $result[$key] = [
                        'id' => $value->id,
                        'pekerjaan' => $value->pekerjaan,
                        'panjang' => (string) $value->panjang,
                        'lebar' => (string) $value->lebar,
                        'dalam' => (string) $value->dalam,
                        'keterangan' => $value->keterangan,
                    ];
                }
            }
        }
        return $result;
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

    public function getKeteranganPenyelesaianAttribute()
    {
        if ($this->hasPelaksanaanPekerjaan) {
            return $this->hasPelaksanaanPekerjaan->keterangan;
        }
    }

    public function getKeteranganBarangAttribute()
    {
        if ($this->hasPelaksanaanPekerjaan) {
            return $this->hasPelaksanaanPekerjaan->keterangan_barang;
        }
    }

    public function getTanggalSelesaiAttribute()
    {
        if ($this->hasPelaksanaanPekerjaan) {
            return $this->hasPelaksanaanPekerjaan->tanggal_selesai;
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
    public function getTotalPekerjaanAttribute()
    {
        if ($this->hasPelaksanaanPekerjaan) {
            return $this->hasPelaksanaanPekerjaan->total_pekerjaan;
        }
    }

    public function getListBahanAttribute()
    {
        $kategoriBahan = Kategori::whereSlug('bahan')->first();
        if ($kategoriBahan) {
            $jenisBahan = Jenis::where('kategori_id', $kategoriBahan->id)->get()->pluck('id');
            $listBahan = Item::whereIn('jenis_id', $jenisBahan)->get()->pluck('id')->toArray();
        }

        $item = [];
        $index = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan->hasItem as $key => $value) {
                if (in_array($value->id, $listBahan)) {
                    $item[$index++] = [
                        'id' => $value->id,
                        'nama' => $value->nama,
                        'jumlah' => (string) $value->pivot->qty,
                        'keterangan' => $value->pivot->keterangan,
                    ];
                }
            }
        }
        return $item;
    }
    public function getListPekerjaanAttribute()
    {
        $kategoriBahan = Kategori::whereSlug('pekerjaan')->first();
        if ($kategoriBahan) {
            $jenisBahan = Jenis::where('kategori_id', $kategoriBahan->id)->get()->pluck('id');
            $listBahan = Item::whereIn('jenis_id', $jenisBahan)->get()->pluck('id')->toArray();
        }

        $item = [];
        $index = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan->hasItem as $key => $value) {
                if (in_array($value->id, $listBahan)) {
                    $item[$index++] = [
                        'id' => $value->id,
                        'nama' => $value->nama,
                        'jumlah' => (string) $value->pivot->qty,
                        'keterangan' => $value->pivot->keterangan,
                        'total_pekerjaan' => $value->pivot->total_pekerjaan,
                    ];
                }
            }
        }
        return $item;
    }
    public function getListAlatBantuAttribute()
    {
        $kategoriBahan = Kategori::whereSlug('alat-bantu')->first();
        if ($kategoriBahan) {
            $jenisBahan = Jenis::where('kategori_id', $kategoriBahan->id)->get()->pluck('id');
            $listBahan = Item::whereIn('jenis_id', $jenisBahan)->get()->pluck('id')->toArray();
        }

        $item = [];
        $index = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan->hasItem as $key => $value) {
                if (in_array($value->id, $listBahan)) {
                    $item[$index++] = [
                        'id' => $value->id,
                        'nama' => $value->nama,
                        'jumlah' => (string) $value->pivot->qty,
                        'keterangan' => $value->pivot->keterangan,
                    ];
                }
            }
        }
        return $item;
    }
    public function getListTransportasiAttribute()
    {
        $kategoriBahan = Kategori::whereSlug('transportasi')->first();
        if ($kategoriBahan) {
            $jenisBahan = Jenis::where('kategori_id', $kategoriBahan->id)->get()->pluck('id');
            $listBahan = Item::whereIn('jenis_id', $jenisBahan)->get()->pluck('id')->toArray();
        }

        $item = [];
        $index = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan->hasItem as $key => $value) {
                if (in_array($value->id, $listBahan)) {
                    $item[$index++] = [
                        'id' => $value->id,
                        'nama' => $value->nama,
                        'jumlah' => (string) $value->pivot->qty,
                        'keterangan' => $value->pivot->keterangan,
                    ];
                }
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

    public function getFotoGalianAttribute()
    {
        $media = Media::where('modul',  'galian_pekerjaan')->where('modul_id', $this->id)->orderBy('created_at', 'desc')->get();
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

    public function getFotoTransportasiAttribute()
    {
        $media = Media::where('modul',  'transportasi_pekerjaan')->where('modul_id', $this->id)->orderBy('created_at', 'desc')->get();
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

    public function getListPersetujuanAttribute()
    {
        $result = [];
        $hasUserMany = [];
        if ($this->hasUserMany) {
            foreach ($this->hasUserMany as $key => $value) {
                if ($value->karyawan) {
                    $hasUserMany[$value->karyawan->nama] = (object) [
                        'id' => $value->karyawan->user_id,
                        'nama' => $value->karyawan->nama,
                        'jabatan' => $value->karyawan->nama_jabatan,
                        'url' => $value->karyawan->url,
                        'tdd' => $value->karyawan->tdd,
                        'is_setuju' => true,
                        'created_at' => $value->pivot->created_at,
                        'updated_at' => $value->pivot->updated_at,
                        'tanggal_disetujui' => isset($value->pivot->updated_at) ? tanggal_indonesia($value->pivot->updated_at) . " - " . Carbon::parse($value->pivot->updated_at)->format('H:i') : ''
                    ];
                }
            }

            $collect = collect($hasUserMany)->unique()->sortBy('updated_at');
            $nomor = 0;
            foreach ($collect as $key => $value) {
                $result[$nomor] = (object) [
                    'id' => $value->id,
                    'nama' => $value->nama,
                    'jabatan' => $value->jabatan,
                    'url' => $value->url,
                    'tdd' => $value->tdd,
                    'is_setuju' => $value->is_setuju,
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at,
                    'tanggal_disetujui' => $value->tanggal_disetujui
                ];
                $nomor++;
            }
        }
        return $result;
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
