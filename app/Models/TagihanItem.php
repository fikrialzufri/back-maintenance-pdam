<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanItem extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'tagihan_item';
    protected $guarded = ['id'];
    protected $append = ['tanggal_adjust_indo'];
    protected $fillable = [
        'nomor_tagihan',
        'nomor_bap',
        'kode_vocher',
        'aduan_id',
        'rekanan_id',
        'penunjukan_pekerjaan_id',
        'user_id'
    ];

    public function hasItem()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function getItemJenisAttribute()
    {
        if ($this->hasItem) {
            return $this->hasItem->jenis;
        }
    }

    public function getTanggalAdjustIndoAttribute()
    {
        if ($this->tanggal_adjust) {
            return date('d-m-Y', strtotime($this->tanggal_adjust));
        }
    }
}
