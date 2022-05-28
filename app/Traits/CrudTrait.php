<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;
use Storage;
use DB;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

trait CrudTrait
{
    //abstract untuk model
    abstract function model();

    //abstract untuk header tabel
    abstract function configHeaders();

    //abstract untuk pencarian
    abstract function configSearch();

    //abstract untuk form
    abstract function configForm();

    //nama route dan title
    protected $route;

    //nama route dan title
    protected $index;

    //nama route dan title
    protected $title;

    //sort
    protected $sort;

    //desc
    protected $desc;

    //relasi table
    protected $relations;

    //pemberian jumlah data dalam tabel
    protected $paginate = 15;

    //pemberian nama user
    private $user;

    //pemberian nama user
    private $kelipatan = 6;

    //pemberian extra form
    private $extraFrom;

    //many to many
    private $manyToMany;

    //many to many
    private $oneToMany;

    //btn-tambah
    private $tambah = 'true';

    //btn-upload
    private $upload = 'false';

    // validation rule harus pkai object
    // tentukan jumlah col-sm boostrap
    // ooption object label
    // span untuk colom
    // textfield harus ada option untuk diab
    // menetukan option selecet dalam bentuk object

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
        // return  $data;

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

        return view('template.form', compact(
            'title',
            'form',
            'countColom',
            'colomField',
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
        $relationModels = '';
        DB::beginTransaction();
        try {
            //open model
            DB::commit();
            $data = $this->model();
            if (isset($relation)) {
                $firstColumn = [];
                $manyToMany  = null;
                $manyRelation  = null;
                $valueMany  = null;
                foreach ($relation as $key => $value) {
                    try { //
                        $relationModels = '\\App\Models\\' . ucfirst($key);
                        $relationModels = new $relationModels;
                        foreach ($value as $colom => $val) {
                            if ($colom === "password") {
                                $val = bcrypt($val);
                            }
                            if (in_array(str_replace('_id', '', $colom), $this->manyToMany)) {
                                $manyToMany = str_replace('_id', '', $colom);
                                $valueMany[$manyToMany] = $val;
                                continue;
                            }
                            $relationModels->$colom = $val;
                        }
                        $relationModels->save();
                        if (isset($manyToMany)) {
                            $relationModels->$manyToMany()->attach($valueMany);
                        }
                        $relationsFields = $key . '_id';

                        $data->$relationsFields = $relationModels->id;
                    } catch (\Throwable $th) { }
                }
            }

            //post ke model

            foreach ($form as $index => $item) {
                if (isset($this->manyToMany)) {
                    if (in_array($index, $this->manyToMany)) {
                        continue;
                    }
                }
                if ($this->oneToMany) {
                    if (in_array(str_replace('_id', '', $index), $this->oneToMany)) {
                        $oneToMany = str_replace('_id', '', $index);
                        continue;
                    }
                }
                if (preg_match("/-image/i", $index)) {
                    $route =  $this->route;
                    $file =  str_replace("-image", "", $index);
                    if ($request->hasFile($file)) {
                        # code...
                        $nama_gambar = Str::slug($route) . '-' . Str::Random(15) . '.' . $request->file($file)->getClientOriginalExtension();

                        if (!Storage::disk('public')->exists($route)) {
                            Storage::disk('public')->makeDirectory($route);
                        }
                        if (!Storage::disk('public')->exists($route . '/thumbnail')) {
                            Storage::disk('public')->makeDirectory($route . '/thumbnail');
                        }

                        $path = public_path('storage/' . $route . '/' . $nama_gambar);

                        $gambar_original = Image::make($request->file($file))->save($path);
                        Storage::disk('public')->put($route . '/' . $nama_gambar, $gambar_original);


                        $thumbnail = Image::make($request->file($file))->resize(360, 360)->save($path);
                        Storage::disk('public')->put($route . '/thumbnail' . '/' . $nama_gambar, $thumbnail);

                        $data->$file = $nama_gambar;
                    }
                    continue;
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

            if (isset($this->manyToMany)) {
                if (!isset($this->extraFrom)) {
                    return $this->manyToMany;
                    foreach ($this->manyToMany as  $value) {
                        $hasRalation = 'has' . ucfirst($value);
                        $valueField = $data->$hasRalation()->attach($form[$value]);
                    }
                }
            }
            if (isset($this->oneToMany)) {
                foreach ($this->oneToMany as $index => $value) {
                    $hasRalation = 'has' . ucfirst($value);
                    $idRelation = $value . '_id';
                    $valueField = $data->$hasRalation()->attach($form[$idRelation]);
                }
            }
            // $hasRalation;
            //redirect
            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil Ditambahkan')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();
            if (isset($this->manyToMany)) {
                if (!isset($this->extraFrom)) {
                    return $this->manyToMany;
                    foreach ($this->manyToMany as  $value) {
                        $hasRalation = 'has' . ucfirst($value);
                        $valueField = $data->$hasRalation()->detach($form[$value]);
                    }
                }
            }
            if (isset($this->oneToMany)) {
                foreach ($this->oneToMany as $index => $value) {
                    $hasRalation = 'has' . ucfirst($value);
                    $idRelation = $value . '_id';
                    $valueField = $data->$hasRalation()->detach($form[$idRelation]);
                }
            }
            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' gagal Ditambahkan')->with('Class', 'success');
        }
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
        if (isset($this->oneToMany)) {
            foreach ($this->oneToMany as $item) {
                $hasRalation = 'has' . ucfirst($item);
                $field = $item . '_id';
                if (count($data->$hasRalation) != 0) {
                    $valueField = $data->$hasRalation()->first()->id;
                    $data->$field = $valueField;
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

        return view('template.form', compact(
            'route',
            'store',
            'colomField',
            'countColom',
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

            if (preg_match("/-image/i", $index)) {
                $route =  $this->route;
                $file =  str_replace("-image", "", $index);
                if ($request->hasFile($file)) {
                    $nama_gambar = Str::slug($route) . '-' . Str::Random(15) . '.' . $request->file($file)->getClientOriginalExtension();

                    $path = public_path('storage/' . $route . '/' . $nama_gambar);

                    if (!Storage::disk('public')->exists($route)) {
                        Storage::disk('public')->makeDirectory($route);
                    }
                    if (!Storage::disk('public')->exists($route . '/thumbnail')) {
                        Storage::disk('public')->makeDirectory($route . '/thumbnail');
                    }

                    // delete gambar original
                    if (Storage::disk('public')->exists($route . '/' . $data->$file)) {
                        Storage::disk('public')->delete($route . '/' . $data->$file);
                    }

                    $gambar_original = Image::make($request->file($file))->save($path);
                    Storage::disk('public')->put($route . '/' . $nama_gambar, $gambar_original);

                    // delete gambar thumbnail
                    if (Storage::disk('public')->exists($route . '/thumbnail' . '/' . $data->$file)) {
                        Storage::disk('public')->delete($route . '/thumbnail' . '/' . $data->$file);
                    }
                    $thumbnail = Image::make($request->file($file))->resize(720, 720)->save($path);
                    Storage::disk('public')->put($route . '/thumbnail' . '/' . $nama_gambar, $thumbnail);

                    $data->$file = $nama_gambar;
                }
                continue;
            }
            if ($index === "password") {
                $item = bcrypt($item);
            }
            if ($this->manyToMany) {
                # code...
                if (in_array(str_replace('_id', '', $index), $this->manyToMany)) {
                    $manyToMany = str_replace('_id', '', $index);
                    continue;
                }
            }
            if ($this->oneToMany) {
                if (in_array(str_replace('_id', '', $index), $this->oneToMany)) {
                    $oneToMany = str_replace('_id', '', $index);
                    continue;
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
                                continue;
                            }
                            $relationModels->$colom = $val;
                        }
                        $relationModels->save();
                        if (isset($manyToMany)) {
                            $relationModels->$manyToMany()->sync($valueMany);
                        }
                    }
                }
            }
        }

        $data->save();

        if (isset($this->manyToMany)) {
            if (!isset($this->extraFrom)) {
                foreach ($this->manyToMany as  $value) {
                    $hasRalation = 'has' . ucfirst($value);
                    $valueField = $data->$hasRalation()->sync($form[$value]);
                }
            }
        }

        if (isset($this->oneToMany)) {
            foreach ($this->oneToMany as $index => $value) {
                $hasRalation = 'has' . ucfirst($value);
                $idRelation = $value . '_id';

                $valueField = $data->$hasRalation()->sync($form[$idRelation]);
            }
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
        if (isset($this->manyToMany)) {
            if (!isset($this->extraFrom)) {
                foreach ($this->manyToMany as  $value) {
                    $hasRalation = 'has' . ucfirst($value);
                    $valueField = $data->$hasRalation()->detach();
                    try { } catch (\Throwable $th) { }
                }
            }
        }
        if (isset($this->oneToMany)) {
            foreach ($this->oneToMany as $index => $value) {
                $hasRalation = 'has' . ucfirst($value);
                $valueField = $data->$hasRalation()->detach();
            }
        }
        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil dihapus')->with('Class', 'danger');
    }


    /**
     * get all request form.
     *
     *
     */
    public function getRequest($request, $id = null, $relationId = null)
    {
        $messages = [
            'required' => 'tidak boleh kosong',
            'unique' => 'tidak boleh sama',
            'min' => 'harus minimal :min',
            'max' => 'harus makasimal :max',
        ];

        $validation = [];
        $form = [];
        $relation = [];


        foreach ($this->configForm() as $index =>  $value) {
            if (isset($value['extraForm'])) { }

            if (isset($value['validasi'])) {
                $validasi = $value['validasi'];
                if (in_array('unique', $validasi)) {
                    foreach ($validasi as $index => $item) {
                        $tabelUnique = 'unique:' . $this->route;

                        //check extram form untuk ambil id
                        if (isset($value['extraForm'])) {
                            $tabelUnique = 'unique:' . $value['extraForm'];
                            if ($relationId) {
                                $id = $relationId[$value['extraForm'] . '_id'];
                            }
                        }

                        if ($item === 'unique') {
                            if ($id) {

                                $unique = $tabelUnique  . ',' . $value['name'] . ',' . $id;
                            } else {
                                $unique = $tabelUnique . ',' . $value['name'];
                            }

                            $validasi[$index] = $unique;
                        }
                    }
                }
                // str_replace("plural", "", $value['validasi']);
                // $unique = 'unique:' . $this->route;
                if (isset($value['extraForm'])) {
                    foreach ($validasi as $index => $item) {
                        # zamak
                        if ($item == 'plural') {
                            $validasi = str_replace('unique:' . $value['extraForm'], 'unique:' . $value['extraForm'] . 's', $validasi);
                            unset($validasi[$index]);
                        }
                    }
                }
                // $validation[$value['name']] =  $validasi;

                //untuk menjadikan satu dari array
                $validation[$value['name']] =  implode("|",  $validasi);
            }

            if (!isset($value['extraForm'])) {
                if (isset($value['input'])) {
                    if ($value['input'] === "rupiah") {
                        $form[$value['name']] = str_replace(".", "", $request->input($value['name']));
                    } else if ($value['input'] === "image") {
                        $form[$value['name'] . '-image'] = 'image';
                    } else {

                        $form[$value['name']] = $request->input($value['name']);
                    }
                } else {

                    $form[$value['name']] = $request->input($value['name']);
                }
            } else {
                foreach ($this->extraFrom as $realtion) {
                    if ($realtion == $value['extraForm']) {
                        $relation[$realtion][$value['name']] = $request->input($value['name']);
                    }
                }
            }
        }

        return [
            "messages" => $messages,
            "validasi" => $validation,
            "relation" => $relation,
            "form" => $form
        ];
    }

    public function combobox($table, $colom = null, $field = null, $operator = null, $sort = null, $appendModel = null, $colomAppend = null, $fieldAppend = null, $operatorAppend = null, $sortAppend = null, $hasRelation = null, $hasColom = null, $arrayColom = null)
    {
        $model = '\\App\Models\\' . ucfirst($table);
        $model = new $model;
        $query = $model->query();
        if ($colom) {
            if ($field) {
                if (is_array($field)) {
                    foreach ($field as $key => $value) {
                        $query = $query->where($colom, $operator, $value);
                    }
                } else {
                    $query = $query->where($colom, $operator, $field);
                }
            }
        }
        if ($sort) {
            $query->orderBy($sort);
        }
        $relationData = $query->get();


        if ($appendModel) {
            $modelSecond = '\\App\Models\\' . ucfirst($appendModel);
            $modelSecond = new $modelSecond;
            $querySecond = $modelSecond->query();
            if ($colomAppend) {
                if (is_array($fieldAppend)) {
                    foreach ($fieldAppend as $key => $value) {
                        $querySecond = $querySecond->where($colomAppend, $operatorAppend, $value);
                    }
                } else {
                    $querySecond = $querySecond->where($colomAppend, $operatorAppend, $fieldAppend);
                }
            }
            if ($sortAppend) {
                $querySecond->orderBy($sortAppend);
            }
            $querySecond = $querySecond->get();
            $relationData = $relationData->merge($querySecond);
        }
        // return [$colomAppend, $operatorAppend, $fieldAppend];

        $data = [];
        foreach ($relationData as $key => $item) {

            if ($hasRelation) {
                $nama = ucfirst($table) . ' : ' . $item->nama . " | " . ucfirst($hasRelation)  . " : " .  $item->$hasColom;
                if (!$nama) {
                    $nama = ucfirst($table) . ' : ' . $item->name . " | " . ucfirst($hasRelation) . " : " .  $item->$hasColom;
                }
            } else {
                $nama = $item->nama;
                if (!$nama) {
                    $nama = $item->name;
                }
            }
            $array = [];
            if (isset($arrayColom)) {
                foreach ($arrayColom as $value) {
                    $rplcs = str_replace("_", " ",  $value);
                    $array[$value] = ucwords($rplcs) . ' : ' . $item->$value . ' | ';
                }
                $listdata = implode(" ", $array);
                $nama = $nama . ' | ' . $listdata;
                $nama =  rtrim($nama, ' | ');
            }

            $data[$key] = [
                'id'    => $item->id,
                'value'    => $nama,
            ];
        }

        return $data;
    }

    public function countColom($count)
    {
        if ($count < $this->kelipatan) {
            return 6;
        }
        return 6;
    }

    public function countColomFooter($count)
    {
        if ($count > $this->kelipatan) {
            return 12;
        }
        return 6;
    }

    public function colomField($count)
    {
        if ($count < $this->kelipatan * 2 && $count > $this->kelipatan) {
            $count = $this->kelipatan * 2;
        }
        if ($count < $this->kelipatan) {
            $count = $this->kelipatan;
        }
        $lipat = $this->kelipatan;
        $akhir = [];
        $nomor = 0;
        for ($i = 1; $i <= $count; $i++) {

            if ($bagi = $i % $lipat == 0) {
                $nomor++;
                $akhir[$nomor] = $i;
            }
        }

        $sebelum = [];
        $dataKedua = [];
        $hasil = [];
        foreach ($akhir as $key => $value) {
            $jumlahsebelumnya = 0;
            if ($key >= 2) {
                $keysebelumya = $key - 1;
                $jumlahsebelumnya =  $sebelum[$keysebelumya];
            }
            $sebelum[$key] = $value;
            $dataKedua[$key] = $jumlahsebelumnya . ',' . $value;
            $hasil[$key] = explode(",", $dataKedua[$key]);
        }

        return $hasil;
    }

    public function configButton()
    {
        //config buttonInsts
    }
}
