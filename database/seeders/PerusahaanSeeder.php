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
        $jabatanWilayahSatu->nama = 'Admin Wilayah Satu';
        $jabatanWilayahSatu->divisi_id = $divisiDistribusi->id;
        $jabatanWilayahSatu->wilayah_id = $WilayahSatu->id;
        $jabatanWilayahSatu->save();

        $jabatanWilayahDua = new Jabatan();
        $jabatanWilayahDua->nama = 'Admin Wilayah Dua';
        $jabatanWilayahDua->divisi_id = $divisiDistribusi->id;
        $jabatanWilayahDua->wilayah_id = $WilayahDua->id;
        $jabatanWilayahDua->save();

        $jabatanWilayahTiga = new Jabatan();
        $jabatanWilayahTiga->nama = 'Admin Wilayah Tiga';
        $jabatanWilayahTiga->divisi_id = $divisiDistribusi->id;
        $jabatanWilayahTiga->wilayah_id = $WilayahTiga->id;
        $jabatanWilayahTiga->save();

        $jabatanWilayahEmpat = new Jabatan();
        $jabatanWilayahEmpat->nama = 'Admin Wilayah Empat';
        $jabatanWilayahEmpat->divisi_id = $divisiDistribusi->id;
        $jabatanWilayahEmpat->wilayah_id = $WilayahEmpat->id;
        $jabatanWilayahEmpat->save();

        $jabatanManagerDistribusi = new Jabatan();
        $jabatanManagerDistribusi->nama = 'Manager Distribusi';
        $jabatanManagerDistribusi->divisi_id = $divisiDistribusi->id;
        $jabatanManagerDistribusi->wilayah_id = $WilayahSamarinda->id;
        $jabatanManagerDistribusi->save();

        $jabatanStafPerencanaan = new Jabatan();
        $jabatanStafPerencanaan->nama = 'Staf Perencanaan';
        $jabatanStafPerencanaan->divisi_id = $divisiPerencanaan->id;
        $jabatanStafPerencanaan->wilayah_id = $WilayahSamarinda->id;
        $jabatanStafPerencanaan->save();

        $jabatanAsistenManagerDistribusi = new Jabatan();
        $jabatanAsistenManagerDistribusi->nama = 'Asisten Manager Distribusi';
        $jabatanAsistenManagerDistribusi->divisi_id = $divisiDistribusi->id;
        $jabatanAsistenManagerDistribusi->wilayah_id = $WilayahSamarinda->id;
        $jabatanAsistenManagerDistribusi->save();

        $jabatanAdminAsistenManagerSatu = new Jabatan();
        $jabatanAdminAsistenManagerSatu->nama = 'Admin Asisten Manager Distribusi';
        $jabatanAdminAsistenManagerSatu->divisi_id = $divisiDistribusi->id;
        $jabatanAdminAsistenManagerSatu->wilayah_id = $WilayahSatu->id;
        $jabatanAdminAsistenManagerSatu->save();

        $jabatanAdminAsistenManagerDua = new Jabatan();
        $jabatanAdminAsistenManagerDua->nama = 'Admin Asisten Manager Distribusi';
        $jabatanAdminAsistenManagerDua->divisi_id = $divisiDistribusi->id;
        $jabatanAdminAsistenManagerDua->wilayah_id = $WilayahDua->id;
        $jabatanAdminAsistenManagerDua->save();

        $jabatanAdminAsistenManagerTiga = new Jabatan();
        $jabatanAdminAsistenManagerTiga->nama = 'Admin Asisten Manager Distribusi';
        $jabatanAdminAsistenManagerTiga->divisi_id = $divisiDistribusi->id;
        $jabatanAdminAsistenManagerTiga->wilayah_id = $WilayahTiga->id;
        $jabatanAdminAsistenManagerTiga->save();

        $jabatanAdminAsistenManagerEmpat = new Jabatan();
        $jabatanAdminAsistenManagerEmpat->nama = 'Admin Asisten Manager Distribusi';
        $jabatanAdminAsistenManagerEmpat->divisi_id = $divisiDistribusi->id;
        $jabatanAdminAsistenManagerEmpat->wilayah_id = $WilayahEmpat->id;
        $jabatanAdminAsistenManagerEmpat->save();

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

        $jabatanManagerPengawas = new Jabatan();
        $jabatanManagerPengawas->nama = 'Manager Pengawas';
        $jabatanManagerPengawas->divisi_id = $divisiPengawas->id;
        $jabatanManagerPengawas->wilayah_id = $WilayahSamarinda->id;
        $jabatanManagerPengawas->save();

        $jabatanStafPengawas = new Jabatan();
        $jabatanStafPengawas->nama = 'Staf Pengawas';
        $jabatanStafPengawas->divisi_id = $divisiPengawas->id;
        $jabatanStafPengawas->wilayah_id = $WilayahSamarinda->id;
        $jabatanStafPengawas->save();

        $jabatanDirektur = new Jabatan();
        $jabatanDirektur->nama = 'Direktur Teknik';
        $jabatanDirektur->divisi_id = $divisiDistribusi->id;
        $jabatanDirektur->wilayah_id = $WilayahSamarinda->id;
        $jabatanDirektur->save();
    }
}
