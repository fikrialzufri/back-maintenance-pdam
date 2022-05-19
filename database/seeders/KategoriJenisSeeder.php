<?php

namespace Database\Seeders;

use App\Models\Jenis;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Str;

class KategoriJenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listKategori = [
            ['nama' => 'Galian'],
            ['nama' => 'Bahan'],
            ['nama' => 'Alat Bantu'],
            ['nama' => 'Pekerjaan'],
            ['nama' => 'Transportasi'],
        ];

        foreach ($listKategori as $key => $value) {
            $nama = $value['nama'];

            $kategoriGalian[$key] = Kategori::whereSlug(Str::slug($nama))->first();

            if (!$kategoriGalian[$key]) {
                $kategoriGalian[$key] = new Kategori();
                $kategoriGalian[$key]->nama = $nama;
                $kategoriGalian[$key]->save();
            }
            $JenisGalian[$key] = Jenis::whereSlug(Str::slug($nama))->first();
            if (!$JenisGalian[$key]) {
                $JenisGalian[$key] = new Jenis();
                $JenisGalian[$key]->nama = $nama;
                $JenisGalian[$key]->kategori_id = $kategoriGalian[$key]->id;
                $JenisGalian[$key]->save();
            }
        }
    }
}
