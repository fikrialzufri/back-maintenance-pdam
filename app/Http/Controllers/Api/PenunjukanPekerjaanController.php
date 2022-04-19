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
        $this->route = 'penunjukan-pekerjaan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $nomor_pekerjaan = $request->nomor_pekerjaan;
        $status = $request->status;
        $slug = $request->slug;
        $aduan_id = $request->aduan_id;
        $result = [];
        $message = 'List Penunjukan Pekerjaan';
        $rekanan_id = auth()->user()->id_rekanan;

        $query = $this->model();
        if ($nomor_pekerjaan != '') {
            $query = $query->where('nomor_pekerjaan',  $nomor_pekerjaan);
        }
        if ($status != '') {
            $query = $query->where('status',  $status);
        }
        if ($slug != '') {
            $query = $query->where('slug',  $slug);
        }
        if ($aduan_id != '') {
            $query = $query->where('aduan_id',  $aduan_id);
        }
        if (request()->user()->hasRole('rekanan')) {
            $query = $query->where('rekanan_id',  $rekanan_id);
        }

        if ($slug) {
            $data = $query->with('hasAduan')->orderBy('created_at')->first();
            if (!$data) {
                $message = 'Data Penunjukan Pekerjaan Belum Ada';
            } else {
                $result = [
                    'id' =>  $data->id,
                    'nomor_pekerjaan' =>  $data->nomor_pekerjaan,
                    'nomor_pelaksaan_pekerjaan' =>  $data->nomor_pelaksanaan_pekerjaan,
                    'slug' =>  $data->slug,
                    'status' =>  $data->status,
                    'lokasi_aduan' =>  $data->lokasi,
                    'lokasi_pekerjaan' =>  $data->lokasi_pekerjaan,
                    'lat_lang' =>  $data->lat_long,
                    'nama_rekanan' =>  $data->rekanan,
                    'jenis_aduan' =>  $data->jenis_aduan,
                    'atas_nama' =>  $data->atas_nama,
                    'sumber_informasi' =>  $data->sumber_informasi,
                    'keterangan_aduan' =>  $data->keterangan_aduan,
                    'created_at' =>  $data->created_at,
                    'status_mobile' =>  $data->status_mobile,
                ];
            }
        } else {
            $data = $query->orderBy('created_at')->get();
            if (count($data) == 0) {
                $message = 'Data Penunjukan Pekerjaan Belum Ada';
            }
            foreach ($data as $key => $value) {
                $result[$key] = [
                    'id' =>  $value->id,
                    'nomor_pekerjaan' =>  $value->nomor_pekerjaan,
                    'slug' =>  $value->slug,
                    'lokasi_aduan' =>  $value->lokasi,
                    'lokasi_pekerjaan' =>  $value->lokasi_pekerjaan,
                    'status' =>  $value->status,
                    'created_at' =>  $value->created_at,
                    'status_mobile' =>  $value->status_mobile,
                ];
            }
        }

        return $this->sendResponse($result, $message, 200);
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
        $message = 'Gagal Menyimpan Penunjukan Pekerjaan';
        $aduan_id = $request->aduan_id;

        $dataPenunjukanPerkerjaan = $this->model()->count();
        if ($dataPenunjukanPerkerjaan >= 1) {
            $no = str_pad($dataPenunjukanPerkerjaan + 1, 4, "0", STR_PAD_LEFT);
            $nomor_pekerjaan =  $no . "/" . "SPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        } else {
            $no = str_pad(1, 4, "0", STR_PAD_LEFT);
            $nomor_pekerjaan =  $no . "/" . "SPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        }
        $aduan = $this->model()->where('aduan_id', $aduan_id)->first();

        if ($aduan) {
            $message = "Data Aduan sudah dikerjakan";
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '409'
            ];
            return $this->sendError($response, $message, 409);
        }
        DB::beginTransaction();
        try {
            DB::commit();
            $data = $this->model();
            $data->nomor_pekerjaan = $nomor_pekerjaan;
            $data->aduan_id = $aduan_id;
            $data->rekanan_id = $request->rekanan_id;
            $data->user_id = auth()->user()->id;
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
    public function update(Request $request)
    {
        DB::beginTransaction();
        $message = 'Gagal Mengubah Penunjukan Pekerjaan';
        $status = $request->status;
        $slug = $request->slug;
        $user_id = auth()->user()->id;
        try {
            DB::commit();
            $data = $this->model()->whereSlug($slug)->first();
            $data->status = $status;
            $data->save();

            $keterangan = [
                'keterangan' => $status,
            ];

            $syncData  = array_combine($user_id, $keterangan);
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
