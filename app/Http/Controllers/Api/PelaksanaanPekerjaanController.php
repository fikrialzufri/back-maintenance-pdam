<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PelaksanaanPekerjaanController extends Controller
{
    public function __construct()
    {
        $this->route = 'pelaksanaan-pekerjaan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $nomor_pelaksanaan_pekerjaan = $request->nomor_pelaksanaan_pekerjaan;
        $status = $request->status;
        $aduan_id = $request->aduan_id;
        $result = [];
        $message = 'Detail Pelaksanaan Pekerjaan';
        $rekanan_id = auth()->user()->id_rekanan;
        try {
            $message = 'Data Pelaksanaan Pekerjaan';

            $query = $this->model();
            if ($nomor_pelaksanaan_pekerjaan != '') {
                $query = $query->where('nomor_pelaksanaan_pekerjaan',  $nomor_pelaksanaan_pekerjaan);
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
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
        $user_id = auth()->user()->id;
        $rekanan_id = auth()->user()->id_rekanan;
        $dataPelaksanaanPekerjaan = $this->model()->count();
        if ($dataPelaksanaanPekerjaan >= 1) {
            $no = str_pad($dataPelaksanaanPekerjaan + 1, 4, "0", STR_PAD_LEFT);
            $nomor_pelaksanaan_pekerjaan =  $no . "/" . "PPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        } else {
            $no = str_pad(1, 4, "0", STR_PAD_LEFT);
            $nomor_pelaksanaan_pekerjaan =  $no . "/" . "PPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        }

        $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $request->penunjukan_pekerjaan_id)->first();

        try {
            DB::commit();
            $data = $this->model();
            $data->nomor_pelaksanaan_pekerjaan = $nomor_pelaksanaan_pekerjaan;
            $data->penunjukan_pekerjaan_id = $penunjukanPekerjaan->id;
            $data->rekanan_id = $rekanan_id;
            $data->user_id = $user_id;
            $data->status = 'draft';
            $data->save();

            $penunjukanPekerjaan->status = 'proses';
            $penunjukanPekerjaan->save();

            $message = 'Berhasil Menyimpan Pelaksanaan Pekerjaan';
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function proses(Request $request, $slug)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
        try {
            DB::commit();
            $data = $this->model()->where('slug', $slug)->first();
            $data->lokasi = $request->lokasi;
            $data->lat_long = $request->lat_long;
            $data->user_id = $request->user_id;
            $data->tanggal_mulai = Carbon::now();
            $data->status = 'proses';
            $data->save();

            // TODO
            // Belum Nyimpan Foto
            if (isset($request->foto)) {
                $names = [];
                if ($request->hasFile('foto')) {
                    foreach ($request->file('foto') as $image) {
                        $destinationPath = 'images/';
                        $filename = $image->getClientOriginalName();
                        $image->move($destinationPath, $filename);
                        array_push($names, $filename);
                    }
                    $media = new Media();
                    $media->file = json_encode($names);
                    $media->nama = 'Proses Pelaksanan Kerja';
                    $media->modul = 'PelaksananKerja';
                    $media->save();
                }
            }

            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $data->penunjukan_pekerjaan_id)->first();
            $penunjukanPekerjaan->status = 'selesai';
            $penunjukanPekerjaan->save();

            // update histori user
            $keterangan = [
                'keterangan' => 'proses',
            ];

            $syncData  = array_combine($data->id, $keterangan);

            $data->hasItem()->sync($syncData);

            $message = 'Berhasil Menyimpan Pelaksanaan Pekerjaan';
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function prosesAkhir(Request $request, $slug)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
        $status = 'proses-akhir';
        try {
            DB::commit();
            $data = $this->model()->where('slug', $slug)->first();
            $data->status = $status;
            $data->save();

            if (isset($request->id_item)) {
                $item = [];
                $keterangan = [];
                $listitem = [];
                foreach ($request->qty as $index => $value) {
                    $item[$index] = $request->id_item[$index];
                    $keterangan[$index] = $request->keterangan[$index];
                    $listitem[$index] = [
                        'keterangan' => $keterangan[$index],
                        'qty' => $value
                    ];
                }
                $syncData  = array_combine($item, $listitem);

                $data->hasItem()->sync($syncData);
            }

            // Todo
            // simpan foto
            if (isset($request->foto)) {
                $names = [];
                if ($request->hasFile('foto')) {
                    foreach ($request->file('foto') as $image) {
                        $destinationPath = 'images/';
                        $filename = $image->getClientOriginalName();
                        $image->move($destinationPath, $filename);
                        array_push($names, $filename);
                    }
                    $media = new Media();
                    $media->file = json_encode($names);
                    $media->nama = 'Bahan Pelaksanan Kerja';
                    $media->modul = 'pelaksanan_kerja';
                    $media->save();
                }
            }

            // update histori user
            $keterangan = [
                'keterangan' => $status,
            ];

            $syncData  = array_combine($data->id, $keterangan);

            $data->hasItem()->sync($syncData);

            $message = 'Berhasil Menyimpan Pelaksanaan Pekerjaan';
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function selesai(Request $request, $slug)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
        $status = 'selesai';
        $keterangan = $request->keterangan;
        try {
            DB::commit();
            $data = $this->model()->where('slug', $slug)->first();
            $data->status = $status;
            $data->tanggal_selesai = Carbon::now();
            $data->keterangan = $keterangan;
            $data->save();

            // Todo
            // simpan foto
            if (isset($request->foto)) {
                $names = [];
                if ($request->hasFile('foto')) {
                    foreach ($request->file('foto') as $image) {
                        $destinationPath = 'images/';
                        $filename = $image->getClientOriginalName();
                        $image->move($destinationPath, $filename);
                        array_push($names, $filename);
                    }
                    $media = new Media();
                    $media->file = json_encode($names);
                    $media->nama = 'Penyelesaian Pelaksanan Kerja';
                    $media->modul = 'pelaksanan_kerja';
                    $media->save();
                }
            }

            // update histori user
            $keterangan = [
                'keterangan' => $status,
            ];

            $syncData  = array_combine($data->id, $keterangan);

            $data->hasItem()->sync($syncData);

            $message = 'Berhasil Menyimpan Pelaksanaan Pekerjaan';
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
        return new PelaksanaanPekerjaan();
    }
}
