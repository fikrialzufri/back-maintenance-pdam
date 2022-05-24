<?php

namespace App\Http\Controllers;

use App\Exports\ExportTagihan;
use App\Models\Item;
use App\Models\Jenis;
use App\Models\PelaksanaanPekerjaan;
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
            [
                'name'    => 'created_at',
                'input'    => 'daterange',
                'alias'    => 'Tanggal',
                'value'    => null
            ],
        ];
    }
    public function configForm()
    {
        return [];
    }

    public function index()
    {
        //nama title
        if (!isset($this->title)) {
            $title =  ucwords($this->route);
        } else {
            $title =  ucwords($this->title);
        }

        //nama route
        $route =  $this->route;

        //nama relation
        $relations =  $this->relations;

        //nama jumlah pagination
        $paginate =  $this->paginate;

        //declare nilai serch pertama
        $search = null;

        //memanggil configHeaders
        $configHeaders = $this->configHeaders();

        //memangil model peratama
        $query = $this->model()::query();

        //button
        $button = null;

        //tambah data
        $tambah = $this->tambah;

        //tambah data
        $upload = $this->upload;

        $export = null;

        if ($this->configButton()) {
            $button = $this->configButton();
        }
        //mulai pencarian --------------------------------
        $searches = $this->configSearch();
        $searchValues = [];
        $n = 0;
        $countAll = 0;
        $queryArray = [];

        $queryRaw = '';

        foreach ($searches as $key => $val) {
            $search[$key] = request()->input($val['name']);
            $hasilSearch[$val['name']] = $search[$key];

            if ($search[$key]) {
                if ($val['input'] != 'daterange') {
                    # code...
                    $searchValues[$key] = preg_split('/\s+/', $search[$key], -1, PREG_SPLIT_NO_EMPTY);

                    if (count($searchValues[$key]) == 1) {
                        foreach ($searchValues[$key] as $index => $value) {
                            $query->where($val['name'], 'like', "%{$value}%");
                            $countAll = $countAll + 1;
                        }
                    } else {
                        $lastquery = '';

                        foreach ($searchValues[$key] as $index => $word) {
                            if (preg_match("/^[a-zA-Z0-9]+$/", $word) == 1) {

                                if ($queryRaw) {
                                    $count =  $this->model()->whereRaw(rtrim($queryRaw, " and"))->count();
                                    if ($count > 0) {
                                        $countAll = $countAll + 1;
                                        $lastquery = $queryRaw;

                                        $queryRaw .= $val['name'] . ' LIKE "%' . $word . '%" and ';
                                        if ($this->model()->whereRaw(rtrim($queryRaw, " and"))->count() == 0) {
                                            $queryRaw = $lastquery;
                                        }
                                    }
                                } else {
                                    $count =  $this->model()->where($val['name'], 'like', "%{$word}%")->count();
                                    if ($count > 0) {
                                        $countAll = $countAll + 1;

                                        $queryRaw .= $val['name'] . ' LIKE "%' . $word . '%" and ';
                                        continue;
                                    }
                                }
                            }
                        }
                    }

                    if ($queryRaw) {
                        $query->whereRaw(rtrim($queryRaw, " and "));
                    }
                    if (count($queryArray) > 0) {
                        $query->where($queryArray);
                    }
                } else {
                    $date = explode(' - ', request()->input($val['name']));
                    $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
                    $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
                    $query = $query->whereBetween(DB::raw('DATE(' . $val['name'] . ')'), array($start, $end));

                    $export .= 'from=' . $start . '&to=' . $end;
                    $countAll = $countAll + 1;
                }

                if ($countAll == 0) {
                    $query->where('id',  "");
                }
            }
            $export .= $val['name'] . '=' . $search[$key] . '&';
        }

        // return $ayam;

        //akhir pencarian --------------------------------
        // relatio
        // sort by
        if ($this->user) {
            if (!Auth::user()->hasRole('superadmin') && !Auth::user()->hasRole('admin')) {
                $query->where('user_id', Auth::user()->id);
            }
        }
        if ($this->sort) {
            if ($this->desc) {
                $data = $query->orderBy($this->sort, $this->desc);
            } else {
                $data = $query->orderBy($this->sort);
            }
        }

        if (!auth()->user()->hasRole('superadmin')) {
            if (!auth()->user()->hasRole('rekanan')) {
                $list_rekanan_id = auth()->user()->karyawan->hasRekanan->pluck('id');
                if ($list_rekanan_id) {
                    $query->whereIn('rekanan_id', $list_rekanan_id);
                } else {
                    $query->where('wilayah_id', auth()->user()->karyawan->id_wilayah);
                }
            } else {
                $rekanan_id = auth()->user()->id_rekanan;
                $query->where('rekanan_id', $rekanan_id);
            }
        }
        //mendapilkan data model setelah query pencarian
        if ($paginate) {
            $data = $query->paginate($paginate);
        } else {
            $data = $query->get();
        }

        // return $button;
        $template = 'template.index';
        if ($this->index) {
            $template = $this->index . '.index';
        }
        // return  $export;

        return view($template,  compact(
            "title",
            "data",
            'searches',
            'hasilSearch',
            'button',
            'tambah',
            'upload',
            'search',
            'export',
            'configHeaders',
            'route'
        ));
    }

    public function show($slug)
    {
        $query =  Tagihan::whereSlug($slug);

        if (!auth()->user()->hasRole('superadmin')) {
            if (!auth()->user()->hasRole('rekanan')) {
                $list_rekanan_id = auth()->user()->karyawan->hasRekanan->pluck('id');
                if ($list_rekanan_id) {
                    $query->whereIn('rekanan_id', $list_rekanan_id);
                } else {
                    $query->where('wilayah_id', auth()->user()->karyawan->id_wilayah);
                }
            } else {
                $rekanan_id = auth()->user()->id_rekanan;
                $query->where('rekanan_id', $rekanan_id);
            }
        }

        $tagihan = $query->with(['hasPelaksanaanPekerjaan' => function ($q) {
            $q->with('hasGalianPekerjaan')->orderBy('created_at', 'asc');
        }])->first();
        $pelaksanaan = $tagihan->hasPelaksanaanPekerjaan()->pluck('id')->toArray();
        $title =  "Proses Tagihan Nomor :" . $tagihan->nomor_tagihan;
        $filename =  "Tagihan Nomor :" . $tagihan->nomor_tagihan;

        $tagihanItem = TagihanItem::where('tagihan_id', $tagihan->id)->orderBy('urutan')->get();
        $action = route('tagihan.store', $tagihan->id);
        $dataitem = Item::all();

        $total = $tagihanItem->sum('grand_total');

        return view('tagihan.show', compact(
            'action',
            'title',
            'dataitem',
            'total',
            'filename',
            'tagihanItem',
            'tagihan'
        ));
    }

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

        $rekanan_id = auth()->user()->id_rekanan;

        $query =  PelaksanaanPekerjaan::query();
        if (!auth()->user()->hasRole('superadmin')) {
            if (!auth()->user()->hasRole('rekanan')) {
                $list_rekanan_id = auth()->user()->karyawan->hasRekanan->pluck('id');
                if ($list_rekanan_id) {
                    $query->whereIn('rekanan_id', $list_rekanan_id);
                } else {
                    $query->where('wilayah_id', auth()->user()->karyawan->id_wilayah);
                }
            } else {
                $rekanan_id = auth()->user()->id_rekanan;
                $query->where('rekanan_id', $rekanan_id);
            }
        }

        // $hasValue = $this->hasValue;
        $start = Carbon::now()->subMonths(2)->startOfMonth()->format('Y-m-d') . ' 00:00:01';
        $end =  Carbon::now()->endOfMonth()->format('Y-m-d') . ' 23:59:59';
        $query->whereBetween(DB::raw('DATE(tanggal_selesai)'), array($start, $end));
        $penunjukan =  $query->get();

        return view('tagihan.form', compact(
            'title',
            'form',
            'countColom',
            'colomField',
            'penunjukan',
            'countColomFooter',
            'store',
            'route'
            // 'hasValue'
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

        DB::beginTransaction();
        try {
            //open model
            DB::commit();
            // $hasRalation;
            //redirect
            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil Ditambahkan')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' gagal Ditambahkan')->with('Class', 'success');
        }
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
        $total_lokasi = $request->total_lokasi;
        $rekanan_id = $request->rekanan_id;

        $this->validate(request(), [
            'nomor_tagihan' => 'required', 'unique:tagihan,nomor_tagihan,' . $nomor_tagihan . ',NULL,id,rekanan_id,' . $rekanan_id,
            'nomor_tagihan' => 'required',
            'rekanan_id' => 'required',
            'bulan' => 'required',
        ], $messages);

        $tahun = Carbon::now()->formatLocalized("%Y");
        $tanggal = Carbon::now()->daysInMonth;
        $bulan = $request->bulan;

        $tanggal_tagihan = Carbon::parse($tahun . '-' . $bulan . "-"  . $tanggal)->format('Y-m-d');



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
            $tagihan->total_lokasi = $total_lokasi;
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

                    $dataNoPekerjaan[$index] = $item[1]  == null ? '' : $item[1];
                    $dataNama[$index] = $item[2]  == null ? '' : $item[2];
                    $dataJenisHarga[$index] = $item[3];
                    $dataJumlah[$index] = $item[4]  == null ? 0 : $item[4];
                    $dataHarga[$index] = $item[5] == null ? 0 : $item[5];

                    $ListItem[$nomor] = [
                        'no_pekerjaan' =>  $dataNoPekerjaan[$index],
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

            // return $ListItem;

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
                        'jumlah' =>   $val['jumlah'],
                        'jenis_harga' =>  $val['jenis_harga'],
                        // 'total_master' =>  $item[2] * $hargaItem[$index],
                        'grand_total' =>  $itemExist[$key]->harga * $val['jumlah'],
                    ];
                    $total_master++;
                } else {
                    $dataListItem[$key] = [
                        'uraian' =>  $val['uraian'],
                        'count' =>  $countAll[$key],
                        'master' =>  '',
                        'harga_uraian' => $val['harga_uraian'],
                        'harga_master' =>  0,
                        'jumlah' =>   $val['jumlah'],
                        'jenis_harga' =>  $val['jenis_harga'],
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
                $tagihanItem[$key]->no_pekerjaan = $val['no_pekerjaan'];
                $tagihanItem[$key]->master = $dataMaster[$key];
                $tagihanItem[$key]->jumlah =  $val['jumlah'];
                $tagihanItem[$key]->harga_uraian =  $val['harga_uraian'];
                $tagihanItem[$key]->harga_master = $itemExist[$key]->harga;
                $tagihanItem[$key]->total_uraian =  $val['harga_uraian'] *  $val['jumlah'];
                $tagihanItem[$key]->total_master = $itemExist[$key]->harga *  $val['jumlah'];
                $tagihanItem[$key]->jenis_harga = 'malam';

                if ($val['jenis_harga'] === 'malam') { } else {
                    $tagihanItem[$key]->jenis_harga = 'siang';
                }

                if ($val['harga_uraian'] >= $itemExist[$key]->harga) {
                    $tagihanItem[$key]->grand_total  =  $itemExist[$key]->harga *  $val['jumlah'];
                } elseif ($val['harga_uraian'] <= $itemExist[$key]->harga) {
                    $tagihanItem[$key]->grand_total  =  $val['harga_uraian'] *  $val['jumlah'];
                }

                if ($val['harga_uraian'] != $itemExist[$key]->harga) {
                    $tagihanItem[$key]->selisih  = 'ya';
                    if ($val['harga_uraian'] >= $itemExist[$key]->harga) {
                        $tagihanItem[$key]->grand_total_adjust  =  $itemExist[$key]->harga *  $val['jumlah'];
                        $tagihanItem[$key]->total_adjust  = $itemExist[$key]->harga;
                    } elseif ($val['harga_uraian'] <= $itemExist[$key]->harga) {
                        $tagihanItem[$key]->grand_total_adjust  =  $val['harga_uraian'] *  $val['jumlah'];
                        $tagihanItem[$key]->total_adjust  = $val['harga_uraian'];
                    }
                } else {
                    $tagihanItem[$key]->selisih  = 'tidak';
                    if ($val['harga_uraian'] >= $itemExist[$key]->harga) {
                        $tagihanItem[$key]->grand_total_adjust  =  $itemExist[$key]->harga *  $val['jumlah'];
                        $tagihanItem[$key]->total_adjust  = $itemExist[$key]->harga;
                    } elseif ($val['harga_uraian'] <= $itemExist[$key]->harga) {
                        $tagihanItem[$key]->grand_total_adjust  =  $val['harga_uraian'] *  $val['jumlah'];
                        $tagihanItem[$key]->total_adjust  = $val['harga_uraian'];
                    }
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

    public function adjust(Request $request)
    {
        $id = $request->id;
        $harga = $request->harga;
        $jumlah = $request->jumlah;

        $grand_total = $jumlah * str_replace(".", "", $harga);
        $item_id = $request->item_id;

        $itemData = Item::find($item_id);
        // return $id;
        try {
            $query = TagihanItem::find($id);
            $query->total_adjust = str_replace(".", "", $harga);
            $query->tanggal_adjust =  date('Y-m-d H:i:s');
            $query->selisih =  'tidak';
            $query->item_id =  $item_id;
            $query->grand_total =  $grand_total;
            $query->master =    $itemData->nama;
            $query->save();

            $result = [
                'tanggal' => $query->tanggal_adjust_indo,
                'id' => $query->id,
                'grand_total' => $query->grand_total,
            ];
            $message = 'Data Tagihan berhasil diubah';
            return $this->sendResponse($result, $message, 200);
        } catch (\Throwable $th) {
            $message = 'Data tidak Tagihan ada';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }

    public function exxceltagihan()
    {
        $id = request()->get('id') ?: "";
        $tagihan = Tagihan::find($id);
        return Excel::download(new ExportTagihan($id), 'Export Tagihan ' . $tagihan->nomor_tagihan . ' - Rekanan ' . $tagihan->rekanan . '.xlsx');
    }

    public function wordtagihan()
    {
        $id = request()->get('id') ?: "";
        $tagihan = Tagihan::find($id);
        $total_tagihan = TagihanItem::where('tagihan_id', $tagihan->id)->sum('grand_total');
        $filename =  "Tagihan Rekenan " . $tagihan->rekanan . " Nomor " . $tagihan->nomor_tagihan;
        $title =  "Priview Tagihan : " . $tagihan->nomor_tagihan;
        $bulan = bulan_indonesia(Carbon::parse($tagihan->tanggal_adjust));
        $tanggal = tanggal_indonesia(Carbon::parse($tagihan->tanggal_adjust));
        $now = tanggal_indonesia(Carbon::now(), false);
        return view('tagihan.word', compact(
            "title",
            "total_tagihan",
            "filename",
            "bulan",
            "now",
            "tanggal",
            "tagihan"
        ));
    }


    public function model()
    {
        return new Tagihan();
    }
}
