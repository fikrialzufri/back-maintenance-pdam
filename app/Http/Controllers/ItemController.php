<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Jenis;
use App\Models\Satuan;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Excel;

class ItemController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'item';
        $this->upload = 'true';
        $this->sort = 'nama';
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
                'alias'    => 'Nama Item',
            ],
            [
                'name'    => 'satuan',
                'alias'    => 'Nama Satuan',
            ],
            [
                'name'    => 'jenis',
                'alias'    => 'Nama Jenis',
            ],
            [
                'name'    => 'aktif',
                'alias'    => 'Aktif',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Item',
                'value'    => null
            ],
            [
                'name'    => 'jenis_id',
                'input'    => 'combo',
                'alias'    => 'Jenis / Kategori',
                'value' => $this->combobox(
                    'Jenis',
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $hasRelation = 'kategori',
                    $hasColom = 'nama_kategori'
                )
            ],
            [
                'name'    => 'aktif',
                'input'    => 'combo',
                'alias'    => 'Aktif',
                'value' => ['ya', 'tidak'],
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'satuan_id',
                'input'    => 'combo',
                'alias'    => 'Satuan',
                'value' => $this->combobox(
                    'satuan',
                )
            ],
        ];
    }
    public function configForm()
    {

        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Item',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'harga',
                'input'    => 'rupiah',
                'alias'    => 'Harga Item Siang',
                'validasi'    => ['required',  'min:1'],
            ],
            [
                'name'    => 'harga_malam',
                'input'    => 'rupiah',
                'alias'    => 'Harga Item Malam',
                'validasi'    => ['required',  'min:1'],
            ],
            [
                'name'    => 'jenis_id',
                'input'    => 'combo',
                'alias'    => 'Jenis',
                'value' => $this->combobox(
                    'Jenis',
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $hasRelation = 'kategori',
                    $hasColom = 'nama_kategori'
                ),
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'satuan_id',
                'input'    => 'combo',
                'alias'    => 'Satuan',
                'value' => $this->combobox(
                    'Satuan'
                ),
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'aktif',
                'input'    => 'radio',
                'alias'    => 'Aktif',
                'value' => ['ya', 'tidak'],
                'default' => 'ya',
                'validasi'    => ['required'],
            ],
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        $title =  "Upload Data Item";
        $route = $this->route;
        $action = route('item.upload');

        return view('item.upload', compact(
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
        $title =  "Upload Data Item";
        $route = $this->route;

        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $dataItem = [];
        $dataJenis = [];
        $dataSatuan = [];
        $dataHargaSiang = [];
        $dataHargaMalam = [];

        $itemExist = [];

        try {
            $file = $request->hasFile('file');
            $total = 0;
            if ($file) {
                $item = Excel::toArray('', request()->file('file'), null, null);
                foreach ($item[0] as $k => $val) {
                    $dataItem[$k] = $val;
                }
                foreach ($dataItem as $index => $item) {
                    if ($index > 2) {
                        $dataJenis[$index] = Jenis::where('nama', 'LIKE', '%' . $item[2] . "%")->first();

                        $dataSatuan[$index] = Satuan::where('nama', 'LIKE', '%' . $item[3] . "%")->first();

                        if (!$dataJenis[$index]) {
                            return redirect()->route($this->route . '.index')->with('message', ' Jenis Item Tidak ada')->with('Class', 'danger');
                        }
                        if (!$dataSatuan[$index]) {
                            return redirect()->route($this->route . '.index')->with('message', ' Satuan Item tidak')->with('Class', 'danger');
                        }
                        $dataNama[$index] = $item[1];
                        $dataHargaSiang[$index] = isset($item[4]) ? str_replace(".", "", $item[4]) : str_replace(".", "", $item[5]);
                        $dataHargaMalam[$index] = isset($item[5]) ? str_replace(".", "", $item[5]) : str_replace(".", "", $item[4]);

                        $itemExist[$index] = Item::where('nama', 'LIKE', '%' . $dataNama[$index]  . "%")->first();
                        if ($itemExist[$index] == null) {
                            if ($dataNama[$index] != null) {
                                $itemExist[$index]  = new item;
                            }
                            $itemExist[$index]->nama =  $dataNama[$index];
                            $itemExist[$index]->jenis_id =  $dataJenis[$index]->id;
                            $itemExist[$index]->satuan_id =  $dataSatuan[$index]->id;
                            $itemExist[$index]->harga =  isset($dataHargaSiang[$index]) ? $dataHargaSiang[$index] : $dataHargaMalam[$index];
                            $itemExist[$index]->harga_malam =  isset($dataHargaMalam[$index]) ? $dataHargaMalam[$index] : $dataHargaSiang[$index];
                            $itemExist[$index]->save();
                            $total = ++$index;
                        }
                    }
                }
                return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil diupload dengan total item : ' . $total)->with('Class', 'success');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' gagal diupload')->with('Class', 'success');
        }
    }

    public function getdetail(Request $request)
    {
        // return $request;
        $karyawan = null;
        try {
            $id = $request->item;
            $item = $this->model()->find($id);

            if (auth()->user()->hasRole('asisten-manajer-pengawas')) {
                $karyawan = "Asisten Manajer Pengawas";
            }
            if (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
                $karyawan = "Perencanaan";
            }
            if (auth()->user()->hasRole('staf-pengawas')) {
                $karyawan = "Pengawas";
            }
            $result = [
                'id' => $item->id,
                'nama' => $item->nama,
                'harga' => $item->harga,
                'harga_malam' => $item->harga_malam,
                'pekerjaan' => $item->pekerjaan,
                'karyawan' =>  $karyawan,
                'jenis' => $item->jenis,
            ];
            $message = 'Data Item ada';
            return $this->sendResponse($result, $message, 200);
        } catch (\Throwable $th) {
            $message = 'Data tidak Item ada';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }

    public function model()
    {
        return new Item();
    }
}
