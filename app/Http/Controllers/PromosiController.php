<?php

namespace App\Http\Controllers;

use App\Models\KategoriHargaJual;
use App\Models\Produk;
use App\Models\Promosi;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Auth;

class PromosiController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'promosi';
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
                'alias'    => 'Nama Promosi',
            ],
            [
                'name'    => 'mulai',
                'input'    => 'date',
                'alias'    => 'mulai',
            ],
            [
                'name'    => 'selesai',
                'input'    => 'date',
                'alias'    => 'selesai',
            ],
            [
                'name'    => 'produk',
                'alias'    => 'Type Diskon',
            ],
            [
                'name'    => 'nota',
                'alias'    => 'Jumlah Nota',
            ],
            [
                'name'    => 'diskon_tampil',
                'alias'    => 'Diskon',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Promosi',
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
                'alias'    => 'Nama Promosi',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'jenis_diskon',
                'input'    => 'radio',
                'alias'    => 'Jenis Diskon',
                'value'    => ['nota', 'produk'],
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'nota',
                'input'    => 'number',
                'alias'    => 'Jumlah Nota',
                'validasi'    => ['unique'],
            ],
            [
                'name'    => 'mulai',
                'input'    => 'date',
                'alias'    => 'Tanggal Mulai',
            ],
            [
                'name'    => 'selesai',
                'input'    => 'date',
                'alias'    => 'Tanggal Selesai',
            ],
            [
                'name'    => 'keterangan',
                'input'    => 'textarea',
                'alias'    => 'Keterangan / Syarat dan Ketentuan Berlaku',
            ],
            [
                'name'    => 'type_diskon',
                'input'    => 'radio',
                'alias'    => 'Type Diskon',
                'value'    => ['persen', 'nominal'],
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'diskon',
                'input'    => 'number',
                'alias'    => 'Diskon',
            ],
            [
                'name'    => 'produk',
                'input'    => 'radio',
                'alias'    => 'Produk',
                'value'    => ['produk', 'semua'],
                'validasi'    => ['required'],
            ],

        ];
    }

    public function model()
    {
        return new Promosi();
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
        $this->combobox(
            'Jenis',

        );
        $form = $this->configform();

        $count = count($form);
        $dataProduk = Produk::where('inc_jual', 'ya')->get();

        $colomField = $this->colomField($count);

        $countColom = $this->countColom($count);
        $countColomFooter = $this->countColomFooter($count);

        $dataKategoriHargaJual = KategoriHargaJual::get();
        return view('promosi.form', compact(
            'title',
            'form',
            'countColom',
            'colomField',
            'countColomFooter',
            'dataProduk',
            'store',
            'dataKategoriHargaJual',
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
        //get dari post form
        $getRequest = $this->getRequest($request);
        // return $request;
        $validation = $getRequest['validasi'];
        $messages = $getRequest['messages'];
        //validasi
        $this->validate(
            $request,
            $validation,
            $messages
        );

        $type_diskon = $request->type_diskon;
        $jenis_diskon = $request->jenis_diskon;
        $nota = $request->nota;
        $produk = $request->produk;
        $diskon = 0;
        if ($produk == 'semua') {
            $diskonReplaceSemua = str_replace(".", "", $request->diskon);
            $diskonReqSemua = preg_replace("/[^0-9]/", "", $diskonReplaceSemua);
            $diskon = $diskonReqSemua;
        } else {
            $diskon = 0;
        }

        $data = $this->model();
        $data->nama = $request->nama;
        $data->mulai = $request->mulai;
        $data->selesai = $request->selesai;
        $data->jenis_diskon = $jenis_diskon;
        $data->type_diskon = $type_diskon;
        $data->nota = $nota;
        $data->diskon = $diskon;
        $data->produk = $produk;
        $data->user_id = Auth::user()->id;
        $data->save();

        if (isset($request->iditem)) {
            $type_diskon_produk = str_replace(".", "", $request->type_diskon_produk);
            $diskonReplace = str_replace(".", "", $request->diskon_produk);
            $diskonReq = preg_replace("/[^0-9]/", "", $diskonReplace);
            $item = [];
            $diskon = [];
            $listitem = [];
            foreach ($diskonReq as $index => $value) {
                $item[$index] = $request->iditem[$index];
                $diskon[$index] = $value;
                $listitem[$index] = [
                    'type_diskon' => $type_diskon_produk[$index],
                    'diskon' => $value
                ];
            }
            $syncData  = array_combine($item, $listitem);

            $data->hasProduk()->sync($syncData);
        }

        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil Ditambahkan')->with('Class', 'success');
    }

    public function edit($id)
    {
        $data = $this->model()->find($id);
        if (!isset($this->title)) {
            $title =  "Ubah " . ucwords(str_replace('-', ' ', $this->route)) . ' - : ' . $data->nama;
        } else {
            $title =  "Ubah " . ucwords(str_replace('-', ' ', $this->title)) . ' - : ' . $data->nama;
        }

        $produk = $data->hasProduk()->get();

        if (isset($this->manyToMany)) {
            if (isset($this->extraFrom)) {
                if (isset($this->relations)) {
                    foreach ($this->relations as $item) {
                        $hasRalation = 'has' . ucfirst($item);
                        foreach ($this->manyToMany as  $value) {
                            try {
                                $field = $value . '_id';
                                $valueField = $data->$hasRalation->$value()->first()->id;
                                $data->$field = $valueField;
                            } catch (\Throwable $th) {
                                try {
                                    $field = $value . '_id';
                                    $valueField = $data->$hasRalation()->$value()->first()->id;
                                    $data->$field = $valueField;
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            }
                        }
                    }
                }
            }
        }

        //nama route dan action route
        $route =  $this->route;
        $store =  "update";

        $form = $this->configform();
        $count = count($form);

        $colomField = $this->colomField($count);

        $countColom = $this->countColom($count);
        $countColomFooter = $this->countColomFooter($count);
        $dataProduk = Produk::get();

        return view('promosi.form', compact(
            'route',
            'store',
            'colomField',
            'countColom',
            'produk',
            'dataProduk',
            'countColomFooter',
            'title',
            'form',
            'data'
        ));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //open model
        $data = $this->model()->find($id);
        //get dari post form
        $relationId = [];

        //check extra form
        if ($this->extraFrom) {
            foreach ($this->extraFrom as $key => $item) {
                $fileId = $item . '_id';
                $relationId[$fileId] = $data->$fileId;
            }
        }
        // return $request;
        $getRequest = $this->getRequest($request, $id, $relationId);
        $messages = $getRequest['messages'];
        $relation = $getRequest['relation'];
        $validation = $getRequest['validasi'];
        $form = $getRequest['form'];


        //validasi
        $this->validate(
            $request,
            $validation,
            $messages
        );

        $type_diskon = $request->type_diskon;
        $jenis_diskon = $request->jenis_diskon;
        $nota = $request->nota;
        $produk = $request->produk;
        $diskon = $request->diskon;
        $keterangan = $request->keterangan;
        if ($produk == 'semua') {
            $diskonReplaceSemua = str_replace(".", "", $request->diskon);
            $diskonReqSemua = preg_replace("/[^0-9]/", "", $diskonReplaceSemua);
            $diskon = $diskonReqSemua;
        } else {
            $diskon = 0;
        }

        $data->nama = $request->nama;
        $data->mulai = $request->mulai;
        $data->selesai = $request->selesai;
        $data->type_diskon = $type_diskon;
        $data->jenis_diskon = $jenis_diskon;
        $data->keterangan = $keterangan;
        $data->nota = $nota;
        $data->diskon = $diskon;
        $data->produk = $produk;
        $data->user_id = Auth::user()->id;
        $data->save();

        $type_diskon_produk = str_replace(".", "", $request->type_diskon_produk);
        $diskonReplace = str_replace(".", "", $request->diskon_produk);
        $diskonReq = preg_replace("/[^0-9]/", "", $diskonReplace);

        $item = [];
        $diskon = [];
        $listitem = [];
        if (isset($request->iditem)) {

            foreach ($diskonReq as $index => $value) {
                $item[$index] = $request->iditem[$index];
                $diskon[$index] = $value;
                $listitem[$index] = [
                    'type_diskon' => $type_diskon_produk[$index],
                    'diskon' => $value
                ];
            }
            $syncData  = array_combine($item, $listitem);

            $data->hasProduk()->sync($syncData);
        }

        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil diubah')->with('Class', 'primary');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dinamis
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->model()->find($id);
        $data->delete();

        $data->hasProduk()->detach();
        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil dihapus')->with('Class', 'danger');
    }
}
