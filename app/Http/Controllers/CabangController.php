<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Traits\CrudTrait;

class CabangController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'cabang';
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
                'alias'    => 'Nama Cabang',
            ],
            [
                'name'    => 'nama_pusat',
                'alias'    => 'Nama Pusat',
            ],
            [
                'name'    => 'alamat',
                'alias'    => 'Alamat',
            ],
            [
                'name'    => 'no_hp',
                'alias'    => 'No HP',
            ],
            [
                'name'    => 'email',
                'alias'    => 'Email',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Cabang',
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
                'alias'    => 'Nama Cabang',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'alamat',
                'input'    => 'textarea',
                'alias'    => 'Alamat',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'no_hp',
                'input'    => 'number',
                'alias'    => 'No Hp',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'email',
                'input'    => 'email',
                'alias'    => 'Email',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'pusat_id',
                'input'    => 'combo',
                'alias'    => 'Pusat',
                'value' => $this->combobox('Pusat'),
                'validasi'    => ['required'],
            ],
        ];
    }

    public function model()
    {
        return new Cabang();
    }
}
