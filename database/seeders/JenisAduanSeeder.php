<?php

namespace Database\Seeders;

use App\Models\JenisAduan;
use Illuminate\Database\Seeder;

class JenisAduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenissatu = new JenisAduan();
        $jenissatu->nama = 'Air Keruh, Berasa & Berbau';
        $jenissatu->save();

        $jenisdua = new JenisAduan();
        $jenisdua->nama = 'Tekanan Air Rendah / Tidak Mengalir';
        $jenisdua->save();

        $jenistiga = new JenisAduan();
        $jenistiga->nama = 'Pipa (Induk/Sekunder/Tersier) Bocor';
        $jenistiga->save();

        $jenisempat = new JenisAduan();
        $jenisempat->nama = 'Pipa Dinas/Taping/Meter Air Bocor';
        $jenisempat->save();

        $jenislima = new JenisAduan();
        $jenislima->nama = 'Meter Air Rusak/Macet';
        $jenislima->save();

        $jenisenam = new JenisAduan();
        $jenisenam->nama = 'Kaca Meter Air Buram/Pecah';
        $jenisenam->save();

        $jenistujuh = new JenisAduan();
        $jenistujuh->nama = 'Pemutusan Sementara/Permanen';
        $jenistujuh->save();

        $jenisPenyambungan = new JenisAduan();
        $jenisPenyambungan->nama = 'Penyambungan Kembali';
        $jenisPenyambungan->save();

        $jenisIsolasi = new JenisAduan();
        $jenisIsolasi->nama = 'Isolasi Lokasi Kebakaran';
        $jenisIsolasi->save();

        $jenisPasang = new JenisAduan();
        $jenisPasang->nama = 'Pasang/Ganti Pipa & Assesories';
        $jenisPasang->save();
    }
}
