<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Traits\CrudTrait;

class ItemController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'item';
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
                'alias'    => 'Nama Item',
            ],
            [
                'name'    => 'satuan',
                'alias'    => 'Nama Satuan',
            ],
            [
                'name'    => 'jenis',
                'alias'    => 'Nama Jenis',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Item',
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
                'alias'    => 'Nama Item',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'harga',
                'input'    => 'rupiah',
                'alias'    => 'Harga Item',
                'validasi'    => ['required',  'min:1'],
            ],
            [
                'name'    => 'jenis_id',
                'input'    => 'combo',
                'alias'    => 'Jenis',
                'value' => $this->combobox(
                    'Jenis',
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $hasRelation = 'kategori',
                    $hasColom = 'nama_kategori'
                ),
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'satuan_id',
                'input'    => 'combo',
                'alias'    => 'Satuan',
                'value' => $this->combobox(
                    'Satuan'
                ),
                'validasi'    => ['required'],
            ],
        ];
    }

    public function model()
    {
        return new Item();
    }
}
