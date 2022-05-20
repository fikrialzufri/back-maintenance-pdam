<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Jenis;
use App\Models\Satuan;
use Str;
use Illuminate\Database\Seeder;

class GalianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listGalian = [
            [
                'nama' => 'Tanah Keras',
                'harga' => 111125,
                'harga_malam' => 111125
            ],
            [
                'nama' => 'Tanah Cor',
                'harga' => 120000,
                'harga_malam' => 120000
            ],
            [
                'nama' => 'Gorong',
                'harga' => 640000,
                'harga_malam' => 640000
            ],
            [
                'nama' => 'Aspal',
                'harga' => 130000,
                'harga_malam' => 130000
            ],
            [
                'nama' => 'Pengembalian Galian',
                'harga' => 50000,
                'harga_malam' => 50000
            ],
            [
                'nama' => 'Tanah Biasa',
                'harga' => 75000,
                'harga_malam' => 75000
            ],
        ];

        $JenisGalian = Jenis::whereSlug('galian')->first();
        $satuan = Satuan::whereSlug('meter')->first();

        foreach ($listGalian as $key => $value) {

            $nama = $value['nama'];
            $harga = $value['harga'];
            $harga_malam = $value['harga_malam'];
            $item[$key] = Item::whereSlug(Str::slug($nama))->first();
            if (!$item[$key]) {
                $item[$key] = new Item();
                $item[$key]->nama = $nama;
                $item[$key]->harga = $harga;
                $item[$key]->harga_malam = $harga_malam;
                $item[$key]->jenis_id = $JenisGalian->id;
                $item[$key]->satuan_id = $satuan->id;
                $item[$key]->save();
            }
        }
    }
}
