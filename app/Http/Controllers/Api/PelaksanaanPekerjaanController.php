<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Jenis;
use App\Models\PelaksanaanPekerjaan;
use App\Models\PenunjukanPekerjaan;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

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
        $message = 'Data Pelaksanaan Pekerjaan';

        try {
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
            if (request()->user()->hasRole('rekanan')) {
                $query = $query->where('rekanan_id',  $rekanan_id);
            }
            $data = $query->orderBy('created_at')->get();
            if (count($result) == 0) {
                $message = 'Data Pelaksanaan Pekerjaan Belum Ada';
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
        try {
            $dataPelaksanaanPekerjaan = $this->model()->count();
            if ($dataPelaksanaanPekerjaan >= 1) {
                $no = str_pad($dataPelaksanaanPekerjaan + 1, 4, "0", STR_PAD_LEFT);
                $nomor_pelaksanaan_pekerjaan =  $no . "/" . "PPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            } else {
                $no = str_pad(1, 4, "0", STR_PAD_LEFT);
                $nomor_pelaksanaan_pekerjaan =  $no . "/" . "PPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            }
            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $request->slug)->first();
            $pelaksanaan_pekerjaan = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->first();

            if ($pelaksanaan_pekerjaan) {
                $message = "No SPK sudah dikerjakan";
                $response = [
                    'success' => false,
                    'message' => $message,
                    'code' => '409'
                ];
                return $this->sendError($response, $message, 409);
            }

            DB::commit();
            $data = $this->model();
            $data->nomor_pelaksanaan_pekerjaan = $nomor_pelaksanaan_pekerjaan;
            $data->penunjukan_pekerjaan_id = $penunjukanPekerjaan->id;
            $data->rekanan_id = $rekanan_id;
            $data->aduan_id = $penunjukanPekerjaan->aduan_id;
            $data->user_id = $user_id;

            $data->keterangan = $request->keterangan;

            $data->status = 'diterima';
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
    public function proses(Request $request)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
        $slug = $request->slug;
        $lokasi = $request->lokasi;
        $user_id = auth()->user()->id;
        DB::commit();
        $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug',  $slug)->first();
        $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->first();

        if ($data->status == 'selesai') {
            $message = "Pekerjaan sudah selesai";
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '409'
            ];
            return $this->sendError($response, $message, 409);
        }
        $data->lokasi = $lokasi;
        $data->lat_long = $request->lat_long;
        $data->user_id = $user_id;
        $data->tanggal_mulai = Carbon::now();
        $data->status = 'proses';
        $data->save();

        // $media = Media::where('modul',  'pelaksanan_kerja')->where('modul_id', $data->id)->get();
        // if (count($media) == 0) {
        //     if (isset($request->foto)) {
        //         $imageName = [];
        //         if ($request->foto) {
        //             foreach ($request->foto as $index => $image) {

        //                 if (preg_match('/^data:image\/(\w+);base64,/', $image)) {
        //                     $imagebase64 = substr($image, strpos($image, ',') + 1);
        //                     $imagebase64 = base64_decode($imagebase64);
        //                     $imageName = $data->rekanan . $penunjukanPekerjaan->slug . Str::random(5) . '.png';
        //                     Storage::disk('public')->put('proses/' . $imageName, $imagebase64);



        //                     $media = new Media();
        //                     $media->nama = 'Proses Pelaksanan Kerja';
        //                     $media->modul = 'pelaksanan_kerja';
        //                     $media->file = $imageName;
        //                     $media->modul_id = $data->id;
        //                     $media->user_id = $user_id;
        //                     $media->save();
        //                 }
        //             }
        //         }
        //     }
        // }

        $message = 'Berhasil Menyimpan Pelaksanaan Pekerjaan';
        return $this->sendResponse($data, $message, 200);
        try { } catch (\Throwable $th) {
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
    public function prosesAkhir(Request $request)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
        $status = 'proses-akhir';
        $slug = $request->slug;
        $user_id = auth()->user()->id;
        try {
            DB::commit();
            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug',  $slug)->first();
            $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->first();

            if ($data->status == 'selesai') {
                $message = "Pekerjaan sudah selesai";
                $response = [
                    'success' => false,
                    'message' => $message,
                    'code' => '409'
                ];
                return $this->sendError($response, $message, 409);
            }

            $data->status = $status;
            $data->tanggal_selesai = Carbon::now();
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

            // $media = Media::where('modul',  'bahan_perkerjaan')->where('modul_id', $data->id)->get();
            // if (count($media) == 0) {
            //     if (isset($request->foto)) {
            //         $imageName = [];
            //         if ($request->foto) {
            //             foreach ($request->foto as $index => $image) {
            //                 if (preg_match('/^data:image\/(\w+);base64,/', $image)) {
            //                     $imagebase64 = substr($image, strpos($image, ',') + 1);
            //                     $imagebase64 = base64_decode($imagebase64);
            //                     $imageName = $data->rekanan . $penunjukanPekerjaan->slug . Str::random(5) . '.png';
            //                     Storage::disk('public')->put('proses/' . $imageName, $imagebase64);

            //                     $media = new Media();
            //                     $media->nama = 'Proses Akhir Pelaksanan Kerja';
            //                     $media->modul = 'bahan_perkerjaan';
            //                     $media->file = $imageName;
            //                     $media->modul_id = $data->id;
            //                     $media->user_id = $user_id;
            //                     $media->save();
            //                 }
            //             }
            //         }
            //     }
            // }

            $message = 'Berhasil Menyimpan Bahan Pelaksanaan Pekerjaan';
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
    public function selesai(Request $request)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
        $status = 'selesai';
        $slug = $request->slug;
        $user_id = auth()->user()->id;
        $keterangan = $request->keterangan;
        try {
            DB::commit();
            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $slug)->first();
            $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->first();

            if ($data->status == 'selesai') {
                $message = "Pekerjaan sudah selesai";
                $response = [
                    'success' => false,
                    'message' => $message,
                    'code' => '409'
                ];
                return $this->sendError($response, $message, 409);
            }

            $data->status = $status;
            $data->keterangan = $keterangan;
            $data->save();

            // Todo
            // simpan foto
            // $media = Media::where('modul',  'penyelesaian_kerja')->where('modul_id', $data->id)->get();
            // if (count($media) == 0) {
            //     if (isset($request->foto)) {
            //         $imageName = [];
            //         if ($request->foto) {
            //             foreach ($request->foto as $index => $image) {

            //                 if (preg_match('/^data:image\/(\w+);base64,/', $image)) {
            //                     $imagebase64 = substr($image, strpos($image, ',') + 1);
            //                     $imagebase64 = base64_decode($imagebase64);
            //                     $imageName = $data->rekanan . $penunjukanPekerjaan->slug . Str::random(5) . '.png';
            //                     Storage::disk('public')->put('proses/' . $imageName, $imagebase64);



            //                     $media = new Media();
            //                     $media->nama = 'Proses Penyelesaian Pelaksanan Kerja';
            //                     $media->modul = 'penyelesaian_kerja';
            //                     $media->file = $imageName;
            //                     $media->modul_id = $data->id;
            //                     $media->user_id = $user_id;
            //                     $media->save();
            //                 }
            //             }
            //         }
            //     }
            // }

            // update histori user
            $keterangan = [
                'keterangan' => $status,
            ];

            $syncData  = array_combine($data->id, $keterangan);

            $data->hasUserMany()->sync($syncData);
            $penunjukanPekerjaan->status = 'selesai';
            $penunjukanPekerjaan->save();
            $penunjukanPekerjaan->hasUserMany()->sync($syncData);

            $aduan = Aduan::find($data->id_aduan);
            $aduan->status = 'selesai';
            $aduan->save();
            $aduan->hasUserMany()->sync($syncData);

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
            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $request->slug)->first();
            $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->first();
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

    public function item(Request $request)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Penunjukan Pekerjaan';
        $slug = $request->slug;
        $nama = $request->nama;
        $keterangan = $request->keterangan;
        $jumlah = $request->jumlah;
        $id_barang = $request->id_barang;
        DB::commit();
        $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $slug)->first();
        $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->with('hasItem')->first();

        if (!$id_barang) {
            $satuan = Satuan::where('slug', 'pcs')->first();
            $jenis = Jenis::where('slug', 'barang-baru')->first();
            $item = new Item;
            $item->nama = $nama;
            $item->satuan_id = $satuan->id;
            $item->jenis_id = $jenis->id;
            $item->save();
            $item = $item->id;
            $listitem = [
                'keterangan' => $keterangan,
                'harga' => 0,
                'qty' => $jumlah
            ];
        } else {
            $item = Item::find($id_barang);
            $item = $item->id;
            $listitem = [
                'keterangan' => $keterangan,
                'harga' => $item->harga,
                'qty' => $jumlah
            ];
        }


        return $syncData  = array_combine($item, $listitem);
        $data->hasItem()->attach($syncData);

        $message = 'Berhasil Menyimpan Item Pekerjaan';
        return $this->sendResponse($data, $message, 200);
        try { } catch (\Throwable $th) {
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
