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
        $WilayahSamarinda->save();

        $WilayahSatu = new Wilayah();
        $WilayahSatu->nama = 'Wilayah Satu';
        $WilayahSatu->save();

        $WilayahDua = new Wilayah();
        $WilayahDua->nama = 'Wilayah Dua';
        $WilayahDua->save();

        $WilayahTiga = new Wilayah();
        $WilayahTiga->nama = 'Wilayah Tiga';
        $WilayahTiga->save();

        $WilayahEmpat = new Wilayah();
        $WilayahEmpat->nama = 'Wilayah Empat';
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
        $jabatanManagerDistribusi->nama = 'Manajer Distribusi';
        $jabatanManagerDistribusi->divisi_id = $divisiDistribusi->id;
        $jabatanManagerDistribusi->wilayah_id = $WilayahSamarinda->id;
        $jabatanManagerDistribusi->save();

        $jabatanAsistenManagerDistribusi = new Jabatan();
        $jabatanAsistenManagerDistribusi->nama = 'Asisten Manajer Distribusi';
        $jabatanAsistenManagerDistribusi->divisi_id = $divisiDistribusi->id;
        $jabatanAsistenManagerDistribusi->wilayah_id = $WilayahSamarinda->id;
        $jabatanAsistenManagerDistribusi->save();

        $jabatanAdminAsistenManajerSatu = new Jabatan();
        $jabatanAdminAsistenManajerSatu->nama = 'Admin Asisten Manajer Distribusi';
        $jabatanAdminAsistenManajerSatu->divisi_id = $divisiDistribusi->id;
        $jabatanAdminAsistenManajerSatu->wilayah_id = $WilayahSatu->id;
        $jabatanAdminAsistenManajerSatu->save();

        $jabatanAdminAsistenManajerDua = new Jabatan();
        $jabatanAdminAsistenManajerDua->nama = 'Admin Asisten Manajer Distribusi';
        $jabatanAdminAsistenManajerDua->divisi_id = $divisiDistribusi->id;
        $jabatanAdminAsistenManajerDua->wilayah_id = $WilayahDua->id;
        $jabatanAdminAsistenManajerDua->save();

        $jabatanAdminAsistenManajerTiga = new Jabatan();
        $jabatanAdminAsistenManajerTiga->nama = 'Admin Asisten Manajer Distribusi';
        $jabatanAdminAsistenManajerTiga->divisi_id = $divisiDistribusi->id;
        $jabatanAdminAsistenManajerTiga->wilayah_id = $WilayahTiga->id;
        $jabatanAdminAsistenManajerTiga->save();

        $jabatanAdminAsistenManajerEmpat = new Jabatan();
        $jabatanAdminAsistenManajerEmpat->nama = 'Admin Asisten Manajer Distribusi';
        $jabatanAdminAsistenManajerEmpat->divisi_id = $divisiDistribusi->id;
        $jabatanAdminAsistenManajerEmpat->wilayah_id = $WilayahEmpat->id;
        $jabatanAdminAsistenManajerEmpat->save();

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

        $jabatanDirektur = new Jabatan();
        $jabatanDirektur->nama = 'Direktur Teknis';
        $jabatanDirektur->divisi_id = $divisiDistribusi->id;
        $jabatanDirektur->wilayah_id = $WilayahSamarinda->id;
        $jabatanDirektur->save();
    }
}
