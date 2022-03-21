<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;
use App\Models\Shift;
use App\Traits\CrudTrait;

class JabatanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'jabatan';
        $this->oneToMany = ['shift'];
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
                'alias'    => 'Nama Jabatan',
            ],
            [
                'name'    => 'gajih_perbulan',
                'alias'    => 'Gajih Perbulan',
                'input'    => 'rupiah',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Jabatan',
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
                'alias'    => 'Nama Jabatan',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'gajih',
                'input'    => 'combo',
                'alias'    => 'Gaji',
                'value' => [
                    [
                        'id'    => 'perbulan',
                        'value'    => 'perbulan',
                    ],
                    [
                        'id'    => 'perhari',
                        'value'    => 'perhari',
                    ],
                ],
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'gajih_perbulan',
                'input'    => 'rupiah',
                'alias'    => 'Gajih Perbulan',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'gajih_perhari',
                'input'    => 'rupiah',
                'alias'    => 'Gajih Perhari',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'target_pendapatan',
                'input'    => 'rupiah',
                'alias'    => 'Target Pendapatan',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'bonus_target',
                'input'    => 'rupiah',
                'alias'    => 'Bonus Target',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'komisi',
                'input'    => 'rupiah',
                'alias'    => 'Komisi Perlayanan',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
        ];
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
        // $hasValue = $this->hasValue;
        $dataShift = Shift::orderBy('name')->get();

        return view('jabatan.form', compact(
            'title',
            'form',
            'countColom',
            'colomField',
            'dataShift',
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

        //get dari post form
        $getRequest = $this->getRequest($request);
        // return $this->configForm();
        $validation = $getRequest['validasi'];
        $form = $getRequest['form'];
        $relation = $getRequest['relation'];
        $messages = $getRequest['messages'];
        //validasi
        $this->validate(
            $request,
            $validation,
            $messages
        );

        //open model
        $data = $this->model();
        //post ke model

        foreach ($form as $index => $item) {
            if (isset($this->manyToMany)) {
                if (in_array($index, $this->manyToMany)) {
                    break;
                }
            }
            if ($this->oneToMany) {
                if (in_array(str_replace('_id', '', $index), $this->oneToMany)) {
                    $oneToMany = str_replace('_id', '', $index);
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

        if (isset($this->oneToMany)) {
            foreach ($this->oneToMany as $index => $value) {
                $hasRalation = 'has' . ucfirst($value);
                $idRelation = $value . '_id';
                if (isset($request->$idRelation)) {

                    $item = [];
                    $listitem = [];
                    foreach ($request->denda as $index => $value) {
                        $value = str_replace("Rp.", "", $value);
                        $value = str_replace(".", "", $value);
                        $value = preg_replace("/[^0-9]/", "", $value);
                        $item[$index] = $request->$idRelation[$index];
                        $listitem[$index] = [
                            'denda' => $value
                        ];
                    }
                    $syncData  = array_combine($item, $listitem);

                    $data->$hasRalation()->attach($syncData);
                }
            }
        }
        // $hasRalation;
        //redirect
        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil Ditambahkan')->with('Class', 'success');
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dinamis
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->model()->find($id);
        if (!isset($this->title)) {
            $title =  "Ubah " . ucwords(str_replace('-', ' ', $this->route)) . ' - : ' . $data->nama;
        } else {
            $title =  "Ubah " . ucwords(str_replace('-', ' ', $this->title)) . ' - : ' . $data->nama;
        }

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
        $dataShift = Shift::orderBy('name')->get();

        $jabatanDenda = $data->hasShift()->get();
        return view('jabatan.form', compact(
            'route',
            'store',
            'colomField',
            'countColom',
            'dataShift',
            'jabatanDenda',
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
            if ($this->oneToMany) {
                if (in_array(str_replace('_id', '', $index), $this->oneToMany)) {
                    $oneToMany = str_replace('_id', '', $index);
                    break;
                }
            }
            $data->$index = $item;
        }


        $data->save();

        if (isset($this->oneToMany)) {
            foreach ($this->oneToMany as $index => $value) {
                $hasRalation = 'has' . ucfirst($value);
                $idRelation = $value . '_id';
                if (isset($request->$idRelation)) {

                    $item = [];
                    $listitem = [];
                    foreach ($request->denda as $index => $value) {
                        $value = str_replace("Rp.", "", $value);
                        $value = str_replace(".", "", $value);
                        $value = preg_replace("/[^0-9]/", "", $value);
                        $item[$index] = $request->$idRelation[$index];
                        $listitem[$index] = [
                            'denda' => $value
                        ];
                    }
                    $syncData  = array_combine($item, $listitem);

                    $data->$hasRalation()->sync($syncData);
                }
            }
        }

        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil diubah')->with('Class', 'primary');
    }


    public function model()
    {
        return new Jabatan();
    }
}
