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
                'harga' => 300000,
                'harga_malam' => 350000
            ],
            [
                'nama' => 'Tanah Cor',
                'harga' => 300000,
                'harga_malam' => 350000
            ],
            [
                'nama' => 'Gorong',
                'harga' => 300000,
                'harga_malam' => 350000

            ],
            [
                'nama' => 'Aspal',
                'harga' => 300000,
                'harga_malam' => 350000
            ],
            [
                'nama' => 'Pengembalian Galian',
                'harga' => 300000,
                'harga_malam' => 350000
            ],
            [
                'nama' => 'Tanah Biasa',
                'harga' => 300000,
                'harga_malam' => 350000
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
