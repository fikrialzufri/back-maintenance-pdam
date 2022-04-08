<?php

namespace  App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisAduan;

class JenisAduanController extends Controller
{
    public function __construct()
    {
        $this->route = 'jenis_aduan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function index()
    {
        $jenis = $this->model()->orderBy('nama')->get();
        $result = [
            'jenis_aduan' => $jenis,
        ];

        $message = 'Jenis Aduan';
        return $this->sendResponse($result, $message, 200);
    }

    public function model()
    {
        return new JenisAduan();
    }
}
