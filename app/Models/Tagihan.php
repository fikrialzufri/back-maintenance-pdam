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
        'user_id',
        'no_faktur_pajak',
        'no_faktur_pajak_image',
        'e_billing',
        'e_billing_image',
        'bukti_pembayaran',
        'bukti_pembayaran_image',
        'e_spt',
        'e_spt_image',
        'no_kwitansi',
        'no_kwitansi_image',
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
    public function getRekananPimpinanAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->pimpinan;
        }
    }
    public function getOprAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->opr;
        }
    }
    public function getStAttribute()
    {
        if ($this->nomor_tagihan) {
            return str_replace("BAPP", "ST", $this->nomor_tagihan);
        }
    }
    public function getPkpAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->pkp;
        }
    }
    public function getRekananUrlTddAttribute()
    {
        if ($this->hasRekanan) {
            return url('tddrekanan/' . $this->hasRekanan->id);
        }
    }
    public function getAlamatRekananAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->alamat;
        }
    }
    public function getNoHpRekananAttribute()
    {
        if ($this->hasRekanan) {
            return $this->hasRekanan->no_hp;
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
            return tanggal_indonesia($this->created_at, false, false);
        }
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function hasPelaksanaanPekerjaan()
    {
        return $this->belongsToMany(PelaksanaanPekerjaan::class, 'tagihan_pelaksanaan')->withPivot('total')->withTimestamps();
    }

    public function hasItem()
    {
        return $this->belongsToMany(Item::class, 'tagihan_item')
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

        if (auth()->user()->hasRole('manajer-perencanaan')) {
            // $danger = 'bg-danger';
            if ($this->status == 'dikirim') {
                $danger = 'bg-danger';
            }
            if ($this->hasUserMany) {
                foreach ($this->hasUserMany as $key => $value) {
                    if ($value->id == $user) {
                        $danger = '';
                    }
                }
            }
        }
        if (auth()->user()->hasRole('direktur-teknik')) {
            // $danger = 'bg-danger';
            if ($this->status == '' || $this->status == 'proses') {
                $danger = 'bg-danger';
            }
            if ($this->hasUserMany) {
                foreach ($this->hasUserMany as $key => $value) {
                    if ($value->id == $user) {
                        $danger = '';
                    }
                }
            }
        }
        if (auth()->user()->hasRole('asisten-manajer-tata-usaha')) {
            // $danger = 'bg-danger';
            if ($this->status == '' || $this->status == 'disetujui') {
                $danger = 'bg-danger';
            }
            if ($this->hasUserMany) {
                foreach ($this->hasUserMany as $key => $value) {
                    if ($value->id == $user) {
                        $danger = '';
                    }
                }
            }
        }
        if (auth()->user()->hasRole('manajer-umum-dan-kesekretariatan')) {
            // $danger = 'bg-danger';
            if ($this->status == 'disetujui asmentu') {
                $danger = 'bg-danger';
            }
            if ($this->hasUserMany) {
                foreach ($this->hasUserMany as $key => $value) {
                    if ($value->id == $user) {
                        $danger = '';
                    }
                }
            }
        }
        if (auth()->user()->hasRole('direktur-umum')) {
            // $danger = 'bg-danger';
            if ($this->status == 'disetujui mu') {
                $danger = 'bg-danger';
            }
            if ($this->hasUserMany) {
                foreach ($this->hasUserMany as $key => $value) {
                    if ($value->id == $user) {
                        $danger = '';
                    }
                }
            }
        }
        if (auth()->user()->hasRole('direktur-utama')) {
            // $danger = 'bg-danger';
            if ($this->status == 'disetujui dirum') {
                $danger = 'bg-danger';
            }
            if ($this->hasUserMany) {
                foreach ($this->hasUserMany as $key => $value) {
                    if ($value->id == $user) {
                        $danger = '';
                    }
                }
            }
        }

        if (auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan')) {
            // $danger = 'bg-danger';
            if ($this->status == 'disetujui dirut') {
                $danger = 'bg-danger';
            }
            if ($this->hasUserMany) {
                foreach ($this->hasUserMany as $key => $value) {
                    if ($value->id == $user) {
                        $danger = '';
                    }
                }
            }
        }
        if (auth()->user()->hasRole('asisten-manajer-akuntansi')) {
            // $danger = 'bg-danger';
            if ($this->status == 'disetujui asmenanggaran') {
                $danger = 'bg-danger';
            }
            if ($this->hasUserMany) {
                foreach ($this->hasUserMany as $key => $value) {
                    if ($value->id == $user) {
                        $danger = '';
                    }
                }
            }
        }
        if (auth()->user()->hasRole('manajer-keuangan')) {
            // $danger = 'bg-danger';
            if ($this->status == 'disetujui asmenakuntan') {
                $danger = 'bg-danger';
            }
            if ($this->hasUserMany) {
                foreach ($this->hasUserMany as $key => $value) {
                    if ($value->id == $user) {
                        $danger = '';
                    }
                }
            }
        }
        if (auth()->user()->hasRole('asisten-manajer-kas')) {
            // $danger = 'bg-danger';
            if ($this->status == 'disetujui mankeu') {
                $danger = 'bg-danger';
            }
            if ($this->hasUserMany) {
                foreach ($this->hasUserMany as $key => $value) {
                    if ($value->id == $user) {
                        $danger = '';
                    }
                }
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
        $total = pembulatan($total);
        $total = str_replace(".", "", $total);
        $pkp = 'tidak';
        $ppn = 0;


        if ($this->hasRekanan) {
            if ($this->hasRekanan->pkp) {
                if ($this->hasRekanan->pkp === 'ya') {
                    $ppn = ($total * 11) / 100;
                }
            }
        }

        return $total + $ppn;
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

    public function getAnggaranSatuAttribute()
    {
        $count = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {

                if ($value->kode_anggaran == '31.05.30') {
                    $count++;
                }
            }
        }
        return $count;
    }
    public function getTotalAnggaranSatuAttribute()
    {
        $sum = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {

                if ($value->kode_anggaran == '31.05.30') {
                    $sum +=  $value->total_pekerjaan;
                }
            }
        }
        return $sum;
    }
    public function getAnggaranDuaAttribute()
    {
        $count = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {

                if ($value->kode_anggaran == '93.02.30') {
                    $count++;
                }
            }
        }
        return $count;
    }
    public function getTotalAnggaranDuaAttribute()
    {
        $sum = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {

                if ($value->kode_anggaran == '93.02.30') {
                    $sum +=  $value->total_pekerjaan;
                }
            }
        }
        return $sum;
    }
    public function getAnggaranTigaAttribute()
    {
        $count = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {

                if ($value->kode_anggaran == '93.02.40') {
                    $count++;
                }
            }
        }
        return $count;
    }
    public function getTotalAnggaranTigaAttribute()
    {
        $sum = 0;
        if ($this->hasPelaksanaanPekerjaan) {
            foreach ($this->hasPelaksanaanPekerjaan as $key => $value) {

                if ($value->kode_anggaran == '93.02.40') {
                    $sum +=  $value->total_pekerjaan;
                }
            }
        }
        return $sum;
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
                        'karyawan_id' => $value->karyawan->id,
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
                    'karyawan_id' => $value->karyawan_id,
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
    public function getListPersetujuanDirekturTeknikAttribute()
    {
        $result = [];
        $hasUserMany = [];
        if ($this->hasUserMany) {
            foreach ($this->hasUserMany as $key => $value) {
                if ($value->karyawan && $value->karyawan->nama_jabatan === 'Direktur Teknik') {
                    $hasUserMany = [
                        'id' => $value->karyawan->user_id,
                        'karyawan_id' => $value->karyawan->id,
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
        }
        return $hasUserMany;
    }

    public function getListPersetujuanDirekturUtamaAttribute()
    {
        $result = [];
        $hasUserMany = [];
        if ($this->hasUserMany) {
            foreach ($this->hasUserMany as $key => $value) {
                if ($value->karyawan && $value->karyawan->nama_jabatan === 'Direktur Utama') {
                    $hasUserMany = [
                        'id' => $value->karyawan->user_id,
                        'karyawan_id' => $value->karyawan->id,
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
        }
        return $hasUserMany;
    }
    public function getListPersetujuanAsistenManajerAkuntansiAttribute()
    {
        $result = [];
        $hasUserMany = [];
        if ($this->hasUserMany) {
            foreach ($this->hasUserMany as $key => $value) {
                if ($value->karyawan && $value->karyawan->nama_jabatan === 'Asisten Manajer Akuntansi') {
                    $hasUserMany = [
                        'id' => $value->karyawan->user_id,
                        'karyawan_id' => $value->karyawan->id,
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
        }
        return $hasUserMany;
    }
    public function getListPersetujuanManajerKeuanganAttribute()
    {
        $result = [];
        $hasUserMany = [];
        if ($this->hasUserMany) {
            foreach ($this->hasUserMany as $key => $value) {
                if ($value->karyawan && $value->karyawan->nama_jabatan === 'Manajer Keuangan') {
                    $hasUserMany = [
                        'id' => $value->karyawan->user_id,
                        'karyawan_id' => $value->karyawan->id,
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
        }
        return $hasUserMany;
    }
    public function getListPersetujuanAsmenKasAttribute()
    {
        $result = [];
        $hasUserMany = [];
        if ($this->hasUserMany) {
            foreach ($this->hasUserMany as $key => $value) {
                if ($value->karyawan && $value->karyawan->nama_jabatan === 'Asisten Manajer Kas') {
                    $hasUserMany = [
                        'id' => $value->karyawan->user_id,
                        'karyawan_id' => $value->karyawan->id,
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
        }
        return $hasUserMany;
    }

    public function getNomorTagihanSetujuhAttribute()
    {
        $result = [];
        $nomor = $this->nomor_tagihan;

        if ($this->list_persetujuan_direktur_teknik) {
            if (isset($this->list_persetujuan_direktur_teknik['created_at'])) {
                $nomor = $nomor . getRomawi($this->list_persetujuan_direktur_teknik['created_at']->format('m')) . "/" . $this->list_persetujuan_direktur_teknik['created_at']->format('Y');
            }
        }

        return $nomor;
    }

    public function getTanggalVourcherAttribute()
    {
        $tanggal = '';

        if ($this->list_persetujuan_asisten_manajer_akuntansi) {
            if (isset($this->list_persetujuan_asisten_manajer_akuntansi['created_at'])) {
                $tanggal = tanggal_indonesia($this->list_persetujuan_asisten_manajer_akuntansi['created_at'], false, false);
            }
        }

        return $tanggal;
    }

    public function getTanggalBayarAttribute()
    {
        $tanggal = '';

        if ($this->list_persetujuan_asmen_kas) {
            if (isset($this->list_persetujuan_asmen_kas['created_at'])) {
                $tanggal = tanggal_indonesia($this->list_persetujuan_asmen_kas['created_at'], false, false);
            }
        }

        return $tanggal;
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
                        'karyawan_id' => $value->karyawan->id,

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
                    'karyawan_id' => $value->karyawan_id,
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
