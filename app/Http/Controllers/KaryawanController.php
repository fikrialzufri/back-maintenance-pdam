<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Traits\CrudTrait;

class KaryawanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'karyawan';
        $this->sort = 'nama';
        $this->plural = 'true';
        $this->manyToMany = ['role'];
        $this->relations = ['user'];
        $this->extraFrom = ['user'];
        $this->oneToMany = ['rekanan'];
        $this->middleware('permission:view-' . $this->route, ['only' => ['index']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name' => 'nama_jabatan',
                'alias' => 'Jabatan',
            ],
            [
                'name' => 'nama',
                'alias' => 'Nama Karyawan',
            ],

            [
                'name' => 'divisi',
                'alias' => 'Divisi',
            ],
            [
                'name' => 'wilayah',
                'alias' => 'Wilayah',
            ],
            [
                'name' => 'departemen',
                'alias' => 'Departemen',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name' => 'nama',
                'input' => 'text',
                'alias' => 'Nama Karyawan',
                'value' => null
            ],
        ];
    }
    public function configForm()
    {

        return [
            [
                'name' => 'nama',
                'input' => 'text',
                'alias' => 'Nama Karyawan',
                'validasi' => ['required', 'min:1'],
            ],
            [
                'name' => 'nip',
                'input' => 'text',
                'alias' => 'NIP',
                'validasi' => ['required', 'min:1', 'unique'],
            ],
            [
                'name' => 'nik',
                'input' => 'text',
                'alias' => 'Nomor KTP',
                'validasi' => ['required', 'min:1', 'unique'],
            ],
            [
                'name' => 'jabatan_id',
                'input' => 'combo',
                'alias' => 'Jabatan',
                'value' => $this->combobox(
                    'Jabatan',
                    null,
                    null,
                    null,
                    'nama',
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    ['divisi', 'wilayah', 'departemen'],
                ),
                'validasi' => ['required'],
            ],
            [
                'name' => 'rekanan_id',
                'input' => 'combo',
                'alias' => 'Rekanan',
                'value' => $this->combobox(
                    'Rekanan'
                ),
                'multiple' => true,
            ],
            [
                'name' => 'pekerjaan',
                'input' => 'radio',
                'alias' => 'Melakukan Pekerjaan Galian',
                'value' => ['ya', 'tidak'],
                'default' => 'tidak',
                'multiple' => true,
            ],
            [
                'name' => 'email',
                'alias' => 'Email',
                'input' => 'email',
                'validasi' => ['required', 'plural', 'unique', 'email'],
                'extraForm' => 'user',
            ],
            [
                'name' => 'username',
                'alias' => 'Username',
                'input' => 'text',
                'validasi' => ['required', 'unique', 'min:3', 'plural'],
                'extraForm' => 'user',
            ],
            [
                'name' => 'password',
                'alias' => 'Password',
                'input' => 'password',
                'validasi' => ['required', 'min:8'],
                'extraForm' => 'user',
            ],

            [
                'name' => 'role_id',
                'input' => 'combo',
                'alias' => 'Hak Akses',
                'value' => $this->combobox('Role', 'slug', ['superadmin', 'rekanan'], '!=', 'slug'),
                'validasi' => ['required'],
                'extraForm' => 'user',
                'hasMany' => ['role'],
            ],
            [
                'name' => 'url',
                'input' => 'text',
                'alias' => 'Url Tanda Tangan',
            ],
            [
                'name' => 'tdd',
                'input' => 'image',
                'alias' => 'Tanda Tangan',
                'validasi' => ['mimes:jpeg,bmp,png,jpg'],
            ],
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tdd($id)
    {
        $route = $this->route;
        $karyawan = Karyawan::find($id);
        $title = "Tanda Tangan " . $karyawan->nama;


        return view(
            'karyawan.tdd',
            compact(
                "title",
                "route",
                "karyawan"
            )
        );
    }

    public function model()
    {
        return new Karyawan();
    }
}
