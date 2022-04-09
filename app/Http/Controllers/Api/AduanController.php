<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aduan;

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
        $no_aduan = $request->no_aduan;
        $jenis = $request->jenis;
        $result = [];
        $message = 'Detail Aduan';
        $wilayah_id = auth()->user()->karyawan->id_wilayah;
        try {
            $message = 'Data Aduan';

            $query = $this->model();
            if ($no_aduan != '') {
                $query = $query->where('no_aduan',  $no_aduan);
            }
            if ($wilayah_id != '') {
                $query = $query->where('wilayah_id',  $wilayah_id);
            }
            $data = $query->orderBy('created_at')->get();
            if (count($result) == 0) {
                $message = 'Data Aduan Belum Ada';
            }
            return $this->sendResponse($data, $message, 200);
        } catch (\Throwable $th) {
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
