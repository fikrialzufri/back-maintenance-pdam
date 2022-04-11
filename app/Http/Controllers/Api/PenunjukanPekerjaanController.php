<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aduan;
use App\Models\PenunjukanPekerjaan;
use Illuminate\Http\Request;
use DB;

class PenunjukanPekerjaanController extends Controller
{
    public function __construct()
    {
        // $this->route = 'penunjukan-pekerjaan';
        // $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        // $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $nomor_pekerjaan = $request->nomor_pekerjaan;
        $status = $request->status;
        $aduan_id = $request->aduan_id;
        $result = [];
        $message = 'Detail Penunjukan Pekerjaan';
        $rekanan_id = auth()->user()->id_rekanan;
        $message = 'Data Penunjukan Pekerjaan';

        $query = $this->model();
        if ($nomor_pekerjaan != '') {
            $query = $query->where('nomor_pekerjaan',  $nomor_pekerjaan);
        }
        if ($status != '') {
            $query = $query->where('status',  $status);
        }
        if ($aduan_id != '') {
            $query = $query->where('aduan_id',  $aduan_id);
        }
        if ($rekanan_id != '') {
            $query = $query->where('rekanan_id',  $rekanan_id);
        }
        $data = $query->orderBy('created_at')->get();
        if (count($result) == 0) {
            $message = 'Data Aduan Belum Ada';
        }
        return $this->sendResponse($data, $message, 200);
        try { } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => $message,
            ];
            return $this->sendError($response, $th, 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Penunjukan Pekerjaan';
        $aduan_id = $request->aduan_id;

        $dataPenunjukanPerkerjaan = $this->model()->count();
        if ($dataPenunjukanPerkerjaan >= 1) {
            $no = str_pad($dataPenunjukanPerkerjaan + 1, 4, "0", STR_PAD_LEFT);
            $nomor_pekerjaan =  $no . "/" . "PPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        } else {
            $no = str_pad(1, 4, "0", STR_PAD_LEFT);
            $nomor_pekerjaan =  $no . "/" . "PPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        }
        try {
            DB::commit();
            $data = $this->model();
            $data->nomor_pekerjaan = $nomor_pekerjaan;
            $data->aduan_id = $aduan_id;
            $data->rekanan_id = $request->rekanan_id;
            $data->user_id = $request->user_id;
            $data->status = 'draft';
            $data->save();

            $aduan = Aduan::find($aduan_id);
            $aduan->status = 'proses';
            $aduan->save();

            $message = 'Berhasil Menyimpan Penunjukan Pekerjaan';
            return $this->sendResponse($data, $message, 200);
        } catch (\Throwable $th) {
            DB::rollback();
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $message = 'Gagal Mengubah Penunjukan Pekerjaan';
        $status = $request->status;
        $user_id = auth()->user()->id;
        try {
            DB::commit();
            $data = $this->model()->find($id);
            $data->status = $status;
            $data->save();

            $syncData  = array_combine($user_id, $status);
            $data->hasUserMany()->sync($syncData);

            $message = 'Berhasil Mengubah Penunjukan Pekerjaan';
            return $this->sendResponse($data, $message, 200);
        } catch (\Throwable $th) {
            DB::rollback();
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }


    public function model()
    {
        return new PenunjukanPekerjaan();
    }
}
