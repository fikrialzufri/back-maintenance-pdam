<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Pekerjaan extends JsonResource
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
            'slug' =>  $this->slug,
            'lokasi_aduan' =>  $this->lokasi,
            'lokasi_pekerjaan' =>  $this->lokasi_pekerjaan,
            'status' =>  $this->status,
            'tanggal_selesai' =>  $this->tanggal_selesai,
            'created_at' =>  $this->created_at,
            'status_mobile' =>  $this->status_mobile,
        ];
    }
}
