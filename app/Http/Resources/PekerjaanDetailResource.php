<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PekerjaanDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' =>  $this->id,
            'nomor_pekerjaan' =>  $this->nomor_pekerjaan,
            'nomor_pelaksaan_pekerjaan' =>  $this->nomor_pelaksanaan_pekerjaan,
            'slug' =>  $this->slug,
            'status' =>  $this->status,
            'lokasi_aduan' =>  $this->lokasi,
            'lokasi_pekerjaan' =>  $this->lokasi_pekerjaan,
            'lat_long' =>  $this->lat_long,
            'nama_rekanan' =>  $this->rekanan,
            'foto_lokasi' =>  $this->foto_lokasi,
            'foto_bahan' =>  $this->foto_bahan,
            'foto_penyelesaian' =>  $this->foto_penyelesaian,
            'galian_pekerjaan' =>  $this->galian_pekerjaan,
            'jenis_aduan' =>  $this->jenis_aduan,
            'atas_nama' =>  $this->atas_nama,
            'no_hp' =>  $this->no_hp,
            'no_pelanggan' =>  $this->no_pelanggan,
            'item_pekerjaan' =>  $this->list_pekerjaan,
            'item_bahan' =>  $this->list_bahan,
            'item_alat_bantu' =>  $this->list_alat_bantu,
            'item_transportasi' =>  $this->list_transportasi,
            'sumber_informasi' =>  $this->sumber_informasi,
            'keterangan_aduan' =>  $this->keterangan_aduan,
            'keterangan_barang' =>  $this->keterangan_barang,
            'keterangan_penyelesaian' =>  $this->keterangan_penyelesaian,
            'created_at' =>  $this->created_at,
            'status_mobile' =>  $this->status_mobile,
        ];
    }
}
