<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rekanan;

class RekananController extends Controller
{
    public function __construct()
    {
        $this->route = 'rekanan';
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
            $message = 'Data Rekanan';

            $query = $this->model();
            $query = $query->where('nama', 'like', '%' . $nama . '%');

            $result = $query->get();
            if (count($result) == 0) {
                $message = 'Data Rekanan Belum Ada';
            }
            return $this->sendResponse($result, $message, 200);
        } catch (\Throwable $th) {
            $message = 'Detail Rekanan';
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
        return new Rekanan();
    }
}
