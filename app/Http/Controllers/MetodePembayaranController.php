<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;

class MetodePembayaranController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'metode-pembayaran';
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
                'alias'    => 'Nama Metode',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Metode',
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
                'alias'    => 'Nama Metode',
                'validasi'    => ['required', 'min:1'],
            ],

        ];
    }

    public function model()
    {
        return new MetodePembayaran();
    }
}
