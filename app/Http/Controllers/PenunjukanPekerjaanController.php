<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aduan;
use App\Models\GalianPekerjaan;
use App\Models\Item;
use App\Models\Jenis;
use App\Models\PenunjukanPekerjaan;
use App\Models\PelaksanaanPekerjaan;
use App\Models\JenisAduan;
use App\Models\Kategori;
use App\Models\Notifikasi;
use App\Models\Rekanan;
use App\Models\Tagihan;
use App\Models\Wilayah;
use DB;
use Excel;
use Str;
use Carbon\Carbon;

class PenunjukanPekerjaanController extends Controller
{
    public function __construct()
    {
        $this->tambah = 'false';
        $this->route = 'penunjukan_pekerjaan';
        $this->middleware('permission:view-penunjukan-pekerjaan', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-penunjukan-pekerjaan', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-penunjukan-pekerjaan', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-penunjukan-pekerjaan', ['only' => ['delete']]);
    }

    public function index()
    {
        $title = 'List Pekerjaan';
        $route = 'penunjukan_pekerjaan';
        $search = request()->search;
        $limit = request()->limit ?? 30;

        $query = Aduan::query()->orderBy('status', 'asc');
        if ($search) {
            $query = $query->where('no_ticket', 'like', "%" . $search . "%")->orWhere('no_aduan', 'like', "%" . $search . "%");
        }

        if (auth()->user()->hasRole('superadmin')) {
            $penunjukan = $query->orderBy('created_at', 'desc')->paginate($limit);
        } else {
            $penunjukan = $query->where('wilayah_id', auth()->user()->karyawan->id_wilayah)->orderBy('created_at', 'desc')->paginate($limit);
        }

        $count_penunjukan = $query->count();
        $no = $limit * ($penunjukan->currentPage() - 1);

        return view('penunjukan_pekerjaan.index', compact(
            'title',
            'route',
            'penunjukan',
            'no',
            'count_penunjukan',
            'search',
            'limit'
        ));
    }

    public function show($slug)
    {
        $aduan = Aduan::where('slug', $slug)->first();

        $jenisPekerjaan = [];
        $jenisBahan = [];
        $jenisBajenisAlatBanturang = [];
        $jenisTransportasi = [];

        $listPekerjaan = [];
        $listBahan = [];
        $listAlatBantu = [];
        $listTransportasi = [];
        $penunjukan = [];
        $pekerjaanUtama = [];
        $daftarPekerjaan = [];
        $daftarGalian = [];
        $daftarBahan = [];
        $daftarAlatBantu = [];
        $daftarTransportasi = [];
        $listDokumentasi = [];

        $totalPekerjaan = 0;

        $kategoriPekerjaan = Kategori::whereSlug('pekerjaan')->first();
        if ($kategoriPekerjaan) {
            $jenisPekerjaan = Jenis::where('kategori_id', $kategoriPekerjaan->id)->get()->pluck('id');
            $listPekerjaan = Item::whereIn('jenis_id', $jenisPekerjaan)->get();
        }

        $kategoriGalian = Kategori::whereSlug('galian')->first();
        if ($kategoriGalian) {
            $jenisGalian = Jenis::where('kategori_id', $kategoriGalian->id)->get()->pluck('id');
            $listGalian = Item::whereIn('jenis_id', $jenisGalian)->get();
        }

        $kategoriBahan = Kategori::whereSlug('bahan')->first();
        if ($kategoriBahan) {
            $jenisBahan = Jenis::where('kategori_id', $kategoriBahan->id)->get()->pluck('id');
            $listBahan = Item::whereIn('jenis_id', $jenisBahan)->get();
        }

        $kategoriAlatBantu = Kategori::whereSlug('alat-bantu')->first();
        if ($kategoriAlatBantu) {
            $jenisAlatBantu = Jenis::where('kategori_id', $kategoriAlatBantu->id)->get()->pluck('id');
            $listAlatBantu = Item::whereIn('jenis_id', $jenisAlatBantu)->get();
        }

        $kategoriTransportasi = Kategori::whereSlug('transportasi')->first();
        if ($kategoriTransportasi) {
            $jenisTransportasi = Jenis::where('kategori_id', $kategoriTransportasi->id)->get()->pluck('id');
            $listTransportasi = Item::whereIn('jenis_id', $jenisTransportasi)->get();
        }

        $kategoriDokumentasi = Kategori::whereSlug('dokumentasi')->first();
        if ($kategoriDokumentasi) {
            $jenisDokumentasi = Jenis::where('kategori_id', $kategoriDokumentasi->id)->get()->pluck('id');
            $listDokumentasi = Item::whereIn('jenis_id', $jenisDokumentasi)->get();
        }

        if ($aduan->status !== 'draft') {
            $penunjukan = PenunjukanPekerjaan::where('aduan_id', $aduan->id)->first();

            $query = PelaksanaanPekerjaan::where('penunjukan_pekerjaan_id', $penunjukan->id);

            $pekerjaanUtama = $query->first();

            if ($pekerjaanUtama) {

                $daftarPekerjaan = $query->with(["hasItem" => function ($q) use ($listPekerjaan) {
                    $q->whereIn('item.id', $listPekerjaan->pluck('id'));
                }])->first();

                $daftarBahan = $query->with(["hasItem" => function ($q) use ($listBahan) {
                    $q->whereIn('item.id', $listBahan->pluck('id'));
                }])->first();

                $daftarGalian = GalianPekerjaan::where('pelaksanaan_pekerjaan_id', $pekerjaanUtama->id)->get();

                $daftarAlatBantu = $query->with(["hasItem" => function ($q) use ($listAlatBantu) {
                    $q->whereIn('item.id', $listAlatBantu->pluck('id'));
                }])->first();

                $daftarTransportasi = $query->with(["hasItem" => function ($q) use ($listTransportasi) {
                    $q->whereIn('item.id', $listTransportasi->pluck('id'));
                }])->first();

                $daftarDokumentasi = $query->with(["hasItem" => function ($q) use ($listDokumentasi) {
                    $q->whereIn('item.id', $listDokumentasi->pluck('id'));
                }])->first();

                $totalPekerjaan = $daftarPekerjaan->hasItem->sum('pivot.total') + $daftarBahan->hasItem->sum('pivot.total') + $daftarAlatBantu->hasItem->sum('pivot.total') + $daftarTransportasi->hasItem->sum('pivot.total') + $daftarDokumentasi->hasItem->sum('pivot.total') + $daftarGalian->sum('pivot.total');
            }
        }

        $jenisAduan = $aduan->hasJenisAduan->toArray();
        $jenis_aduan = JenisAduan::orderBy('nama')->get();
        $rekanan = Rekanan::orderBy('nama')->get();

        $notifikasi = Notifikasi::where('modul_id', $aduan->id)->first();
        if ($notifikasi) {
            $notifikasi->status = 'baca';
            $notifikasi->save();
        }

        $title = 'Detail Aduan ' . $aduan->nomor_pekerjaan;
        $action = route('penunjukan_pekerjaan.store');

        if ($aduan == null) {
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Data Aduan tidak ditemukan')->with('Class', 'primary');
        }

        return view('penunjukan_pekerjaan.show', compact(
            'aduan',
            'penunjukan',
            'pekerjaanUtama',
            'daftarPekerjaan',
            'daftarGalian',
            'daftarBahan',
            'daftarAlatBantu',
            'daftarTransportasi',
            'daftarDokumentasi',
            'jenisAduan',
            'jenis_aduan',
            'rekanan',
            'title',
            'listPekerjaan',
            'listGalian',
            'listBahan',
            'listAlatBantu',
            'listTransportasi',
            'listDokumentasi',
            'totalPekerjaan',
            'action'
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
        $user_id = auth()->user()->id;
        $rekanan_id = $request->rekanan_id;
        $slug = $request->slug;

        $aduan = Aduan::where('slug', $slug)->first();

        $notifikasi = Notifikasi::where('modul_id', $aduan->id)->first();
        if ($notifikasi) {
            $notifikasi->status = 'baca';
            $notifikasi->save();
        }

        $dataPenunjukanPerkerjaan = PenunjukanPekerjaan::count();
        if ($dataPenunjukanPerkerjaan >= 1) {
            $no = str_pad($dataPenunjukanPerkerjaan + 1, 4, "0", STR_PAD_LEFT);
            $nomor_pekerjaan =  $no . "/" . "SPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        } else {
            $no = str_pad(1, 4, "0", STR_PAD_LEFT);
            $nomor_pekerjaan =  $no . "/" . "SPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        }

        $penunjukanPekerjaan = PenunjukanPekerjaan::where('aduan_id', $aduan->id)->first();
        if ($penunjukanPekerjaan) {
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Aduan sudah dikerjakan')->with('Class', 'danger');
        }

        $rekanan = Rekanan::find($rekanan_id);

        try {
            DB::commit();
            $data = new PenunjukanPekerjaan;
            $data->nomor_pekerjaan = $nomor_pekerjaan;
            $data->rekanan_id = $rekanan_id;
            $data->aduan_id = $aduan->id;
            $data->user_id = $user_id;
            $data->status = 'draft';
            $data->save();

            $aduan->status = 'proses';
            $aduan->save();

            $title = "Penunjukan Pekerjaan Baru";
            $body = "SPK " . $nomor_pekerjaan . " telah diterbitkan";
            $modul = "penunjukan-pekerjaan";

            $this->notification($data->id, $data->slug, $title, $body, $modul, auth()->user()->id, $rekanan->hasUser->id);

            $message = 'Berhasil Menyimpan Pelaksanaan Pekerjaan';
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Penunjukan pekerjaan berhasil ditambah')->with('Class', 'primary');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Penunjukan pekerjaan gagal ditambah')->with('Class', 'danger');
        }
    }

    public function notifikasi($id)
    {
        $notifikasi = Notifikasi::where('modul_id', $id)->where('to_user_id', auth()->user()->id)->first();
        $notifikasi->status = 'baca';
        $notifikasi->save();

        $aduan = Aduan::where('id', $id)->first();

        return redirect()->route('penunjukan_pekerjaan.show', $aduan->slug);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        $title =  "Upload Data Pekerjaan";
        $route = "$this->route";
        $action = route('penunjukan_pekerjaan.upload');

        return view('penunjukan_pekerjaan.upload', compact(
            "title",
            "route",
            "action",
        ));
    }
    /**
     * upload data
     *
     * @return \Illuminate\Http\Response
     * @param \Illuminate\Http\Request
     */
    public function uploaddata(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $dataNoTagihan = '';
        $dataTanggalTagihan = [];
        $dataCv = [];
        $dataSpk = [];
        $dataLokasi = [];
        $dataJamMulai = [];
        $dataJamSelesai = [];
        $dataTanggalMulai = [];
        $dataTanggalSelesai = [];
        $dataJenisPekerjaan = [];
        $dataNamaPekerjaan = [];
        $dataJumlahPekerjaan = [];
        $dataPanjangGalian = [];
        $dataLebarGalian = [];
        $dataDalamGalian = [];
        $dataPekerjaan = [];
        $dataAduan = [];
        $dataRekanan = [];
        $PelaksanaanPekerjaan = [];
        $listitem = [];
        $tagihan = [];

        $wilayah = Wilayah::first();

        $file = $request->hasFile('file');
        $total = 0;
        if ($file) {
            $item = Excel::toArray('', request()->file('file'), null, null);
            foreach ($item[0] as $k => $val) {
                $dataItem[$k] = $val;
            }
            foreach ($dataItem as $index => $item) {
                if ($index > 2) {

                    $dataNoTagihan = $item[1];
                    $dataTanggalTagihan[$index] = $item[2];
                    $dataCv[$index] = $item[3];
                    $dataSpk[$index] = $item[4];
                    $dataLokasi[$index] = $item[5];
                    $dataJamMulai[$index] = $item[6];
                    $dataJamSelesai[$index] = $item[7];
                    $dataTanggalMulai[$index] = $item[8];
                    $dataTanggalSelesai[$index] = $item[9];
                    $dataJenisPekerjaan[$index] = $item[10];
                    $dataNamaPekerjaan[$index] = $item[11];
                    $dataJumlahPekerjaan[$index] = (float) $item[12];
                    $dataPanjangGalian[$index] = $item[13];
                    $dataLebarGalian[$index] = $item[14];
                    $dataDalamGalian[$index] = $item[15];

                    if (
                        $dataNoTagihan[$index] != null &&
                        $dataTanggalTagihan[$index] != null &&
                        $dataCv[$index] != null &&
                        $dataSpk[$index] != null &&
                        $dataLokasi[$index] != null &&
                        $dataJamMulai[$index] != null &&
                        $dataJamSelesai[$index] != null &&
                        $dataTanggalMulai[$index] != null &&
                        $dataTanggalSelesai[$index] != null &&
                        $dataJenisPekerjaan[$index] != null &&
                        $dataNamaPekerjaan[$index] != null &&
                        $dataJumlahPekerjaan[$index] != null &&
                        $dataPanjangGalian[$index] != null &&
                        $dataLebarGalian[$index] != null &&
                        $dataDalamGalian[$index] != null
                    ) {
                        # code...
                        $dataRekanan[$index] = Rekanan::where('nama', 'like', '%' . $dataCv[$index] . '%')->first();

                        if ($dataRekanan[$index]) {
                            $dataPekerjaan[$index] = PenunjukanPekerjaan::where('nomor_pekerjaan', $dataSpk[$index])->where('rekanan_id', $dataRekanan[$index]->id)->first();

                            if (empty($dataPekerjaan[$index])) {
                                $dataAduan[$index] = new Aduan();
                                $dataAduan[$index]->no_ticket = $dataSpk[$index];
                                $dataAduan[$index]->no_aduan = $dataSpk[$index];
                                $dataAduan[$index]->no_pelanggan = $dataCv[$index];
                                $dataAduan[$index]->detail_lokasi = $dataLokasi[$index];
                                $dataAduan[$index]->no_hp = $dataRekanan[$index]->no_hp;
                                $dataAduan[$index]->mps = $dataSpk[$index];
                                $dataAduan[$index]->atas_nama = $dataRekanan[$index]->nama;
                                $dataAduan[$index]->sumber_informasi = $dataRekanan[$index]->nama;
                                $dataAduan[$index]->lokasi =  $dataLokasi[$index];
                                $dataAduan[$index]->keterangan =  "Aduan dari internal";
                                $dataAduan[$index]->lat_long = '-0.475303, 117.14647';
                                $dataAduan[$index]->status = "selesai";
                                $dataAduan[$index]->wilayah_id =  $wilayah->id;
                                $dataAduan[$index]->user_id = auth()->user()->id;
                                $dataAduan[$index]->save();

                                $dataPekerjaan[$index] = new PenunjukanPekerjaan;
                                $dataPekerjaan[$index]->nomor_pekerjaan = $dataSpk[$index];
                                $dataPekerjaan[$index]->rekanan_id = $dataRekanan[$index]->id;
                                $dataPekerjaan[$index]->aduan_id = $dataAduan[$index]->id;
                                $dataPekerjaan[$index]->tagihan = 'ya';
                                $dataPekerjaan[$index]->user_id = auth()->user()->id;
                                $dataPekerjaan[$index]->status = 'selesai';
                                $dataPekerjaan[$index]->save();
                            }

                            $PelaksanaanPekerjaan[$index] = PelaksanaanPekerjaan::where('penunjukan_pekerjaan_id', $dataPekerjaan[$index]->id)->where('rekanan_id', $dataRekanan[$index]->id)->first();

                            if (empty($PelaksanaanPekerjaan[$index])) {
                                $PelaksanaanPekerjaan[$index] = new PelaksanaanPekerjaan;
                                $PelaksanaanPekerjaan[$index]->nomor_pelaksanaan_pekerjaan =  $dataSpk[$index];
                                $PelaksanaanPekerjaan[$index]->penunjukan_pekerjaan_id = $dataPekerjaan[$index]->id;
                                $PelaksanaanPekerjaan[$index]->rekanan_id =  $dataRekanan[$index]->id;
                                $PelaksanaanPekerjaan[$index]->aduan_id = $dataPekerjaan[$index]->aduan_id;
                                $PelaksanaanPekerjaan[$index]->lokasi = $dataLokasi[$index];
                                $PelaksanaanPekerjaan[$index]->lat_long =  '-0.475303, 117.14647';
                                $PelaksanaanPekerjaan[$index]->user_id = auth()->user()->id;
                                $PelaksanaanPekerjaan[$index]->tanggal_mulai = Carbon::parse($dataTanggalMulai[$index])->format('Y-m-d');
                                $PelaksanaanPekerjaan[$index]->tanggal_selesai =  Carbon::parse($dataTanggalSelesai[$index])->format('Y-m-d');
                                $PelaksanaanPekerjaan[$index]->status = 'selesai';
                                $PelaksanaanPekerjaan[$index]->save();
                            }

                            $dataItem[$index] = Item::where('nama', 'like', '%' . $dataNamaPekerjaan[$index] . '%')->first();
                            if ($dataItem[$index]) {
                                $harga_item[$index] = $dataItem[$index]->harga;
                                if ($dataJenisPekerjaan[$index] !== 'Galian') {
                                    if ($dataJumlahPekerjaan != '') {

                                        $listitem[$dataItem[$index]->id] = [
                                            'keterangan' => '',
                                            'harga' => $harga_item[$index],
                                            'qty' => $dataJumlahPekerjaan[$index],
                                            'total' => $dataJumlahPekerjaan[$index] * $harga_item[$index],
                                        ];
                                        $PelaksanaanPekerjaan[$index]->hasItem()->sync($listitem);
                                    }
                                } else {
                                    $dataGalian[$index] = GalianPekerjaan::where('item_id', $item)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan[$index]->id)->first();

                                    if (empty($dataGalian[$index])) {
                                        $dataGalian[$index] = new GalianPekerjaan;
                                    }
                                    $dataGalian[$index]->panjang = $dataPanjangGalian[$index];
                                    $dataGalian[$index]->lebar = $dataLebarGalian[$index];
                                    $dataGalian[$index]->dalam = $dataDalamGalian[$index];
                                    $dataGalian[$index]->harga = 'siang';
                                    $dataGalian[$index]->total = 0;
                                    $dataGalian[$index]->item_id = $dataItem[$index]->id;
                                    $dataGalian[$index]->user_id = auth()->user()->id;
                                    $dataGalian[$index]->pelaksanaan_pekerjaan_id = $PelaksanaanPekerjaan[$index]->id;
                                    $dataGalian[$index]->save();
                                }
                            }
                        }
                    }
                }
            }


            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil diupload dengan total item :' . $total)->with('Class', 'success');
        }
        // try { } catch (\Throwable $th) {
        //     //throw $th;
        //     return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' gagal diupload')->with('Class', 'success');
        // }
    }
}
