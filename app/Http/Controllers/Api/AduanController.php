<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\JenisAduan;
use App\Models\Aduan;
use Str;

class AduanController extends Controller
{
    public function __construct()
    {
        $this->route = 'aduan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $nomor_aduan = $request->nomor_aduan;
        $jenis = $request->jenis;
        $result = [];

        try {
            $message = 'Data Aduan';

            $query = $this->model();
            $query = $query->where('nomor_aduan',  $nomor_aduan)->orderBy('created_at');
            $data = $query->get();
            // foreach ($data as $key => $value) {
            //     $result[$key] = [
            //         'nama' =>  $value->nama,
            //         'slug' =>  $value->slug,
            //         'satuan' =>  $value->satuan,
            //         'jenis' =>  $value->jenis,
            //     ];
            // }
            if (count($result) == 0) {
                $message = 'Data Item Belum Ada';
            }
            return $this->sendResponse($data, $message, 200);
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
        return new Aduan();
    }
}
