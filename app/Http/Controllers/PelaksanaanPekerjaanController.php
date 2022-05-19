<?php

namespace App\Http\Controllers;

use App\Models\GalianPekerjaan;
use App\Models\Item;
use App\Models\PelaksanaanPekerjaan;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;

class PelaksanaanPekerjaanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->tambah = 'false';
        $this->route = 'pelaksanaan-pekerjaan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'no_spk',
                'alias'    => 'Nomor SPK',
            ],
            // [
            //     'name'    => 'nomor_pelaksanaan_pekerjaan',
            //     'alias'    => 'Nomor Pekerjaan',
            // ],
            [
                'name'    => 'rekanan',
                'alias'    => 'Nama Rekanana',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nomor_pelaksanaan_pekerjaan',
                'input'    => 'text',
                'alias'    => 'Nama Divisi',
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
                'alias'    => 'Nama Divisi',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'departemen_id',
                'input'    => 'combo',
                'alias'    => 'Departemen',
                'value' => $this->combobox('Departemen'),
                'validasi'    => ['required'],
            ],
        ];
    }

    public function model()
    {
        return new PelaksanaanPekerjaan();
    }

    public function item(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
        ];

        $this->validate(request(), [
            'panjang' => 'required|string|min:1',
            'lebar' => 'required|string|min:1',
            'dalam' => 'required|string|min:1',
            'item' => 'required',
        ], $messages);

        $message = 'Data Pekerjaan';
        $id = $request->id;
        $panjang = $request->panjang;
        $lebar = $request->lebar;
        $dalam = $request->dalam;
        $item = $request->item;
        $harga = $request->harga;
        $keterangan = $request->keterangan;
        $user_id = auth()->user()->id;

        $dataItem = Item::find($item);
        $harga_item = $dataItem->harga;

        if ($harga === 'malam') {
            $harga_item = $dataItem->harga_malam;
        }

        $total = ($panjang * $lebar * $dalam) * $harga_item;
        $item_id = $dataItem->id;

        $data = $this->model()->find($id);

        try {
            if ($data) {
                # code...
                $dataGalian = GalianPekerjaan::where('item_id', $item_id)->first();

                if (empty($dataGalian)) {
                    $dataGalian = new GalianPekerjaan;
                }
                $dataGalian->panjang = $panjang;
                $dataGalian->lebar = $lebar;
                $dataGalian->dalam = $dalam;
                $dataGalian->keterangan = $keterangan;
                $dataGalian->harga = $harga;
                $dataGalian->total = $total;
                $dataGalian->item_id = $item_id;
                $dataGalian->user_id = $user_id;
                $dataGalian->pelaksanaan_pekerjaan_id = $data->id;
                $dataGalian->save();

                $result = [
                    'panjang' => $dataGalian->panjang,
                    'lebar' => $dataGalian->lebar,
                    'dalam' => $dataGalian->dalam,
                    'total' => format_uang($dataGalian->total),
                    'keterangan' => $dataGalian->keterangan,
                    'pekerjaan' => $dataGalian->pekerjaan,
                    'item_id' => $dataGalian->item_id,
                ];
                return $this->sendResponse($result, $message, 200);
            }
        } catch (\Throwable $th) {
            $message = 'Detail Jenis Aduan';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }
}
