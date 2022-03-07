<?php

namespace App\Http\Controllers;

use App\Models\Pusat;
use App\Traits\CrudTrait;

class PusatController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'pusat';
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
                'input'    => 'text',
                'alias'    => 'Nama Pusat',
            ],
            [
                'name'    => 'alamat',
                'input'    => 'text',
                'alias'    => 'Alamat',
            ],
            [
                'name'    => 'no_hp',
                'input'    => 'text',
                'alias'    => 'No HP',
            ],
            [
                'name'    => 'email',
                'input'    => 'text',
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
                'alias'    => 'Nama Pusat',
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
                'alias'    => 'Nama Pusat',
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
        ];
    }

    public function model()
    {
        return new Pusat();
    }
}
