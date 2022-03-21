<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Jenis;
use App\Models\Karyawan;
use App\Models\KategoriHargaJual;
use App\Models\MetodePembayaran;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Promosi;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'penjualan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'nama',
                'alias'    => 'Nama Penjualan',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Penjualan',
                'value'    => null
            ],

        ];
    }
    public function configForm()
    {

        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Penjualan',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'harga_beli',
                'input'    => 'rupiah',
                'alias'    => 'Harga Modal (HPP)',
                'value'    => null
            ],

        ];
    }

    public function model()
    {
        return new Penjualan();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //nama title
        if (!isset($this->title)) {
            $title =  "Tambah " . ucwords($this->route);
        } else {
            $title =  "Tambah " . ucwords($this->title);
        }

        //nama route dan action route
        $route =  $this->route;
        $store =  "store";

        //memanggil config form
        $form = $this->configform();

        $count = count($form);

        $colomField = $this->colomField($count);

        $countColom = $this->countColom($count);
        $countColomFooter = $this->countColomFooter($count);
        $dataPelanggan = Pelanggan::get();
        $dataProduk = Produk::where('inc_jual', 'ya')->get();
        $datakaryawan = Karyawan::get();
        $userId = Auth::user()->id;
        $userName = Auth::user()->name;

        $dataKategoriHargaJual = KategoriHargaJual::get();

        $dataMetodePembayaran = MetodePembayaran::get();
        $dataBank = Bank::get();
        $toko_id = '';
        $nama_toko = '';
        $alamat = '';
        $no_hp = '';
        $nama_karyawan = Auth::user()->name;
        if (!Auth::user()->hasRole('superadmin')) {
            $toko_id = Auth::user()->karyawan->hasToko()->first()->id;
            $nama_toko = Auth::user()->karyawan->hasToko()->first()->nama;
            $alamat = Auth::user()->karyawan->hasToko()->first()->alamat;
            $no_hp = Auth::user()->karyawan->hasToko()->first()->no_hp;
            $nama_karyawan = Auth::user()->karyawan->nama;
        }
        $dataPenjualan = Penjualan::count();
        // return $toko_id;
        if ($dataPenjualan >= 1) {
            $no = str_pad($dataPenjualan + 1, 4, "0", STR_PAD_LEFT);
            $noNota =  $no . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        } else {
            $no = str_pad(1, 4, "0", STR_PAD_LEFT);
            $noNota = $no . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        }
        $today = Carbon::now();
        $promosi = Promosi::where('mulai', '<=', $today)->where('selesai', '>=', $today)->orderBy('created_at', 'asc')->first();
        $today = tanggal_indonesia($today);
        $namaPromosi = '';
        $idPromosi = '';
        $diskonPromosi = '';
        $diskonPromosTampil = '';
        $keterangan = '';
        if ($promosi) {
            $namaPromosi = $promosi->nama;
            $idPromosi = $promosi->id;
            $diskonPromosi = $promosi->diskon;
            $keterangan = $promosi->keterangan;
            $diskonPromosTampil = 'Rp. ' . format_uang($promosi->diskon);
        }

        return view('penjualan.form', compact(
            'title',
            'form',
            'countColom',
            'colomField',
            'dataProduk',
            'today',
            'countColomFooter',
            'datakaryawan',
            'dataBank',
            'noNota',
            'userName',
            'userId',
            'store',
            'promosi',
            'dataKategoriHargaJual',
            'dataMetodePembayaran',
            'dataPelanggan',
            'idPromosi',
            'namaPromosi',
            'keterangan',
            'diskonPromosi',
            'diskonPromosTampil',
            'toko_id',
            'alamat',
            'nama_toko',
            'no_hp',
            'nama_karyawan',
            'route'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $uangbayar =  $request->uangbayar != null ?  $request->uangbayar : 0;
        $idPromosi =  $request->id_promosi != null  ?  $request->id_promosi : null;
        $uang_angsul =  $request->uang_angsul != null ?  $request->uang_angsul : 0;
        $idPelanggan =  $request->id_pelanggan;

        //get dari post form
        $data = $this->model();
        $data->no_nota = $request->nota_pembyaran;
        $data->uang_bayar = $uangbayar;
        $data->uang_angsul = $uang_angsul;
        $data->diskon = $request->diskonPembayaran;
        $data->total = $request->grandtotal;
        $data->grandtotal = $request->totalbayarPembayaran;
        $data->bank_id = $request->bank_id;
        $data->toko_id = $request->toko_id;
        $data->pelanggan_id = $idPelanggan;
        $data->promosi_id = $idPromosi;
        $data->user_id = Auth::user()->id;

        $listitem = [];
        $produk = [];
        $produkbahan = [];
        $karyawan = [];
        $karyawan_id = [];
        $type_diskon = [];
        $komisi = [];
        $produkIncNota = [];

        $harga  = $request->harga;
        $iditem  = $request->iditem;
        $diskon_produk  = $request->diskon_produk;
        $type_diskon_promosi  = $request->type_diskon_promosi_pembayaran;
        $jumlahitem_pembayaran = $request->jumlah_item__pembayaran_;
        $totalharga_pembayaran  = $request->totalharga_pembayaran;
        $karyawan_pembayaran_id  = $request->karyawan_pembayaran_id;

        // list jenis inc nota
        $listJenisIncNota = Jenis::where('inc_nota', 'ya')->pluck('id');

        // detail pelanggan
        $pelanggan = Pelanggan::find($idPelanggan);

        // check promo tertinggi
        $promoMax = Promosi::max('nota');

        if (isset($request->iditem)) {
            $incNota = 0;
            foreach ($jumlahitem_pembayaran as $index => $value) {
                $iditem[$index] = $request->iditem[$index];
                $type_diskon[$index] =  $type_diskon_promosi[$index] != null ? $type_diskon_promosi[$index] : null;
                $produk[$index] = Produk::with('hasBahan')->find($iditem[$index]);
                $produkIncNota[$index] = Produk::whereIn('jenis_id', $listJenisIncNota)->find($iditem[$index]);

                $karyawan[$index] = Karyawan::with('hasJabatan')->find($karyawan_pembayaran_id[$index]);

                if ($karyawan[$index]) {
                    $karyawan_id[$index] = $karyawan[$index]->id;
                    $komisi[$index] = $karyawan[$index]->komisi;
                } else {
                    $karyawan_id[$index] = null;
                    $komisi[$index] = 0;
                }
                // return $karyawan_id[$index];
                $hargaBeli[$index] = $produk[$index]->harga_beli;
                $hasBahan[$index] = $produk[$index]->hasBahan;
                if ($produk[$index]) {
                    $listitem[$index] = [
                        'type_diskon' => $type_diskon[$index],
                        'harga_beli' => $hargaBeli[$index],
                        'harga_jual' => $harga[$index],
                        'qty' => $value,
                        'total_harga' => $totalharga_pembayaran[$index],
                        'diskon_produk' => $diskon_produk[$index],
                        'karyawan_id' =>  $karyawan_id[$index],
                        'komisi' => $komisi[$index],
                        'promosi_id' => $idPromosi,
                    ];

                    //cek mengurangi stok
                    if ($produk[$index]->inc_stok == "ya") {
                        $produk[$index]->stok =  $produk[$index]->stok - $value;
                        $produk[$index]->save();
                    }
                    // cek mengurangi bahan baku
                    if (isset($hasBahan[$index])) {
                        foreach ($hasBahan[$index] as $key => $item) {

                            $produkbahan[$index][$key] = Produk::find($item->id);
                            $produkbahan[$index][$key]->stok = $produkbahan[$index][$key]->stok - ($item->pivot->qty * $value[$key]);
                            $produkbahan[$index][$key]->save();
                        }
                    }
                }
            }
            $syncData  = array_combine($iditem, $listitem);

            // menghitung jumlah jenis produk incl nota;
            foreach ($produkIncNota as $index => $value) {
                if ($value != null) {
                    $incNota = 1;
                }
            }
            $data->save();
            $data->hasProduk()->sync($syncData);
        }
        // return $incNota;
        // apabila jenis produk incl nota;
        $jumlah_nota = $pelanggan->jumlah_nota;
        if ($incNota > 0) {
            $jumlahBonus = $pelanggan->bonus_nota;
            if ($jumlahBonus == $promoMax) {
                $pelanggan->bonus_nota = 0;
            } else {
                $pelanggan->bonus_nota = $jumlahBonus + 1;
            }
            $pelanggan->jumlah_nota = $jumlah_nota + 1;
            $pelanggan->update();
        }



        return redirect()->route($this->route . '.create')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil Ditambahkan')->with('Class', 'success');
    }
}
