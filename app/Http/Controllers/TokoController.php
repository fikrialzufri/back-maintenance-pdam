<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Traits\CrudTrait;

class TokoController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'toko';
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
                'alias'    => 'Nama Toko',
            ],
            [
                'name'    => 'nama_cabang',
                'alias'    => 'Nama Cabang',
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
            [
                'name'    => 'ip_public',
                'alias'    => 'IP Public',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Toko',
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
                'alias'    => 'Nama Toko',
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
                'name'    => 'cabang_id',
                'input'    => 'combo',
                'alias'    => 'Cabang',
                'value' => $this->combobox('cabang'),
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'ip_public',
                'input'    => 'text',
                'alias'    => 'IP Public',
                'validasi'    => ['required', 'min:1'],
            ],
        ];
    }


    public function model()
    {
        return new Toko();
    }
}
