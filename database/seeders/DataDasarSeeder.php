<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Gudang;
use App\Models\Jenis;
use App\Models\Kategori;
use App\Models\Pusat;
use App\Models\Satuan;
use App\Models\Supplier;
use App\Models\Toko;
use Illuminate\Database\Seeder;

class DataDasarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $satuan = new Satuan();
        $satuan->nama = 'PSC';
        $satuan->save();

        $satuanLusin = new Satuan();
        $satuanLusin->nama = 'Lusin';
        $satuanLusin->save();

        $satuanLayanan = new Satuan();
        $satuanLayanan->nama = 'Layanan';
        $satuanLayanan->save();

        $kategoriLayanan = new Kategori();
        $kategoriLayanan->nama = 'Layanan';
        $kategoriLayanan->save();

        $kategoriProduk = new Kategori();
        $kategoriProduk->nama = 'Produk';
        $kategoriProduk->save();

        $kategoriBahanBaku = new Kategori();
        $kategoriBahanBaku->nama = 'Bahan Baku';
        $kategoriBahanBaku->save();

        $JenisLayanan = new Jenis();
        $JenisLayanan->nama = 'Layanan';
        $JenisLayanan->kategori_id = $kategoriLayanan->id;
        $JenisLayanan->save();

        $JenisMinuman = new Jenis();
        $JenisMinuman->nama = 'Minuman';
        $JenisMinuman->kategori_id = $kategoriProduk->id;
        $JenisMinuman->save();

        $JenisMakanan = new Jenis();
        $JenisMakanan->nama = 'Makanan';
        $JenisMakanan->kategori_id = $kategoriProduk->id;
        $JenisMakanan->save();

        $PusatSatu = new Pusat();
        $PusatSatu->nama = 'Pusat Satu';
        $PusatSatu->alamat = 'Jl. Pusat Satu';
        $PusatSatu->no_hp = '0853113898';
        $PusatSatu->email = 'pusatsatu@gmail.com';
        $PusatSatu->save();

        $cabangSatu = new Cabang();
        $cabangSatu->nama = 'Cabang Satu';
        $cabangSatu->alamat = 'Jl. cabang Satu';
        $cabangSatu->no_hp = '0855113898';
        $cabangSatu->email = 'cabangsatu@gmail.com';
        $cabangSatu->pusat_id =  $PusatSatu->id;
        $cabangSatu->save();

        $GudangSatu = new Gudang();
        $GudangSatu->nama = 'Gudang Satu';
        $GudangSatu->alamat = 'Jl. Gudang Satu';
        $GudangSatu->no_hp = '0851113898';
        $GudangSatu->email = 'Gudangsatu@gmail.com';
        $GudangSatu->cabang_id =  $cabangSatu->id;
        $GudangSatu->save();

        $TokoSatu = new Toko();
        $TokoSatu->nama = 'Toko Satu';
        $TokoSatu->alamat = 'Jl. Toko Satu';
        $TokoSatu->no_hp = '0851241898';
        $TokoSatu->email = 'Tokosatu@gmail.com';
        $TokoSatu->cabang_id =  $cabangSatu->id;
        $TokoSatu->save();

        $supplierSatu = new Supplier();
        $supplierSatu->nama = 'Supplier Satu';
        $supplierSatu->alamat = 'Jl. Supplier Satu';
        $supplierSatu->no_hp = '0855241898';
        $supplierSatu->email = 'suppliersatu@gmail.com';
        $supplierSatu->save();
    }
}
