<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Jenis;
use App\Models\Rekanan;
use App\Models\Satuan;
use App\Models\Tagihan;
use App\Models\TagihanItem;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Excel;
use Carbon\Carbon;
use DB;


class TagihanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'tagihan';
        $this->tambah = 'false';
        $this->index = 'tagihan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }


    public function configHeaders()
    {
        return [
            [
                'name'    => 'nomor_tagihan',
                'alias'    => 'Nomor Tagihan',
            ],
            [
                'name'    => 'rekanan',
                'alias'    => 'Nama Rekanan',
            ],
            [
                'name'    => 'tanggal',
                'alias'    => 'Tanggal',
            ],
            [
                'name'    => 'status',
                'alias'    => 'Status',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nomor_tagihan',
                'input'    => 'text',
                'alias'    => 'Nomor Tagihan',
                'value'    => null
            ],
        ];
    }
    public function configForm()
    {
        return [];
    }

    public function show($slug)
    {
        $query =  Tagihan::whereSlug($slug);
        $tagihan = $query->with(['hasPelaksanaanPekerjaan' => function ($q) {
            $q->with('hasGalianPekerjaan')->orderBy('created_at', 'asc');
        }])->first();
        $pelaksanaan = $tagihan->hasPelaksanaanPekerjaan()->pluck('id')->toArray();
        $title =  "Proses Tagihan Nomor :" . $tagihan->nomor_tagihan;

        $tagihanItem = TagihanItem::where('tagihan_id', $tagihan->id)->get();
        $action = route('tagihan.store', $tagihan->id);
        return view('tagihan.show', compact(
            'action',
            'title',
            'tagihanItem',
            'tagihan'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        $title =  "Upload Data Tagihan";
        $route = $this->route;
        $dataRekanan = Rekanan::get();
        $action = route('tagihan.uploaddata');
        $month = [];

        for ($m = 1; $m <= 12; $m++) {
            $month[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }

        return view('tagihan.upload', compact(
            "title",
            "route",
            "month",
            "dataRekanan",
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

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
            'same' => 'Password dan konfirmasi password harus sama',
        ];

        $nomor_tagihan = $request->nomor_tagihan;

        $this->validate(request(), [
            'nomor_tagihan' => 'required',
            'rekanan_id' => 'required',
            'bulan' => 'required',
        ], $messages);

        $tahun = Carbon::now()->formatLocalized("%Y");
        $tanggal = Carbon::now()->daysInMonth;
        $bulan = $request->bulan;

        $tanggal_tagihan = Carbon::parse($tahun . '-' . $bulan . "-"  . $tanggal)->format('Y-m-d');

        $rekanan_id = $request->rekanan_id;

        $dataJenisItem = Jenis::where('nama', 'LIKE', "%baru%")->first();
        $satuan = Satuan::where('nama', 'LIKE', "%pcs%")->first();

        $dataItem = [];
        $dataListItem = [];
        $ListItem = [];
        $dataJenisHarga = [];
        $itemExist = [];
        $dataJumlah = [];
        $dataHarga = [];
        $hargaItem = [];
        $dataMaster = [];
        $dataHargaMaster = [];
        $dataHargaUraian = [];

        // DB::beginTransaction();

        $file = $request->hasFile('file');
        $total_uraian = 0;
        $total_master = 0;
        if ($file) {
            $tagihan = $this->model()
                ->where('nomor_tagihan', $nomor_tagihan)
                ->where('rekanan_id', $rekanan_id)
                ->where('tanggal_tagihan', $tanggal_tagihan)
                ->first();

            if (empty($tagihan)) {
                $tagihan = new Tagihan;
            }
            $tagihan->nomor_tagihan = $nomor_tagihan;
            $tagihan->rekanan_id = $rekanan_id;
            $tagihan->tanggal_tagihan = $tanggal_tagihan;
            $tagihan->user_id = auth()->user()->id;
            $tagihan->status = 'dikirim';
            $tagihan->save();

            $item = Excel::toArray('', request()->file('file'), null, null);
            foreach ($item[0] as $k => $val) {
                $dataItem[$k] = $val;
            }
            $nomor = 0;
            $countAll = [];
            $count = 0;

            $dataItemExist = [];
            $lastquery = [];
            $nomorkey = 0;
            $urutan = 1;

            foreach ($dataItem as $index => $item) {
                if ($index > 3) {

                    $dataNama[$index] = $item[1]  == null ? '' : $item[1];
                    $dataJumlah[$index] = $item[3]  == null ? 0 : $item[3];
                    $dataHarga[$index] = $item[4] == null ? 0 : $item[4];
                    $dataJenisHarga[$index] = $item[2]  == null ? '' : $item[2];

                    $ListItem[$nomor] = [
                        'uraian' =>  $dataNama[$index],
                        // 'master' =>  $dataMaster[$index],
                        'harga_uraian' =>  $dataHarga[$index],
                        // 'harga_master' =>  $itemExist[$index]->harga,
                        'jumlah' =>  number_format($dataJumlah[$index], 2),
                        'jenis_harga' =>  $dataJenisHarga[$index]
                        // 'total_master' =>  $item[2] * $hargaItem[$index],
                    ];
                    $nomor++;
                }
            }


            $countAll = [];
            foreach ($ListItem as $key => $val) {

                $searchValues[$key] = preg_split('/\s+/', $val['uraian'], -1, PREG_SPLIT_NO_EMPTY);
                $queryRaw = '';
                $countAll[$key] = 0;

                $dataListItem[$key] = Item::query();
                if (count($searchValues[$key]) == 1) {
                    foreach ($searchValues[$key] as $j => $kword) {
                        if ($kword !== "Dan") {
                            $dataListItem[$key]->where('nama', 'like', "%{$kword}%");
                            $countAll[$key] = $countAll[$key] + 1;
                        }
                    }
                } else {
                    $nomorkey = 0;
                    foreach ($searchValues[$key] as $i => $word) {
                        if (preg_match("/^[a-zA-Z0-9]+$/", $word) == 1) {
                            if ($queryRaw) {
                                $count =  Item::whereRaw(rtrim($queryRaw, " and"))->count();
                                if ($count > 0) {
                                    $lastquery = $queryRaw;

                                    if ($word !== "Dan") {
                                        $queryRaw .= 'nama LIKE "%' . $word . '%" and ';
                                        $countAll[$key] = $countAll[$key] + 1;

                                        if (Item::whereRaw(rtrim($queryRaw, " and"))->count() == 0) {
                                            $queryRaw = $lastquery;
                                        }
                                    }
                                }
                            } else {
                                if (Item::where('nama', 'like', "%{$word}%")->count() > 0) {
                                    if ($word !== "Dan") {
                                        $countAll[$key] = $countAll[$key] + 1;
                                        $queryRaw .= 'nama LIKE "%' . $word . '%" and ';
                                    }
                                } else {
                                    // $countAll[$key] = 0;
                                    continue;
                                }
                            }

                            $nomorkey++;
                        }
                    }
                }
                // if ($countAll > 1) {
                //     $dataListItem[$key]->where('id',  "");
                // }

                $dataItemExist[$key] = rtrim($queryRaw, " and");
                if ($queryRaw) {
                    $dataListItem[$key]->whereRaw($dataItemExist[$key]);
                }

                if ($countAll[$key] == 0) {
                    $dataListItem[$key]->where('id',  "");
                }

                if ($dataListItem[$key]->first()) {
                    $itemExist[$key] =    $dataListItem[$key]->first();
                    if ($val['jenis_harga'] === 'malam') {
                        $hargaItem[$key] =  $itemExist[$key]->harga_malam;
                    } else {
                        $hargaItem[$key] =  $itemExist[$key]->harga;
                    }
                    $total_master++;
                    $dataMaster[$key] = $itemExist[$key]->nama;

                    $dataListItem[$key] = [
                        'uraian' =>  $val['uraian'],
                        'count' =>  $countAll[$key],
                        'master' =>  $itemExist[$key]->nama,
                        'harga_uraian' =>  $val['harga_uraian'],
                        'harga_master' =>  $itemExist[$key]->harga,
                        'jumlah' =>  number_format($dataJumlah[$index], 2),
                        'jenis_harga' =>  $dataJenisHarga[$index],
                        // 'total_master' =>  $item[2] * $hargaItem[$index],
                        'grand_total' =>  $item[2] *  $itemExist[$key]->harga,
                    ];
                    $total_master++;
                } else {
                    $dataListItem[$key] = [
                        'uraian' =>  $val['uraian'],
                        'count' =>  $countAll[$key],
                        'master' =>  '',
                        'harga_uraian' => $val['harga_uraian'],
                        'harga_master' =>  0,
                        'jumlah' =>  number_format($val['jumlah'], 2),
                        'jenis_harga' =>  $dataJenisHarga[$index],
                        'grand_total' =>  0,
                    ];
                    $itemExist[$key] =  new Item;
                    $itemExist[$key]->nama = $val['uraian'];
                    $itemExist[$key]->harga = 0;
                    $itemExist[$key]->harga_malam = 0;
                    $itemExist[$key]->satuan_id = $satuan->id;
                    $itemExist[$key]->jenis_id = $dataJenisItem->id;
                    $itemExist[$key]->save();
                    $dataMaster[$key] = '';
                    $total_uraian++;
                }



                $tagihanItem[$key] = TagihanItem::where('tagihan_id', $tagihan->id)->where('item_id', $itemExist[$key]->id)->where('urutan', $key + 1)->first();

                if (empty($tagihanItem[$key])) {
                    $tagihanItem[$key] = new TagihanItem;
                }

                $tagihanItem[$key]->uraian = $val['uraian'];
                $tagihanItem[$key]->master = $dataMaster[$key];
                $tagihanItem[$key]->jumlah =  $val['jumlah'];
                $tagihanItem[$key]->harga_uraian =  $val['harga_uraian'];
                $tagihanItem[$key]->harga_master = $itemExist[$key]->harga;
                $tagihanItem[$key]->total_uraian =  $val['harga_uraian'] *  $val['jumlah'];
                $tagihanItem[$key]->total_master = $itemExist[$key]->harga *  $val['jumlah'];

                if ($val['harga_uraian'] > $itemExist[$key]->harga) {
                    $tagihanItem[$key]->grand_total  =  $itemExist[$key]->harga *  $val['jumlah'];
                } else {
                    $tagihanItem[$key]->grand_total   =  $val['harga_uraian'] *  $val['jumlah'];
                }

                $tagihanItem[$key]->urutan =  $urutan;
                $tagihanItem[$key]->item_id = $itemExist[$key]->id;
                $tagihanItem[$key]->tagihan_id = $tagihan->id;
                $tagihanItem[$key]->save();

                $urutan++;
            }
            // return $dataListItem;

            return redirect()->route($this->route . '.show', $tagihan->slug)->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil diupload dengan total item dari kamus data sebanyak : ' . $total_master . ' dan data yang baru sebanyak : ' . $total_uraian)->with('Class', 'success');
        }
        try { } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' gagal diupload')->with('Class', 'success');
        }
    }

    public function model()
    {
        return new Tagihan();
    }
}
