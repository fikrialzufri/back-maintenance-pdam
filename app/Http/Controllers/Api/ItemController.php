<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Jenis;
use App\Models\Kategori;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->route = 'item';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $nama = $request->nama;
        $kategori = $request->kategori;
        $result = [];

        $message = 'Data Item';

        try {
            $query = $this->model();
            $query = $query->where('nama', 'like', '%' . $nama . '%');
            if ($kategori) {
                $kategori = Kategori::where('nama', 'like', '%' . $kategori . '%')->first();
                if ($kategori) {
                    $jenis = Jenis::where('kategori_id', $kategori->id)->get();
                    $query = $query->whereIn('jenis_id', $jenis->pluck('id'));
                }
            }

            $data = $query->get();
            foreach ($data as $key => $value) {
                $result[$key] = [
                    'id' =>  $value->id,
                    'nama' =>  $value->nama,
                    'slug' =>  $value->slug,
                    'satuan' =>  $value->satuan,
                    'jenis' =>  $value->jenis,
                    'kategori' =>  $value->hasJenis->nama_kategori,
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

    public function model()
    {
        return new Item();
    }
}
