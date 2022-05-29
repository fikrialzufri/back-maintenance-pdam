<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aduan;
use App\Models\GalianPekerjaan;
use App\Models\Item;
use App\Models\Jabatan;
use App\Models\Jenis;
use App\Models\Karyawan;
use App\Models\Kategori;
use App\Models\PelaksanaanPekerjaan;
use App\Models\PenunjukanPekerjaan;
use App\Models\Satuan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;

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
            $nomor_pekerjaan = $request->nomor_pekerjaan;
            $status = $request->status;
            $slug = $request->slug;
            $tanggal_mulai = $request->tanggal_mulai;
            $tanggal_selesai = $request->tanggal_selesai;
            $aduan_id = $request->aduan_id;
            $result = [];
            $message = 'List Penunjukan Pekerjaan';
            $rekanan_id = auth()->user()->id_rekanan;

            $query = $this->model();
            if ($nomor_pekerjaan != '') {
                $query = $query->where('nomor_pelaksanaan_pekerjaan',  $nomor_pelaksanaan_pekerjaan);
            }
            if ($status != '') {
                $query = $query->where('status',  $status);
            }
            if ($slug != '') {
                $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $request->slug)->first();
                $query = $query->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id);
            }
            if ($aduan_id != '') {
                $query = $query->where('aduan_id',  $aduan_id);
            }
            if (request()->user()->hasRole('rekanan')) {
                $query = $query->where('rekanan_id',  $rekanan_id);
            }
            if (request()->user()->hasRole('staf-pengawas')) {
                $rekanan_id = auth()->user()->karyawan_list_rekanan;
                $query = $query->whereIn('rekanan_id',  $rekanan_id);
            }
            if ($tanggal_mulai || $tanggal_selesai) {
                $start = Carbon::parse($tanggal_mulai)->format('Y-m-d') . ' 00:00:01';
                $end = Carbon::parse($tanggal_selesai)->format('Y-m-d') . ' 23:59:59';
                $query = $query->whereBetween('tanggal_selesai', [$start, $end]);
            }

            if ($slug) {
                $data = $query->with('hasAduan')->orderBy('status', 'ASC')->orderBy('created_at')->first();
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
                        'lat_long' =>  $data->lat_long,
                        'nama_rekanan' =>  $data->rekanan,
                        'foto_lokasi' =>  $data->foto_lokasi,
                        'foto_bahan' =>  $data->foto_bahan,
                        'foto_penyelesaian' =>  $data->foto_penyelesaian,
                        'galian_pekerjaan' =>  $data->galian_pekerjaan,
                        'jenis_aduan' =>  $data->jenis_aduan,
                        'atas_nama' =>  $data->atas_nama,
                        'item' =>  $data->list_item,
                        'sumber_informasi' =>  $data->sumber_informasi,
                        'keterangan_aduan' =>  $data->keterangan_aduan,
                        'keterangan_penyelesaian' =>  $data->keterangan_penyelesaian,
                        'created_at' =>  $data->created_at,
                        'status_mobile' =>  $data->status_mobile,
                    ];
                }
            } else {
                $data = $query->orderBy('status', 'ASC')->orderBy('created_at')->get();
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
            $data->tanggal_mulai = Carbon::now();
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
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
        try {
            DB::commit();
            $slug = $request->slug;
            $lokasi = $request->lokasi;
            $keterangan = $request->keterangan;
            $user_id = auth()->user()->id;
            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug',  $slug)->first();
            $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->first();

            // if ($data->status == 'selesai') {
            //     $message = "Pekerjaan sudah selesai";
            //     $response = [
            //         'success' => false,
            //         'message' => $message,
            //         'code' => '409'
            //     ];
            //     return $this->sendError($response, $message, 409);
            // }
            $data->lokasi = $lokasi;
            $data->lat_long = $request->lat_long;
            $data->keterangan_barang = $request->keterangan_barang;
            $data->user_id = $user_id;
            $data->tanggal_mulai = Carbon::now();
            $data->status = 'proses';
            $data->save();

            $user[$user_id] = [
                'keterangan' => 'proses',
            ];
            $data->hasUserMany()->sync($user);

            $message = 'Berhasil Menyimpan Pelaksanaan Pekerjaan';
            return $this->sendResponse($data, $message, 200, 0);
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
     * proses update modul bahan pekerjaan
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
            $data->keterangan_barang = $request->keterangan;
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
        // list jabatan
        $listJabatan = Jabatan::whereSlug('manager-distribusi')->orWhere('slug', 'staf-perencanaan')->orWhere('slug', 'asisten-manager-pengawas-fisik')->orWhere('slug', 'direktur-teknik')->get()->pluck('id')->toArray();

        // list karyawan bedasarkan jabatan
        $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get();

        try {
            DB::commit();
            $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
            $status = 'selesai';
            $slug = $request->slug;
            $user_id = auth()->user()->id;
            $keterangan = $request->keterangan;
            $user = [];
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

            $stafPengawas = Auth::user()->hasRekanan->hasKaryawan;
            $fotolokasi = $penunjukanPekerjaan->foto_penyelesaian;

            $kategoriDokumentasi = Kategori::whereSlug('dokumentasi')->first();
            if ($kategoriDokumentasi) {
                $jenisDokumentasi = Jenis::where('kategori_id', $kategoriDokumentasi->id)->get()->pluck('id');
                $listDokumentasi = Item::whereIn('jenis_id', $jenisDokumentasi)->first();
                if (count($fotolokasi) > 0) {
                    if (isset($listDokumentasi)) {

                        if (now()->format('H:i') >= '18:01') {
                            $harga = $listDokumentasi->harga_malam;
                        } else {
                            $harga = $listDokumentasi->harga;
                        }
                        $total = 1 * $harga;

                        $listitem[$listDokumentasi->id] = [
                            'keterangan' => '',
                            'harga' => $harga,
                            'qty' => 1,
                            'total' => $total,
                        ];

                        $data->hasItem()->attach($listitem);
                    }
                }
            }

            $title = "Pengerjaan Telah selesai";
            $body = "Dengan nomor SPK : " . $penunjukanPekerjaan->nomor_pekerjaan . " telah selesai";
            $modul = "pelaksanaan-pekerjaan";

            // notif ke staf pengawas
            foreach ($stafPengawas as $pengawas) {
                $this->notification($data->id, $aduan->slug, $title, $body, $modul, auth()->user()->id, $pengawas->user_id);
            }

            // notif ke karyawan bedasarkan jabatan
            if ($listKaryawan) {
                foreach (collect($listKaryawan) as $i => $kr) {
                    $this->notification($data->id, $aduan->slug, $title, $body, $modul, auth()->user()->id, $kr->user_id);
                }
            }



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


        try {
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
            if (request()->user()->hasRole('staf-pengawas')) {
                $listRekanan = auth()->user()->karyawan_list_rekanan->toArray();
                $rekanan_id = $penunjukanPekerjaan->rekanan_id;
                if (in_array($rekanan_id, $listRekanan)) {
                    $data->status = $status;
                    $data->save();


                    $user[$user_id] = [
                        'keterangan' => $status,
                    ];

                    $data->hasUserMany()->attach($user);

                    $penunjukanPekerjaan->status = $status;
                    $penunjukanPekerjaan->save();
                    $penunjukanPekerjaan->hasUserMany()->attach($user);

                    $aduan = Aduan::find($penunjukanPekerjaan->aduan_id);
                    $aduan->status = $status;
                    $aduan->save();

                    $title = "Pengerjaan Telah Disetujui pengawas";
                    $body = "Dengan nomor SPK : " . $penunjukanPekerjaan->nomor_pekerjaan . " telah Disetujui";
                    $modul = "pelaksaan-pekerjaan";

                    $this->notification($penunjukanPekerjaan->id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $data->user_id);


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
            DB::commit();

            $slug = $request->slug;
            $keterangan = $request->keterangan;
            $jumlah = $request->jumlah;

            $id_barang = $request->id_barang;
            $listitem = [];
            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $slug)->first();
            $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->with('hasItem')->first();
            $harga = 0;
            if ($id_barang != '') {
                $item = Item::find($id_barang);
                if (now()->format('H:i') >= '18:01') {
                    $harga = $item->harga;
                } else {
                    $harga = $item->harga_malam;
                }
                $total = $jumlah * $harga;
                $listitem[$item->id] = [
                    'keterangan' => $keterangan,
                    'harga' => $harga,
                    'qty' => $jumlah,
                    'total' => $total,
                ];

                $data->hasItem()->attach($listitem);
                $message = 'Berhasil Menyimpan Item Pekerjaan';
                return $this->sendResponse($data, $message, 200);
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
                if ($item->jenis_id == $jenis->id && $item->hapus == 'ya') {
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
        $slug = $request->slug;
        $panjang = $request->panjang;
        $lebar = $request->lebar;
        $dalam = $request->dalam;
        $item = $request->id_item;

        $dataItem = Item::find($item);

        if (now()->format('H:i') >= '18:01') {
            $harga_item = $dataItem->harga_malam;
            $harga = 'malam';
        } else {
            $harga = 'siang';
            $harga_item = $dataItem->harga;
        }


        $total = ($panjang * $lebar * $dalam) * $harga_item;
        $keterangan = $request->keterangan;

        try {
            $penunjukanPekerjaan = PenunjukanPekerjaan::where('slug', $slug)->first();
            $data = $this->model()->where('penunjukan_pekerjaan_id', $penunjukanPekerjaan->id)->with('hasItem')->first();

            DB::commit();
            $gajian = GalianPekerjaan::where('pelaksanaan_pekerjaan_id', $data->id)->where('item_id',  $item)->first();
            if (empty($gajian)) {
                $gajian = new GalianPekerjaan;
            }
            $gajian->panjang = $panjang;
            $gajian->lebar = $lebar;
            $gajian->dalam = $dalam;
            $gajian->keterangan = $keterangan;
            $gajian->harga = $harga;
            $gajian->item_id = $item;
            $gajian->total = $total;
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
