<?php

namespace  App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jenis;

class JenisController extends Controller
{
    public function __construct()
    {
        $this->route = 'jenis';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $nama = $request->nama;
        $result = [];

        try {
            $message = 'Data Item';
            $query = $this->model();
            $query = $query->where('nama', 'like', '%' . $nama . '%')->orderBy('nama');
            $data = $query->get();
            foreach ($data as $key => $value) {
                $result[$key] = [
                    'nama' =>  $value->nama,
                    'slug' =>  $value->slug,
                    'nama_kategori' =>  $value->nama_kategori,
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
        return new Jenis();
    }
}
