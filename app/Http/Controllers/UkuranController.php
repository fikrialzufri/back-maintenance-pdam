<?php

namespace App\Http\Controllers;

use App\Models\Ukuran;
use App\Traits\CrudTrait;

class UkuranController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'ukuran';
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
                'alias'    => 'Nama Ukuran',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Ukuran',
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
                'alias'    => 'Nama Ukuran',
                'validasi'    => ['required', 'unique', 'min:1'],
            ]
        ];
    }

    public function model()
    {
        return new Ukuran();
    }
}
