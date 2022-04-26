<?php

namespace App\Http\Controllers;

use App\Models\PelaksanaanPekerjaan;
use App\Traits\CrudTrait;

class PelaksanaanPekerjaanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->tambah = 'false';
        $this->route = 'pelaksanaan-pekerjaan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'no_spk',
                'alias'    => 'Nomor SPK',
            ],
            // [
            //     'name'    => 'nomor_pelaksanaan_pekerjaan',
            //     'alias'    => 'Nomor Pekerjaan',
            // ],
            [
                'name'    => 'rekanan',
                'alias'    => 'Nama Rekanana',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nomor_pelaksanaan_pekerjaan',
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
        return new PelaksanaanPekerjaan();
    }
}
