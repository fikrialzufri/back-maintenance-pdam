<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Pekerjaan;
use App\Http\Resources\PekerjaanDetailResource;
use App\Models\Aduan;

use App\Models\PenunjukanPekerjaan;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

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
        $statusNot = $request->status_not;
        $slug = $request->slug;
        $aduan_id = $request->aduan_id;
        $tagihan = $request->tagihan;
        $result = [];
        $message = 'List Penunjukan Pekerjaan';
        $rekanan_id = auth()->user()->id_rekanan;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $query = $this->model();
        if ($nomor_pekerjaan != '') {
            $query = $query->where('nomor_pekerjaan',  $nomor_pekerjaan);
        }
        if ($status != '') {
            $query = $query->where('status',  $status);
        }
        if ($statusNot != '') {
            $query = $query->where('status', '!=',  $statusNot);
        }
        if ($slug != '') {
            $query = $query->where('slug',  $slug);
        }
        if ($aduan_id != '') {
            $query = $query->where('aduan_id',  $aduan_id);
        }
        if ($tagihan != '') {
            $query = $query->where('tagihan',  $tagihan);
        }
        if (request()->user()->hasRole('rekanan')) {
            $query = $query->where('rekanan_id',  $rekanan_id);
        }
        if (request()->user()->hasRole('staf-pengawas')) {
            $rekanan_id = auth()->user()->karyawan_list_rekanan;
            $query = $query->whereIn('rekanan_id',  $rekanan_id);
        }

        if ($start_date || $end_date) {
            $start = Carbon::parse($start_date)->format('Y-m-d');
            $end = Carbon::parse($end_date)->format('Y-m-d');
            $query = $query->with('hasPelaksanaanPekerjaan')
                ->whereHas('hasPelaksanaanPekerjaan', function ($query) use ($start, $end, $status) {
                    $query->whereBetween('tanggal_selesai', [$start, $end]);
                    // where('status',  $status);
                    if ($status != '') {
                        $x = $query->where('status',  $status);
                    }
                });
        }

        $count = 0;

        if ($slug) {
            $data = $query->with('hasAduan')->orderBy('status', 'desc')->orderBy('created_at', 'desc')->first();
            if (!$data) {
                $message = 'Data Penunjukan Pekerjaan Belum Ada';
            } else {
                $result = new PekerjaanDetailResource($data);
            }
        } else {
            $data = $query->orderBy('status', 'desc')->orderBy('created_at', 'desc')->paginate(10);
            $count =  $query->orderBy('status', 'desc')->orderBy('created_at', 'desc')->count();
            if (count($data) == 0) {
                $message = 'Data Penunjukan Pekerjaan Belum Ada';
            }
            $data = Pekerjaan::collection($data)->response()->getData(true);

            $result =  $data['data'];
        }
        return $this->sendResponse($result, $message, 200, $count);
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

            $notifikasi = Notifikasi::where('modul_id', $data->id)->where('to_user_id', auth()->user()->id)->first();
            $notifikasi->status = 'baca';
            $notifikasi->delete();

            $notifikasi = Notifikasi::where('modul_id', $aduan->id)->where('to_user_id', auth()->user()->id)->first();
            $notifikasi->status = 'baca';
            $notifikasi->delete();

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
