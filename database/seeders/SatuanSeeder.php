<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $satuanPsc = new Satuan();
        $satuanPsc->nama = 'Pcs';
        $satuanPsc->save();

        $satuanLusin = new Satuan();
        $satuanLusin->nama = 'Lusin';
        $satuanLusin->save();

        $satuanMeter = new Satuan();
        $satuanMeter->nama = 'Meter';
        $satuanMeter->save();

        $satuanKg = new Satuan();
        $satuanKg->nama = 'Kg';
        $satuanKg->save();

        $satuanCm = new Satuan();
        $satuanCm->nama = 'Cm';
        $satuanCm->save();

        $satuanLiter = new Satuan();
        $satuanLiter->nama = 'Liter';
        $satuanLiter->save();

        $satuanTitik = new Satuan();
        $satuanTitik->nama = 'Titik';
        $satuanTitik->save();

        $satuanLs = new Satuan();
        $satuanLs->nama = 'LS';
        $satuanLs->save();

        $satuanBuah = new Satuan();
        $satuanBuah->nama = 'Buah';
        $satuanBuah->save();

        $satuanLs = new Satuan();
        $satuanLs->nama = '121';
        $satuanLs->save();
    }
}
