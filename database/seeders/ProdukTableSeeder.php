<?php

namespace Database\Seeders;

use App\Models\Jenis;
use App\Models\Kategori;
use App\Models\KategoriHargaJual;
use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Database\Seeder;

class ProdukTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $satuanPsc = new Satuan();
        $satuanPsc->nama = 'Psc';
        $satuanPsc->save();

        $satuanLusin = new Satuan();
        $satuanLusin->nama = 'Lusin';
        $satuanLusin->save();

        $satuanMililiter = new Satuan();
        $satuanMililiter->nama = 'Mililiter';
        $satuanMililiter->save();

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
        $JenisLayanan->inc_nota = 'ya';
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

        $JenisBahanBaku = new Jenis();
        $JenisBahanBaku->nama = 'Bahan Baku';
        $JenisBahanBaku->kategori_id = $kategoriBahanBaku->id;
        $JenisBahanBaku->save();


        $KategoriHargaJualMember = new KategoriHargaJual();
        $KategoriHargaJualMember->nama = 'Member';
        $KategoriHargaJualMember->save();

        // $KategoriHargaJualPelanggan = new KategoriHargaJual();
        // $KategoriHargaJualPelanggan->nama = 'Pelanggan';
        // $KategoriHargaJualPelanggan->save();

        $ProdukKopi = new Produk();
        $ProdukKopi->nama = 'Kopi';
        $ProdukKopi->harga_beli = '10000';
        $ProdukKopi->jenis_id = $JenisMinuman->id;
        $ProdukKopi->satuan_id = $satuanPsc->id;
        $ProdukKopi->save();

        $id_kategori_harga_Kopi = [
            $KategoriHargaJualMember->id,
            // $KategoriHargaJualPelanggan->id,
        ];

        $kategoriHargaKopi = [
            '15000',
        ];

        $extra = array_map(function ($harga) {
            return ['harga_jual' => $harga];
        }, $kategoriHargaKopi);

        $array_combine = array_combine($id_kategori_harga_Kopi, $extra);

        if (isset($kategoriHargaKopi)) {
            $ProdukKopi->KategoriHargaJual()->sync($array_combine);
        }

        $ProdukPotongRambut = new Produk();
        $ProdukPotongRambut->nama = 'Potong Rambut';
        $ProdukPotongRambut->harga_beli = '50000';
        $ProdukPotongRambut->karyawan = 'ya';
        $ProdukPotongRambut->jenis_id = $JenisLayanan->id;
        $ProdukPotongRambut->satuan_id = $satuanLayanan->id;

        $ProdukPotongRambut->save();

        //harga_jual
        //
        $id_kategori_harga_Potong_rambut = [
            $KategoriHargaJualMember->id,
            // $KategoriHargaJualPelanggan->id,
        ];

        $kategoriHargaPotongRambut = [
            '55000',
        ];

        $extra = array_map(function ($harga) {
            return ['harga_jual' => $harga];
        }, $kategoriHargaPotongRambut);

        $array_combine = array_combine($id_kategori_harga_Potong_rambut, $extra);

        if (isset($kategoriHargaPotongRambut)) {
            $ProdukPotongRambut->KategoriHargaJual()->sync($array_combine);
        }

        $ProdukPotongRambut = new Produk();
        $ProdukPotongRambut->nama = 'Cuci Rambut';
        $ProdukPotongRambut->harga_beli = '60000';
        $ProdukPotongRambut->karyawan = 'ya';
        $ProdukPotongRambut->jenis_id = $JenisLayanan->id;
        $ProdukPotongRambut->satuan_id = $satuanLayanan->id;

        $ProdukPotongRambut->save();

        //harga_jual
        //
        $id_kategori_harga_Potong_rambut = [
            $KategoriHargaJualMember->id,
            // $KategoriHargaJualPelanggan->id,
        ];

        $kategoriHargaPotongRambut = [
            '75000',
        ];

        $extra = array_map(function ($harga) {
            return ['harga_jual' => $harga];
        }, $kategoriHargaPotongRambut);

        $array_combine = array_combine($id_kategori_harga_Potong_rambut, $extra);

        if (isset($kategoriHargaPotongRambut)) {
            $ProdukPotongRambut->KategoriHargaJual()->sync($array_combine);
        }
    }
}
