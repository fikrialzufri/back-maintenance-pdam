<?php

namespace App\Http\Controllers;

use App\Models\Rekanan;
use App\Models\Role;
use App\Models\User;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Excel;

class RekananController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'rekanan';
        $this->sort = 'nama';
        $this->plural = 'true';
        $this->upload = 'true';
        $this->manyToMany = ['role'];
        $this->relations = ['user'];
        $this->extraFrom = ['user'];
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
                'alias'    => 'Nama CV',
            ],
            [
                'name'    => 'nama_penangung_jawab',
                'alias'    => 'Nama Penanggung Jawab',

            ],
            [
                'name'    => 'nik',
                'alias'    => 'Nomor KTP',
            ],
            [
                'name'    => 'no_hp',
                'alias'    => 'No HP',
            ],
            [
                'name'    => 'alamat',
                'alias'    => 'Alamat',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Rekanan',
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
                'alias'    => 'Nama CV',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'nama_penangung_jawab',
                'input'    => 'text',
                'alias'    => 'Nama Penanggung Jawab',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'nik',
                'input'    => 'text',
                'alias'    => 'Nomor KTP / NIK',
                'validasi'    => ['required', 'min:1', 'unique'],
            ],
            [
                'name'    => 'no_hp',
                'input'    => 'text',
                'alias'    => 'No HP',
                'validasi'    => ['required', 'min:1', 'unique'],
            ],
            [
                'name'    => 'alamat',
                'input'    => 'textarea',
                'alias'    => 'Alamat',
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'username',
                'alias'    => 'Username',
                'validasi'    => ['required', 'unique', 'min:3', 'plural'],
                'extraForm' => 'user',
            ],
            [
                'name'    => 'password',
                'alias'    => 'Password',
                'input'    => 'password',
                'validasi'    => ['required', 'min:8'],
                'extraForm' => 'user',
            ],
            [
                'name'    => 'email',
                'alias'    => 'Email',
                'input'    => 'email',
                'validasi'    => ['required',  'plural', 'unique', 'email'],
                'extraForm' => 'user',
            ],
            [
                'name'    => 'role_id',
                'input'    => 'combo',
                'alias'    => 'Hak Akses',
                'value' => $this->combobox('Role', 'slug', 'rekanan', '='),
                'validasi'    => ['required'],
                'extraForm' => 'user',
                'hasMany'    => ['role'],
            ],
            [
                'name'    => 'tdd',
                'input'    => 'image',
                'alias'    => 'Tanda Tangan',
                'validasi'    => ['mimes:jpeg,bmp,png,jpg'],
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
        $title =  "Upload Data rekanan";
        $route = $this->route;
        $action = route('rekanan.upload');

        return view('rekanan.upload', compact(
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

        $dataRekanan = [];
        $dataCV = [];
        $dataNama = [];
        $dataKtp = [];
        $dataNoHp = [];
        $dataAlamat = [];
        $dataUser = [];
        $dataUserName = [];
        $dataPassword = [];
        $dataEmail = [];
        $itemExist = [];
        $roles = Role::whereSlug('rekanan')->first();
        $file = $request->hasFile('file');
        $total = 0;
        try {
            if ($file) {
                $item = Excel::toArray('', request()->file('file'), null, null);
                foreach ($item[0] as $k => $val) {
                    $dataItem[$k] = $val;
                }
                foreach ($dataItem as $index => $item) {
                    $dataCV[$index] = $item[1];
                    $dataNama[$index] = $item[2];
                    $dataKtp[$index] = $item[3];
                    $dataNoHp[$index] = $item[4];
                    $dataAlamat[$index] = $item[5];
                    $dataUserName[$index] = $item[6];
                    $dataPassword[$index] = $item[7];
                    $dataEmail[$index] = $item[8];

                    if ($index > 2) {
                        $dataUser[$index] = User::where('username', 'LIKE', '%' .  $dataCV[$index] . "%")->first();
                        if (!$dataUser[$index]) {
                            $dataUser[$index] = new User;
                            $dataUser[$index]->name =  $dataUserName[$index];
                            $dataUser[$index]->username =  $dataUserName[$index];
                            $dataUser[$index]->password =  bcrypt($dataPassword[$index]);
                            $dataUser[$index]->email =  $dataEmail[$index];
                            $dataUser[$index]->save();
                            if ($roles) {
                                $dataUser[$index]->role()->attach($roles->id);
                            }
                        }
                        $dataRekanan[$index] = Rekanan::where('nama', 'LIKE', '%' . $dataCV[$index] . "%")->first();
                        if (!$dataRekanan[$index]) {
                            if ($dataNama[$index] != null) {
                                $Rekanan = new Rekanan;
                                $Rekanan->nama =  $dataCV[$index];
                                $Rekanan->nama_penangung_jawab =  $dataNama[$index];
                                $Rekanan->nik =  $dataKtp[$index];
                                $Rekanan->no_hp =  $dataNoHp[$index];
                                $Rekanan->alamat =  $dataAlamat[$index];
                                $Rekanan->user_id =  $dataUser[$index]->id;
                                $Rekanan->save();
                                $total = ++$index;
                            }
                        }
                    }
                }
                return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil diupload dengan total item :' . $total)->with('Class', 'success');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' gagal diupload')->with('Class', 'success');
        }
    }

    public function model()
    {
        return new Rekanan();
    }
}
