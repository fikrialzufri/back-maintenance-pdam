<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Traits\CrudTrait;

class TagihanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'tagihan';
        $this->tambah = 'false';
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
                'alias'    => 'Nama Rekanan',
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

        return [];
    }

    public function model()
    {
        return new Tagihan();
    }
}
