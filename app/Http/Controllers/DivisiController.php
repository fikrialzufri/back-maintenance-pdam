<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Traits\CrudTrait;

class DivisiController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'divisi';
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
                'alias'    => 'Nama Divisi',
            ],
            [
                'name'    => 'departemen',
                'alias'    => 'Nama Departemen',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Divisi',
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
                'alias'    => 'Nama Divisi',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'departemen_id',
                'input'    => 'combo',
                'alias'    => 'Departemen',
                'value' => $this->combobox('Departemen'),
                'validasi'    => ['required'],
            ],
        ];
    }

    public function model()
    {
        return new Divisi();
    }
}
