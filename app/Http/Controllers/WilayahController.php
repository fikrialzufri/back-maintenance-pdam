<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use App\Traits\CrudTrait;

class WilayahController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'wilayah';
        $this->sort = 'nama';
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
                'alias'    => 'Nama Wilayah',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Wilayah',
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
                'alias'    => 'Nama Wilayah',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'singkatan',
                'input'    => 'text',
                'alias'    => 'Singkatan Wilayah',
                'validasi'    => ['required', 'unique', 'min:1'],
            ]
        ];
    }

    public function model()
    {
        return new Wilayah();
    }
}
