<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use Carbon\Carbon;

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
    public function getRekananUrlAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->url;
        }
    }
    public function getRekananUrlTddAttribute()
    {
        if ($this->hasRekanan) {
            return asset('storage/rekanan/' . $this->hasRekanan->tdd);
        }
    }
    public function getAlamatRekananAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->alamat;
        }
    }
    public function getTddRekananAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->tdd;
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
        if (
            auth()->user()->hasRole('staf-pengawas') ||
            auth()->user()->hasRole('asisten-manajer-distribusi') ||
            auth()->user()->hasRole('asisten-manajer-pengawas') ||
            auth()->user()->hasRole('manajer-distribusi') ||
            auth()->user()->hasRole('asisten-manajer-perencanaan') ||
            auth()->user()->hasRole('manajer-perencanaan') || auth()->user()->hasRole('direktur-teknik')
        ) {
            $danger = 'bg-danger';
            if ($this->hasUserMany) {
                foreach ($this->hasUserMany as $key => $value) {
                    if ($value->id ==  $user) {
                        $danger = '';
                    }
                }
            } else {
                $danger = 'bg-danger';
            }
        }
        return $danger;
    }
    public function getBelumPersetujuanMobileAttribute()
    {
        $danger = false;
        if ($this->hasUserMany) {
            if (count($this->hasUserMany) > 0) {
                $danger = true;
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
                    $total += $item->pivot->total;
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
    public function getPekerjaanAdjustAttribute()
    {
        $total = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {

                $total += $value->hasPekerjaanAdjust->sum('total');
            }
        }
        return $total;
    }

    public function getTotalTagihanAttribute()
    {
        $total = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {

                $total += $value->total_pekerjaan;
            }
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
        $hasUserMany = [];
        if ($this->hasUserMany) {
            foreach ($this->hasUserMany as $key => $value) {
                if ($value->karyawan) {
                    $hasUserMany[$key] = (object) [
                        'id' => $value->karyawan->user_id,
                        'nama' => $value->karyawan->nama,
                        'jabatan' => $value->karyawan->nama_jabatan,
                        'url' => $value->karyawan->url,
                        'tdd' => $value->karyawan->tdd,
                        'is_setuju' => true,
                        'created_at' => $value->pivot->created_at,
                        'tanggal_disetujui' => isset($value->pivot->created_at) ? tanggal_indonesia($value->pivot->created_at) . " - " . Carbon::parse($value->pivot->created_at)->format('H:i') : ''
                    ];
                }
            }

            $collect = collect($hasUserMany)->sortBy('created_at');
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
                    'tanggal_disetujui' => $value->tanggal_disetujui
                ];
                $nomor++;
            }
        }
        return $result;
    }

    public function getListPersetujuanPekerjaanAttribute()
    {
        $result = [];
        $hasUserMany = [];
        if ($this->hasUserMany) {
            foreach ($this->hasUserMany as $key => $value) {
                if ($value->karyawan) {
                    $hasUserMany[$key] = (object) [
                        'id' => $value->karyawan->user_id,
                        'nama' => $value->karyawan->nama,
                        'jabatan' => $value->karyawan->nama_jabatan,
                        'is_setuju' => true,
                        'created_at' => $value->pivot->created_at,
                        'tanggal_disetujui' => isset($value->pivot->created_at) ? tanggal_indonesia($value->pivot->created_at) . " - " . Carbon::parse($value->pivot->created_at)->format('H:i') : ''
                    ];
                }
            }

            $collect = collect($hasUserMany)->sortBy('created_at');
            $nomor = 0;
            foreach ($collect as $key => $value) {
                $result[$nomor] = (object) [
                    'id' => $value->id,
                    'nama' => $value->nama,
                    'jabatan' => $value->jabatan,
                    'is_setuju' => $value->is_setuju,
                    'created_at' => $value->created_at,
                    'tanggal_disetujui' => $value->tanggal_disetujui
                ];
                $nomor++;
            }
        }
        return $result;
    }
    public function getListPersetujuanTandaTanganAttribute()
    {
        $result = [];
        if ($this->hasUserMany) {
            foreach ($this->hasUserMany as $key => $value) {
                if ($value->karyawan) {
                    $result[$key] = (object) [
                        'id' => $value->id,
                        'nama' => $value->karyawan->nama,
                        'jabatan' => $value->karyawan->nama_jabatan,
                        'is_setuju' => true,
                        'created_at' => $value->pivot->created_at,
                        'tdd' => $value->karyawan->tdd,
                        'tanggal_disetujui' => isset($value->pivot->created_at) ? tanggal_indonesia($value->pivot->created_at) . " - " . Carbon::parse($value->pivot->created_at)->format('H:i') : ''
                    ];
                }
            }

            $result = collect($result)->sortByDesc('created_at');
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
