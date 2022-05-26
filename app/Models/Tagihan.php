<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Tagihan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'tagihan';
    protected $guarded = ['id'];
    protected $fillable = [
        'nomor_tagihan',
        'nomor_bap',
        'kode_vocher',
        'aduan_id',
        'rekanan_id',
        'penunjukan_pekerjaan_id',
        'user_id'
    ];

    public function setNomorTagihanAttribute($value)
    {
        $this->attributes['nomor_tagihan'] = $value;
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

    public function getDirekturAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->nama_penangung_jawab;
        }
    }

    public function getTanggalAttribute()
    {
        if ($this->created_at) {
            return tanggal_indonesia($this->created_at);
        }
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function hasPelaksanaanPekerjaan()
    {
        return $this->belongsToMany(PelaksanaanPekerjaan::class,  'tagihan_pelaksanaan')->withPivot('total')->withTimestamps();
    }

    public function hasItem()
    {
        return $this->belongsToMany(Item::class,  'tagihan_item')
            ->withPivot(
                'uraian',
                'master',
                'harga_uraian',
                'harga_master',
                'jumlah',
                'total_uraian',
                'total_master'
            )
            ->withTimestamps();
    }

    public function getHargaItemAttribute()
    {
        $harga = [];
        $danger = '';
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {
                foreach ($value->hasItem as $i => $item) {

                    $harga[$key][$i] = $item->pivot->harga;
                    if ($item->pivot->harga == 0) {
                        $danger = 'salah';
                        break;
                    }
                }
            }
        }
        return $danger;
    }
    public function getBelumAdjustAttribute()
    {
        $danger = '';
        if ($this->hasTagihanItem) {
            foreach ($this->hasTagihanItem as $key => $value) {
                if ($value->selisih == 'ya') {
                    $danger .= 'ya';
                    break;
                }
            }
        }
        return $danger;
    }
    public function getBelumPersetujuanAttribute()
    {
        $danger = '';
        $user = auth()->user()->id;

        if ($this->hasUserMany) {
            foreach ($this->hasUserMany as $key => $value) {
                if ($value->id ==  $user) {
                    $danger = 'bg-danger';
                }
            }
        }
        return $danger;
    }

    public function getTagihanAttribute()
    {
        $total = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {
                foreach ($value->hasItem as $i => $item) {
                    $total += $item->pivot->harga * $item->pivot->qty;
                }
            }
        }
        return $total;
    }

    public function getGalianAttribute()
    {
        $total = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {

                $total += $value->hasGalianPekerjaan->sum('total');
            }
        }
        return $total;
    }

    public function getTotalTagihanAttribute()
    {
        $total = 0;
        if ($this->tagihan && $this->galian) {
            $total = $this->tagihan + $this->galian;
        }
        return $total;
    }

    public function getTotalLokasiPekerjaanAttribute()
    {
        $counttotal = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            $counttotal = count($this->hasPelaksanaanPekerjaan);
        }
        return $counttotal;
    }

    public function getListPekerjaanAttribute()
    {
        $result = [];
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {
                # code...
                $result[$key] = [
                    'slug' => $value->hasPenunjukanPekerjaan->slug,
                    'tanggal_mulai' => $value->hasPenunjukanPekerjaan->tanggal_mulai,
                    'tanggal_selesai' => $value->hasPenunjukanPekerjaan->tanggal_selesai
                ];
            }
        }
        return $result;
    }

    public function getListPersetujuanAttribute()
    {
        $result = [];
        if ($this->hasUserMany) {
            foreach ($this->hasUserMany as $key => $value) {
                $result[$key] = (object) [
                    'id' => $value->id,
                    'nama' => $value->karyawan->nama,
                    'jabatan' => $value->karyawan->nama_jabatan,
                    'is_setuju' => true,
                    'tanggal_disetujui' => isset($value->pivot->created_at) ? tanggal_indonesia($value->pivot->created_at) : ''
                ];
            }
        }
        return $result;
    }

    public function hasUserMany()
    {
        return $this->belongsToMany(User::class, 'tagihan_user')->withTimestamps();
    }

    public function hasTagihanItem()
    {
        return $this->hasMany(TagihanItem::class);
    }

    public function getStatusMobileAttribute()
    {
        switch ($this->status) {
            case 'step1':
                return 1;
                break;
            case 'step2':
                return 2;
                break;
            case 'step3':
                return 3;
                break;
            case 'step4':
                return 4;
                break;
            case 'step5':
                return 5;
                break;
            case 'disetujui':
                return 6;
                break;
            case 'dibayar':
                return 7;
                break;
            default:
                return 0;
                break;
        }
    }
}
