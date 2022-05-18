<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Traits\CrudTrait;

class JenisController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'jenis';
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
                'alias'    => 'Nama Jenis',
            ],
            [
                'name'    => 'nama_kategori',
                'alias'    => 'Nama Kategori',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Jenis',
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
                'alias'    => 'Nama Jenis',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'kategori_id',
                'input'    => 'combo',
                'alias'    => 'Kategori',
                'value' => $this->combobox('kategori'),
                'validasi'    => ['required'],
            ]
        ];
    }

    public function model()
    {
        return new Jenis();
    }
}
