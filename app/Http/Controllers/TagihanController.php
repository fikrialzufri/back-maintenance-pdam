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
            $countAll = 0;
            $count = 0;

            $queryArray = [];
            $dataTotalHargaIaran = [];

            foreach ($dataItem as $index => $item) {
                if ($index > 3) {

                    $dataNama[$index] = $item[1]  == null ? '' : $item[1];
                    $dataJumlah[$index] = $item[3]  == null ? 0 : $item[3];
                    $dataJenisHarga[$index] = $item[2]  == null ? '' : $item[2];

                    $searchValues[$index] = preg_split('/\s+/', $dataNama[$index], -1, PREG_SPLIT_NO_EMPTY);

                    $dataListItem[$nomor] = Item::query();
                    if (count($searchValues[$index]) == 1) {
                        foreach ($searchValues[$index] as $index => $kword) {
                            $dataListItem[$nomor]->where('nama', 'like', "%{$kword}%");
                            // return 1;
                        }
                    } else {
                        foreach ($searchValues[$index] as $i => $word) {
                            if (preg_match("/^[a-zA-Z0-9]+$/", $word) == 1) {

                                if (Item::where('nama', 'like', "%{$word}%")->count() > 1) {
                                    $queryArray[$nomor] = $dataListItem[$nomor]->where('nama', 'like', "%{$word}%");
                                    continue;
                                }
                            }
                        }
                    }

                    $dataListItem[$nomor] =  $dataListItem[$nomor]->first();

                    if ($dataListItem[$nomor]) {
                        # code...
                        if (isset($item[2]) && $item[2]  == 'malam') {
                            $dataHarga[$nomor] = $dataListItem[$nomor]->harga;
                            $dataTotalHargaMaster[$nomor] = $dataListItem[$nomor]->harga * $item[3]  == null ? 0 : number_format($item[3]);
                        } else {
                            $dataHarga[$nomor] = $dataListItem[$nomor]->harga_malam;

                            $dataTotalHargaMaster[$nomor] = $dataListItem[$nomor]->harga_malam * number_format($item[3]);
                        }

                        $dataTotalHargaIura[$nomor] =  $item[3]  == null ? 0 : number_format($item[3]) * $item[4];

                        if ($dataTotalHargaIura[$nomor] > $dataTotalHargaMaster[$nomor]) {
                            $dataGrandTotal[$nomor] = $dataTotalHargaMaster[$nomor];
                        } else {
                            $dataGrandTotal[$nomor] = $dataTotalHargaIura[$nomor];
                        }

                        $ListItem[$nomor] = [
                            'uraian' =>  $item[1],
                            'master' =>   $dataListItem[$nomor]->nama,
                            'harga_uraian' =>  $item[4] == null ? 0 : $item[4],
                            'harga_master' =>  $dataHarga[$nomor],
                            'jumlah' =>   $item[3]  == null ? 0 : number_format($item[3]),
                            'total_uraian' => $dataTotalHargaIura[$nomor],
                            'total_master' =>   $dataTotalHargaMaster[$nomor],
                            'grand_total' =>    $dataGrandTotal[$nomor],
                        ];
                        $total_master++;
                    } else {
                        $ListItem[$nomor] = [
                            'uraian' =>  $item[1],
                            'master' =>   '',
                            'harga_uraian' =>  $item[4] == null ? 0 : $item[4],
                            'harga_master' =>  0,
                            'jumlah' =>   $item[3]  == null ? 0 : number_format($item[3]),
                            'total_uraian' =>  $item[3]  == null ? 0 : number_format($item[3]) * $item[4],
                            'total_master' =>  0,
                            'grand_total' =>   0,
                        ];

                        $total_uraian++;
                    }

                    // if ($dataListItem[$nomor]) {
                    //     # code...
                    //     $ListItem[$nomor] = [
                    //         'uraian' => $item[1],
                    //         'master' =>  $dataListItem[$nomor]->nama,
                    //         'harga_uraian' =>  $dataHarga[$index],
                    //         // 'harga_master' =>  $dataListItem[$index],
                    //         'jumlah' =>  number_format($dataJumlah[$index], 2),
                    //         'jenis_harga' =>  $dataJenisHarga[$index],
                    //         // 'total_master' =>  $item[2] * $hargaItem[$index],
                    //     ];
                    // } else {
                    //     $ListItem[$nomor] = [
                    //         'uraian' =>  $dataNama[$nomor],
                    //         'master' =>  "",
                    //         'harga_uraian' =>  $dataHarga[$nomor],
                    //         // 'harga_master' =>  $dataListItem[$index],
                    //         'jumlah' =>  number_format($dataJumlah[$index], 2),
                    //         'jenis_harga' =>  $dataJenisHarga[$index],
                    //         // 'total_master' =>  $item[2] * $hargaItem[$index],
                    //     ];
                    // }

                    $nomor++;
                }
            }

            // foreach ($ListItem as $l => $list) {
            //     $tagihanItem[$k] = TagihanItem::where('tagihan_id', $tagihan->id)->where('item_id', $itemExist[$k]->id)->where('urutan', $k + 1)->first();

            //     if (empty($tagihanItem[$k])) {
            //         $tagihanItem[$k] = new TagihanItem;
            //     }

            //     $tagihanItem[$k]->uraian = $value['uraian'];
            //     $tagihanItem[$k]->master = $dataMaster[$k];
            //     $tagihanItem[$k]->jumlah =  $value['jumlah'];
            //     $tagihanItem[$k]->harga_uraian =  $value['harga_uraian'];
            //     $tagihanItem[$k]->harga_master = $itemExist[$k]->harga;
            //     $tagihanItem[$k]->total_uraian =  $value['harga_uraian'] *
            //         $tagihanItem[$k]->urutan = $k + 1;
            //     $tagihanItem[$k]->item_id = $itemExist[$k]->id;
            //     $tagihanItem[$k]->tagihan_id = $tagihan->id;
            //     $tagihanItem[$k]->save();
            //     # code...
            // }
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
