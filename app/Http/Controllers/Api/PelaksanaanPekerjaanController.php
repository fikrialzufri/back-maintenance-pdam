<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aduan;
use App\Models\GalianPekerjaan;
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
            $data = $query->orderBy('status', 'ASC')->orderBy('created_at')->get();
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
     *
     * proses terima pekerjaan
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
            $data->status = 'diterima';
            $data->save();

            $penunjukanPekerjaan->status = 'proses';
            $penunjukanPekerjaan->save();


            $user[$user_id] = [
                'keterangan' => 'proses',
            ];
            $penunjukanPekerjaan->hasUserMany()->sync($user);

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
     *
     * update tag lokasi pekerjaan
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function proses(Request $request)
    {
        try {
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

            $user[$user_id] = [
                'keterangan' => 'proses',
            ];
            $data->hasUserMany()->sync($user);

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
     *
     * proses update bahan pekerjaan
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function prosesAkhir(Request $request)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
        $status = 'proses-akhir';
        $slug = $request->slug;
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
            $data->save();
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
     *
     * proses selesai pekerjaan
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
        $user = [];
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
            $data->tanggal_selesai = Carbon::now();
            $data->keterangan = $keterangan;
            $data->save();

            // update histori user
            $user[$user_id] = [
                'keterangan' => $status,
            ];

            $data->hasUserMany()->sync($user);

            $penunjukanPekerjaan->status = $status;
            $penunjukanPekerjaan->save();
            $penunjukanPekerjaan->hasUserMany()->sync($user);

            $aduan = Aduan::find($penunjukanPekerjaan->aduan_id);
            $aduan->status = $status;
            $aduan->save();

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
        $message = 'Gagal Mengubah Pekerjaan';
        $status = 'disetujui';
        $slug = $request->slug;
        $user_id = auth()->user()->id;

        DB::commit();
        $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $slug)->first();
        $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->first();
        $listRekanan = [];
        if ($data->status == 'disetujui') {
            $message = "Pekerjaan sudah disetujui";
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '409'
            ];
            return $this->sendError($response, $message, 409);
        }

        try {
            if (request()->user()->hasRole('staf-pengawas')) {
                $listRekanan = auth()->user()->karyawan_list_rekanan->toArray();
                $rekanan_id = $penunjukanPekerjaan->rekanan_id;
                if (in_array($rekanan_id, $listRekanan)) {
                    $data->status = $status;
                    $data->save();


                    $user[$user_id] = [
                        'keterangan' => $status,
                    ];

                    $data->hasUserMany()->sync($user);

                    $penunjukanPekerjaan->status = $status;
                    $penunjukanPekerjaan->save();
                    $penunjukanPekerjaan->hasUserMany()->sync($user);

                    $aduan = Aduan::find($penunjukanPekerjaan->aduan_id);
                    $aduan->status = $status;
                    $aduan->save();

                    $message = 'Berhasil Mengubah Pekerjaan';
                }
            }
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
        $message = 'Gagal Menyimpan Item Pekerjaan';
        try {
            $slug = $request->slug;
            $nama = $request->nama;
            $keterangan = $request->keterangan;
            $jumlah = $request->jumlah;
            $id_barang = $request->id_barang;
            $listitem = [];
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
                $item->harga = 0;
                $item->save();
            } else {
                $item = Item::find($id_barang);
            }
            $listitem[$item->id] = [
                'keterangan' => $keterangan,
                'harga' => $item->harga,
                'qty' => $jumlah
            ];

            $data->hasItem()->attach($listitem);
            $result = [];
            $message = 'Berhasil Menyimpan Item Pekerjaan';
            return $this->sendResponse($result, $message, 200);
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

    public function itemRemove(Request $request)
    {
        DB::beginTransaction();

        try {
            $message = 'Gagal Hapus Penunjukan Pekerjaan';

            // request
            $slug = $request->slug;
            $id_barang = $request->id_barang;

            DB::commit();
            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $slug)->first();
            $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->with('hasItem')->first();

            $jenis = Jenis::where('slug', 'barang-baru')->first();

            $item = Item::find($id_barang);
            if ($item) {
                if ($item->jenis_id == $jenis->id) {
                    $item->delete();
                }

                $data->hasItem()->detach($item->id);

                $result = [];

                $message = 'Berhasil Hapus Item Pekerjaan';
                return $this->sendResponse($result, $message, 200);
            } else {
                $message = 'Id Item tidak ada';
                $response = [
                    'success' => false,
                    'message' => $message,
                    'code' => '400'
                ];
                return $this->sendError($response, [], 400);
            }
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


    public function galian(Request $request)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Galian Pekerjaan';
        $user_id = auth()->user()->id;
        try {
            $slug = $request->slug;
            $panjang = $request->panjang;
            $lebar = $request->lebar;
            $dalam = $request->dalam;
            $bongkaran = $request->bongkaran;
            $keterangan = $request->keterangan;
            DB::commit();
            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $slug)->first();
            $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->with('hasItem')->first();

            $gajian = new GalianPekerjaan;
            $gajian->panjang = $panjang;
            $gajian->lebar = $lebar;
            $gajian->dalam = $dalam;
            $gajian->bongkaran = $bongkaran;
            $gajian->keterangan = $keterangan;
            $gajian->user_id = $user_id;
            $gajian->pelaksanaan_pekerjaan_id = $data->id;
            $gajian->save();

            $result = [];
            $message = 'Berhasil Menyimpan Galian Pekerjaan';
            return $this->sendResponse($result, $message, 200);
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

    public function galianRemove(Request $request)
    {
        DB::beginTransaction();
        $result = [];
        try {
            $message = 'Gagal Hapus Penunjukan Pekerjaan';

            $slug = $request->slug;
            $id_galian = $request->id_galian;

            DB::commit();
            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $slug)->first();
            $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->with('hasItem')->first();

            if ($data) {
                $GalianPekerjaan = GalianPekerjaan::find($id_galian);
                if ($GalianPekerjaan) {

                    $GalianPekerjaan->delete();

                    $message = 'Berhasil Hapus Galian Pekerjaan';
                    return $this->sendResponse($result, $message, 200);
                } else {
                    $message = 'Id Galian tidak ada';
                    $response = [
                        'success' => false,
                        'message' => $message,
                        'code' => '400'
                    ];
                    return $this->sendError($response, [], 400);
                }
            } else {
                $message = 'Pekerjaan tidak ada';
                $response = [
                    'success' => false,
                    'message' => $message,
                    'code' => '400'
                ];
                return $this->sendError($response, [], 404);
            }
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
