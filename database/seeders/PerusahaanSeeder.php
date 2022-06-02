<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departemenTeknik = new Departemen();
        $departemenTeknik->nama = 'Teknik';
        $departemenTeknik->save();

        $departemenHumas = new Departemen();
        $departemenHumas->nama = 'Humas';
        $departemenHumas->save();

        $departemenUmum = new Departemen();
        $departemenUmum->nama = 'Umum';
        $departemenUmum->save();

        $departemenKeuangan = new Departemen();
        $departemenKeuangan->nama = 'Keuangan';
        $departemenKeuangan->save();

        $WilayahSamarinda = new Wilayah();
        $WilayahSamarinda->nama = 'Wilayah Samarinda';
        $WilayahSamarinda->singkatan = 'smd';
        $WilayahSamarinda->save();

        $WilayahSatu = new Wilayah();
        $WilayahSatu->nama = 'Wilayah Satu';
        $WilayahSatu->singkatan = 'I';
        $WilayahSatu->save();

        $WilayahDua = new Wilayah();
        $WilayahDua->nama = 'Wilayah Dua';
        $WilayahDua->singkatan = 'II';
        $WilayahDua->save();

        $WilayahTiga = new Wilayah();
        $WilayahTiga->nama = 'Wilayah Tiga';
        $WilayahTiga->singkatan = 'III';
        $WilayahTiga->save();

        $WilayahEmpat = new Wilayah();
        $WilayahEmpat->nama = 'Wilayah Empat';
        $WilayahEmpat->singkatan = 'IV';
        $WilayahEmpat->save();

        $divisiHumas = new Divisi();
        $divisiHumas->nama = 'Humas';
        $divisiHumas->departemen_id = $departemenHumas->id;
        $divisiHumas->save();

        $divisiKeuangan = new Divisi();
        $divisiKeuangan->nama = 'Keuangan';
        $divisiKeuangan->departemen_id = $departemenKeuangan->id;
        $divisiKeuangan->save();

        $divisiUmum = new Divisi();
        $divisiUmum->nama = 'Umum';
        $divisiUmum->departemen_id = $departemenUmum->id;
        $divisiUmum->save();

        $divisiPengawas = new Divisi();
        $divisiPengawas->nama = 'Pengawas';
        $divisiPengawas->departemen_id = $departemenTeknik->id;
        $divisiPengawas->save();

        $divisiDistribusi = new Divisi();
        $divisiDistribusi->nama = 'Distribusi ';
        $divisiDistribusi->departemen_id = $departemenTeknik->id;
        $divisiDistribusi->save();

        $divisiPerencanaan = new Divisi();
        $divisiPerencanaan->nama = 'Perencanaan';
        $divisiPerencanaan->departemen_id = $departemenTeknik->id;
        $divisiPerencanaan->save();

        $jabatanWilayahSatu = new Jabatan();
        $jabatanWilayahSatu->nama = 'Admin Distribusi Wilayah Satu';
        $jabatanWilayahSatu->divisi_id = $divisiDistribusi->id;
        $jabatanWilayahSatu->wilayah_id = $WilayahSatu->id;
        $jabatanWilayahSatu->save();

        $jabatanWilayahDua = new Jabatan();
        $jabatanWilayahDua->nama = 'Admin Distribusi Wilayah Dua';
        $jabatanWilayahDua->divisi_id = $divisiDistribusi->id;
        $jabatanWilayahDua->wilayah_id = $WilayahDua->id;
        $jabatanWilayahDua->save();

        $jabatanWilayahTiga = new Jabatan();
        $jabatanWilayahTiga->nama = 'Admin Distribusi Wilayah Tiga';
        $jabatanWilayahTiga->divisi_id = $divisiDistribusi->id;
        $jabatanWilayahTiga->wilayah_id = $WilayahTiga->id;
        $jabatanWilayahTiga->save();

        $jabatanWilayahEmpat = new Jabatan();
        $jabatanWilayahEmpat->nama = 'Admin Distribusi Wilayah Empat';
        $jabatanWilayahEmpat->divisi_id = $divisiDistribusi->id;
        $jabatanWilayahEmpat->wilayah_id = $WilayahEmpat->id;
        $jabatanWilayahEmpat->save();

        $jabatanManajerDistribusi = new Jabatan();
        $jabatanManajerDistribusi->nama = 'Manajer Distribusi';
        $jabatanManajerDistribusi->divisi_id = $divisiDistribusi->id;
        $jabatanManajerDistribusi->wilayah_id = $WilayahSamarinda->id;
        $jabatanManajerDistribusi->save();

        $jabatanStafPerencanaan = new Jabatan();
        $jabatanStafPerencanaan->nama = 'Staf Perencanaan';
        $jabatanStafPerencanaan->divisi_id = $divisiPerencanaan->id;
        $jabatanStafPerencanaan->wilayah_id = $WilayahSamarinda->id;
        $jabatanStafPerencanaan->save();

        $jabatanStafWilayahSatu = new Jabatan();
        $jabatanStafWilayahSatu->nama = 'Staf Distribusi Wilayah Satu';
        $jabatanStafWilayahSatu->divisi_id = $divisiDistribusi->id;
        $jabatanStafWilayahSatu->wilayah_id = $WilayahSatu->id;
        $jabatanStafWilayahSatu->save();

        $jabatanStafWilayahDua = new Jabatan();
        $jabatanStafWilayahDua->nama = 'Staf Distribusi Wilayah Dua';
        $jabatanStafWilayahDua->divisi_id = $divisiDistribusi->id;
        $jabatanStafWilayahDua->wilayah_id = $WilayahDua->id;
        $jabatanStafWilayahDua->save();

        $jabatanStafWilayahTiga = new Jabatan();
        $jabatanStafWilayahTiga->nama = 'Staf Distribusi Wilayah Tiga';
        $jabatanStafWilayahTiga->divisi_id = $divisiDistribusi->id;
        $jabatanStafWilayahTiga->wilayah_id = $WilayahTiga->id;
        $jabatanStafWilayahTiga->save();

        $jabatanStafWilayahEmpat = new Jabatan();
        $jabatanStafWilayahEmpat->nama = 'Staf Distribusi Wilayah Empat';
        $jabatanStafWilayahEmpat->divisi_id = $divisiDistribusi->id;
        $jabatanStafWilayahEmpat->wilayah_id = $WilayahEmpat->id;
        $jabatanStafWilayahEmpat->save();

        $jabatanAsistenManajerSatu = new Jabatan();
        $jabatanAsistenManajerSatu->nama = 'Asisten Manajer Distribusi Wilayah Satu';
        $jabatanAsistenManajerSatu->divisi_id = $divisiDistribusi->id;
        $jabatanAsistenManajerSatu->wilayah_id = $WilayahSatu->id;
        $jabatanAsistenManajerSatu->save();

        $jabatanAsistenManajerDua = new Jabatan();
        $jabatanAsistenManajerDua->nama = 'Asisten Manajer Distribusi Wilayah Dua';
        $jabatanAsistenManajerDua->divisi_id = $divisiDistribusi->id;
        $jabatanAsistenManajerDua->wilayah_id = $WilayahDua->id;
        $jabatanAsistenManajerDua->save();

        $jabatanAsistenManajerTiga = new Jabatan();
        $jabatanAsistenManajerTiga->nama = 'Asisten Manajer Distribusi Wilayah Tiga';
        $jabatanAsistenManajerTiga->divisi_id = $divisiDistribusi->id;
        $jabatanAsistenManajerTiga->wilayah_id = $WilayahTiga->id;
        $jabatanAsistenManajerTiga->save();

        $jabatanAsistenManajerEmpat = new Jabatan();
        $jabatanAsistenManajerEmpat->nama = 'Asisten Manajer Distribusi Wilayah Empat';
        $jabatanAsistenManajerEmpat->divisi_id = $divisiDistribusi->id;
        $jabatanAsistenManajerEmpat->wilayah_id = $WilayahEmpat->id;
        $jabatanAsistenManajerEmpat->save();

        $jabatanUmum = new Jabatan();
        $jabatanUmum->nama = 'Umum';
        $jabatanUmum->divisi_id = $divisiUmum->id;
        $jabatanUmum->wilayah_id = $WilayahSamarinda->id;
        $jabatanUmum->save();

        $jabatanHumas = new Jabatan();
        $jabatanHumas->nama = 'Humas';
        $jabatanHumas->divisi_id = $divisiHumas->id;
        $jabatanHumas->wilayah_id = $WilayahSamarinda->id;
        $jabatanHumas->save();

        $jabatanKeuangan = new Jabatan();
        $jabatanKeuangan->nama = 'Keuangan';
        $jabatanKeuangan->divisi_id = $divisiKeuangan->id;
        $jabatanKeuangan->wilayah_id = $WilayahSamarinda->id;
        $jabatanKeuangan->save();

        $jabatanManajerPengawas = new Jabatan();
        $jabatanManajerPengawas->nama = 'Manajer Pengawas';
        $jabatanManajerPengawas->divisi_id = $divisiPengawas->id;
        $jabatanManajerPengawas->wilayah_id = $WilayahSamarinda->id;
        $jabatanManajerPengawas->save();

        $jabatanStafPengawas = new Jabatan();
        $jabatanStafPengawas->nama = 'Staf Pengawas';
        $jabatanStafPengawas->divisi_id = $divisiPengawas->id;
        $jabatanStafPengawas->wilayah_id = $WilayahSamarinda->id;
        $jabatanStafPengawas->save();

        $jabatanAsistenManajerPerencaan = new Jabatan();
        $jabatanAsistenManajerPerencaan->nama = 'Asisten Manajer Perencanaan';
        $jabatanAsistenManajerPerencaan->divisi_id = $divisiPengawas->id;
        $jabatanAsistenManajerPerencaan->wilayah_id = $WilayahSamarinda->id;
        $jabatanAsistenManajerPerencaan->save();

        $jabatanManajerPerencaan = new Jabatan();
        $jabatanManajerPerencaan->nama = 'Manajer Perencanaan';
        $jabatanManajerPerencaan->divisi_id = $divisiPengawas->id;
        $jabatanManajerPerencaan->wilayah_id = $WilayahSamarinda->id;
        $jabatanManajerPerencaan->save();

        $jabatanDirektur = new Jabatan();
        $jabatanDirektur->nama = 'Direktur Teknik';
        $jabatanDirektur->divisi_id = $divisiDistribusi->id;
        $jabatanDirektur->wilayah_id = $WilayahSamarinda->id;
        $jabatanDirektur->save();
    }
}
