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
                'name'    => 'jenis',
                'alias'    => 'Nama Jenis',
            ]
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
        ];
    }

    public function model()
    {
        return new Item();
    }

    public function detail($slug)
    {
        try {
            $message = 'Detail Item';
            $data = $this->model()->where('slug', $slug)->first();
            $result = [
                'nama' =>  $data->nama,
                'slug' =>  $data->slug,
                'jenis' =>  $data->jenis
            ];
            if (count($result) == 0) {
                $message = 'Data Item Belum Ada';
            }
            return $this->sendResponse($result, $message, 200);
        } catch (\Throwable $th) {
            $message = 'Detail Item';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $errorMessages = [], 404);
        }
    }

    public function getall()
    {
        try {
            $message = 'Data Item';
            $data = $this->model()->get();
            foreach ($data as $key => $value) {
                $result[$key] = [
                    'nama' =>  $value->nama,
                    'slug' =>  $value->slug,
                    'jenis' =>  $value->jenis
                ];
            }
            if (count($result) == 0) {
                $message = 'Data Item Belum Ada';
            }
            return $this->sendResponse($result, $message, 200);
        } catch (\Throwable $th) {
            $message = 'Detail Item';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $errorMessages = [], 404);
        }
    }
}
