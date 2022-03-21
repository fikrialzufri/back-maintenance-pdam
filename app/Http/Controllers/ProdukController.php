<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\KategoriHargaJual;
use App\Models\Produk;
use App\Models\Promosi;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProdukController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'produk';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'nama',
                'alias'    => 'Nama Produk',
            ],
            [
                'name'    => 'harga_beli',
                'input'    => 'rupiah',
                'alias'    => 'Harga Modal (HPP)',
            ],
            [
                'name'    => 'harga_jual',
                'input'    => 'rupiah',
                'alias'    => 'Harga Jual',
            ],
            [
                'name'    => 'stok',
                'alias'    => 'Stok',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Produk',
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
                'alias'    => 'Nama Produk',
                'validasi'    => ['required', 'unique', 'min:1'],
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
                    $hasColom = 'nama_kategori',
                ),
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'inc_stok',
                'alias'    => 'Mengurangi Stok',
                'input'    => 'radio',
                'value' => ['ya', 'tidak'],
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'stok',
                'alias'    => 'Stok',
                'input'    => 'number',
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
                'name'    => 'inc_bahan',
                'alias'    => 'Bahan Baku',
                'input'    => 'radio',
                'value' => ['ya', 'tidak'],
                'validasi'    => ['required'],
            ],

            [
                'name'    => 'karyawan',
                'alias'    => 'Tambah Karyawan',
                'input'    => 'radio',
                'value' => ['ya', 'tidak'],
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'inc_jual',
                'alias'    => 'Bisa Jual',
                'input'    => 'radio',
                'value' => ['ya', 'tidak'],
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'harga_beli',
                'input'    => 'rupiah',
                'alias'    => 'Harga Modal (HPP)',
                'value'    => 0
            ],
            [
                'name'    => 'komisi',
                'input'    => 'rupiah',
                'alias'    => 'Komisi',
                'value'    => 0
            ],

        ];
    }

    public function model()
    {
        return new Produk();
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

        $colomField = $this->colomField($count);

        $countColom = $this->countColom($count);
        $countColomFooter = $this->countColomFooter($count);

        $dataKategoriHargaJual = KategoriHargaJual::get();

        $JenisBahanBaku = Jenis::where('slug', 'bahan-baku')->first();
        $dataProduk = Produk::where('jenis_id', $JenisBahanBaku->id)->get();
        return view('produk.form', compact(
            'title',
            'form',
            'countColom',
            'colomField',
            'countColomFooter',
            'store',
            'dataKategoriHargaJual',
            'dataProduk',
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
        //get dari post form
        $getRequest = $this->getRequest($request);
        // return $this->configForm();
        $validation = $getRequest['validasi'];
        $form = $getRequest['form'];
        $messages = $getRequest['messages'];
        //validasi
        $this->validate(
            $request,
            $validation,
            $messages
        );
        //open model
        $data = $this->model();

        foreach ($form as $index => $item) {
            if (isset($this->manyToMany)) {
                if (in_array($index, $this->manyToMany)) {
                    break;
                }
            }
            if ($index === "password") {
                $item = bcrypt($item);
            }
            $data->$index = $item;
        }
        if ($this->user && !isset($this->extraFrom)) {
            // return Auth::user()->id;
            $data->user_id = Auth::user()->id;
        }
        $data->save();

        $kategoriHarga = str_replace(".", "", $request->kategori_harga);
        $id_kategori_harga = $request->id_kategori_harga;

        $extra = array_map(function ($harga) {
            return ['harga_jual' => $harga];
        }, $kategoriHarga);

        $array_combine = array_combine($id_kategori_harga, $extra);

        if (isset($kategoriHarga)) {
            $data->KategoriHargaJual()->sync($array_combine);
        }

        if (isset($request->iditem)) {
            $qty = str_replace(".", "", $request->qty);
            $item = [];
            $listitem = [];
            foreach ($qty as $index => $value) {
                $item[$index] = $request->iditem[$index];
                $listitem[$index] = [
                    'qty' => $value
                ];
            }
            $syncData  = array_combine($item, $listitem);

            $data->hasBahan()->sync($syncData);
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

        $KategoriHargaJual = $data->KategoriHargaJual()->get();

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
        $dataKategoriHargaJual = KategoriHargaJual::get();

        $JenisBahanBaku = Jenis::where('slug', 'bahan-baku')->first();
        $dataProduk = Produk::where('jenis_id', $JenisBahanBaku->id)->get();
        // return $data;

        return view('produk.form', compact(
            'route',
            'store',
            'colomField',
            'countColom',
            'countColomFooter',
            'KategoriHargaJual',
            'dataKategoriHargaJual',
            'dataProduk',
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
        //post ke model
        // $this->model()->transaction();
        foreach ($form as $index => $item) {
            if ($index === "password") {
                $item = bcrypt($item);
            }
            if ($this->manyToMany) {
                # code...
                if (in_array(str_replace('_id', '', $index), $this->manyToMany)) {
                    $manyToMany = str_replace('_id', '', $index);
                    break;
                }
            }
            $data->$index = $item;
        }

        if (isset($relation)) {
            $firstColumn = [];
            if (isset($this->extraFrom)) {

                foreach ($relation as $key => $value) {
                    $relationsFields = $key . '_id';
                    $relationModels = '\\App\Models\\' . ucfirst($key);
                    $relationModels = new $relationModels;
                    $relationModels = $relationModels->find($data->$relationsFields);
                    if ($relationModels) {
                        foreach ($value as $colom => $val) {
                            if ($colom === "password") {
                                $val = bcrypt($val);
                            }
                            if (in_array(str_replace('_id', '', $colom), $this->manyToMany)) {
                                $manyToMany = str_replace('_id', '', $colom);
                                $valueMany[$manyToMany] = $val;
                                break;
                            }
                            $relationModels->$colom = $val;
                        }
                        $relationModels->save();
                        if (isset($manyToMany)) {
                            $relationModels->$manyToMany()->sync($valueMany);
                        }
                    }
                    try { //
                    } catch (\Throwable $th) { }
                }
            }
            // return $extraFrom;
        }

        $data->save();
        // try { } catch (\Throwable $th) {
        //     $this->model()->rollback();
        // }
        $kategoriHarga = str_replace(".", "", $request->kategori_harga);
        $id_kategori_harga = $request->id_kategori_harga;

        $extra = array_map(function ($harga) {
            return ['harga_jual' => $harga];
        }, $kategoriHarga);

        $array_combine = array_combine($id_kategori_harga, $extra);

        if (isset($kategoriHarga)) {
            $data->KategoriHargaJual()->sync($array_combine);
        }
        if (isset($this->manyToMany)) {
            if (!isset($this->extraFrom)) {
                foreach ($this->manyToMany as  $value) {
                    $hasRalation = 'has' . ucfirst($value);
                    $valueField = $data->$hasRalation()->sync($form[$value]);
                    try { } catch (\Throwable $th) { }
                }
            }
        }

        if (isset($request->iditem)) {
            $qty = str_replace(".", "", $request->qty);
            $item = [];
            $listitem = [];
            foreach ($qty as $index => $value) {
                $item[$index] = $request->iditem[$index];
                $listitem[$index] = [
                    'qty' => $value
                ];
            }
            $syncData  = array_combine($item, $listitem);

            $data->hasBahan()->sync($syncData);
        }


        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil diubah')->with('Class', 'primary');
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = $this->model()->find($id);
        $message = "Produk ada";
        $type_diskon = null;
        $btn = null;
        $diskon = 0;
        // $relation = $model->hasPromosi;
        // if (count($relation) > 0) {
        //     $type_diskon =  $model->hasPromosi()->first()->pivot->type_diskon;
        //     $diskon = $model->hasPromosi()->first()->pivot->diskon;
        // }

        $promosiId = request()->promosi;
        $produkId = request()->produk_promosi;
        $promosi = Promosi::find($promosiId);
        if (isset($promosiId) && !$produkId) {
            $btn = 'disable';
            $promosi =  Promosi::find($promosiId);
            if ($promosi) {
                foreach ($promosi->hasProduk as $key => $value) {
                    if ($value->id == $id) {
                        $type_diskon = $value->pivot->type_diskon;
                        $diskon = $value->pivot->diskon;
                    }
                }
            }
        } else {
            $promosi = Promosi::where('mulai', '<=', Carbon::now())->where('selesai', '>=', Carbon::now())->orderBy('created_at', 'asc')->first();
            if ($promosi) {
                foreach ($promosi->hasProduk as $key => $value) {
                    if ($value->id == $id) {
                        $type_diskon = $value->pivot->type_diskon;
                        $diskon = $value->pivot->diskon;
                    }
                }
            }
        }


        $data  = [
            'id' => $model->id,
            'nama' => $model->nama,
            'harga_jual' => $model->harga_jual,
            'karyawan' => $model->karyawan,
            'jenis' => $model->jenis,
            'type_diskon_promosi' => $type_diskon,
            'diskon_produk' => $diskon,
            'satuan' => $model->satuan,
            'harga_jual_tampil' => format_uang($model->harga_jual),
            'btn_disable' => $btn
        ];
        if (!$data) {
            $data = [];
            $message = "produk tidak ada";
        }
        $result = [
            'data' => $data,
            'message' =>  $message,
        ];
        return response()->json($result);
    }
    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $model = $this->model()->find($id);
        $message = "Produk ada";
        $type_diskon = null;
        $diskon = 0;
        $relation = $model->hasPromosi;
        // if (count($relation) > 0) {
        //     $type_diskon =  $model->hasPromosi()->first()->pivot->type_diskon;
        //     $diskon = $model->hasPromosi()->first()->pivot->diskon;
        // }

        $data  = [
            'id' => $model->id,
            'nama' => $model->nama,
            'harga_jual' => $model->harga_jual,
            'karyawan' => $model->karyawan,
            'jenis' => $model->jenis,
            'type_diskon_promosi' => $type_diskon,
            'diskon_produk' => $diskon,
            'satuan' => $model->satuan,
            'harga_jual_tampil' => $model->harga_jual,
        ];
        if (!$data) {
            $data = [];
            $message = "produk tidak ada";
        }
        $result = [
            'data' => $data,
            'message' =>  $message,
        ];
        return response()->json($result);
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

        $data->KategoriHargaJual()->detach();
        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil dihapus')->with('Class', 'danger');
    }
}
