<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PelaksanaanPekerjaan;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class TagihanController extends Controller
{
    public function __construct()
    {
        $this->route = 'tagihan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $nomor_tagihan = $request->nomor_tagihan;
        $status = $request->status;
        $slug = $request->slug;
        $result = [];
        $message = 'List Tagihan';
        $rekanan_id = auth()->user()->id_rekanan;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $query = $this->model();
        if ($nomor_tagihan != '') {
            $query = $query->where('nomor_tagihan',  $nomor_tagihan);
        }
        if ($status != '') {
            $query = $query->where('status',  $status);
        }
        if ($slug != '') {
            $query = $query->where('slug',  $slug);
        }
        if (request()->user()->hasRole('rekanan')) {
            $query = $query->where('rekanan_id',  $rekanan_id);
        }
        if (request()->user()->hasRole('staf-pengawas')) {
            $rekanan_id = auth()->user()->karyawan_list_rekanan;
            $query = $query->whereIn('rekanan_id',  $rekanan_id);
        }

        if ($start_date || $end_date) {
            $start = Carbon::parse($start_date)->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($end_date)->format('Y-m-d') . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        }

        try {
            $data = $query->orderBy('status', 'ASC')->orderBy('created_at')->get();
            if (count($data) == 0) {
                $message = 'Data List Tagihan Belum Ada';
            }
            foreach ($data as $key => $value) {
                $result[$key] = [
                    'id' =>  $value->id,
                    'nomor_tagihan' =>  $value->nomor_tagihan,
                    'slug' =>  $value->slug,
                    'status' =>  $value->status,
                    'created_at' =>  $value->created_at,
                    'status_mobile' =>  $value->status_mobile,
                ];
            }
            return $this->sendResponse($result, $message, 200);
        } catch (\Throwable $th) {
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
        $message = 'Gagal Menyimpan Menyimpan Tagihan';
        $rekanan_id = auth()->user()->id_rekanan;

        $tagihan = $this->model()->count();
        if ($tagihan >= 1) {
            $no = str_pad($tagihan + 1, 4, "0", STR_PAD_LEFT);
            $nomor_tagihan =  $no . "/" . "BAPP-KJB/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        } else {
            $no = str_pad(1, 4, "0", STR_PAD_LEFT);
            $nomor_tagihan =  $no . "/" . "BAPP-KJB/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        }

        DB::beginTransaction();
        try {
            DB::commit();
            $data = $this->model();
            $data->nomor_tagihan = $nomor_tagihan;
            $data->rekanan_id = $rekanan_id;
            $data->user_id = auth()->user()->id;
            $data->status = 'draft';
            $data->save();

            $slug = $request->slug;
            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $slug)->pluck('id')->toArray();
            $PelaksanaanPekerjaan = PelaksanaanPekerjaan::whereIn('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->pluck('id')->toArray();

            $data->hasPelaksanaanPekerjaan()->sync($PelaksanaanPekerjaan);


            $message = 'Berhasil Menyimpan Tagihan';
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
        return new Tagihan();
    }
}