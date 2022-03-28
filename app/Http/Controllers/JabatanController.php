<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;
use App\Models\Shift;
use App\Traits\CrudTrait;

class JabatanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'jabatan';
        $this->oneToMany = ['shift'];
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
                'alias'    => 'Nama Jabatan',
            ],
            [
                'name'    => 'gajih_perbulan',
                'alias'    => 'Gajih Perbulan',
                'input'    => 'rupiah',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Jabatan',
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
                'alias'    => 'Nama Jabatan',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'divisi_id',
                'input'    => 'combo',
                'alias'    => 'Divisi',
                'value' => $this->combobox('Divisi'),
                'validasi'    => ['required'],
            ],
        ];
    }
}
