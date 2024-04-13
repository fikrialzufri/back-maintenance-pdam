<?php

namespace App\Http\Controllers;

use App\Models\GalianPekerjaan;
use App\Models\Item;
use App\Models\PelaksanaanAdjust;
use App\Models\PelaksanaanPekerjaan;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;

class PelaksanaanPekerjaanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->tambah = 'false';
        $this->route = 'pelaksanaan-pekerjaan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'no_spk',
                'alias'    => 'Nomor SPK',
            ],
            // [
            //     'name'    => 'nomor_pelaksanaan_pekerjaan',
            //     'alias'    => 'Nomor Pekerjaan',
            // ],
            [
                'name'    => 'rekanan',
                'alias'    => 'Nama Rekanana',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nomor_pelaksanaan_pekerjaan',
                'input'    => 'text',
                'alias'    => 'Nama Divisi',
                'value'    => null
            ],
        ];
    }
    public function configForm()
    {

        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Divisi',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'departemen_id',
                'input'    => 'combo',
                'alias'    => 'Departemen',
                'value' => $this->combobox('Departemen'),
                'validasi'    => ['required'],
            ],
        ];
    }

    public function model()
    {
        return new PelaksanaanPekerjaan();
    }

    public function galian(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
        ];

        $this->validate(request(), [
            'panjang' => 'required|string|min:1',
            'lebar' => 'required|string|min:1',
            'dalam' => 'required|string|min:1',
            'item' => 'required',
        ], $messages);

        try {
            $message = 'Data Galian';
            $id = $request->id;
            $panjang = $request->panjang;
            $lebar = $request->lebar;
            $dalam = $request->dalam;
            $item = $request->item;
            $harga = $request->harga;
            $keterangan = $request->keterangan;
            $user_id = auth()->user()->id;

            $dataItem = Item::find($item);
            $harga_item = $dataItem->harga;

            if ($harga === 'malam') {
                $harga_item = $dataItem->harga_malam;
            }

            $total = ($panjang * $lebar * $dalam) * $harga_item;
            $item_id = $dataItem->id;

            $perencanaan = 'false';

            if (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
                $perencanaan = 'true';
            }

            $data = $this->model()->find($id);

            if ($data) {
                $dataGalian = GalianPekerjaan::where('item_id', $item)->where('pelaksanaan_pekerjaan_id', $id)->first();

                if (empty($dataGalian)) {
                    $dataGalian = new GalianPekerjaan;
                }
                $dataGalian->panjang = $panjang;
                $dataGalian->lebar = $lebar;
                $dataGalian->dalam = $dalam;
                $dataGalian->keterangan = $keterangan;
                $dataGalian->harga = $harga;
                $dataGalian->total = $total;
                $dataGalian->item_id = $item_id;
                $dataGalian->user_id = $user_id;
                $dataGalian->pelaksanaan_pekerjaan_id = $data->id;
                $dataGalian->save();

                $result = [
                    'panjang' => $dataGalian->panjang,
                    'lebar' => $dataGalian->lebar,
                    'dalam' => $dataGalian->dalam,
                    'total' => $dataGalian->total,
                    'keterangan' => $dataGalian->keterangan,
                    'pekerjaan' => $dataGalian->pekerjaan,
                    'perencanaan' => $perencanaan,
                    'item_id' => $dataGalian->item_id,
                ];
                return $this->sendResponse($result, $message, 200);
            }
        } catch (\Throwable $th) {
            $message = 'Gagal Menyimpan Pekerjaan';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }


    public function hapusgalian(Request $request)
    {
        $id = $request->id;
        $item_id = $request->item_id;
        try {
            $dataGalian = GalianPekerjaan::where('item_id', $item_id)->where('pelaksanaan_pekerjaan_id', $id)->first();
            if ($dataGalian) {

                $dataGalian->delete();

                $message = 'Berhasil Hapus Galian Pekerjaan';
                return $this->sendResponse([], $message, 200);
            }
        } catch (\Throwable $th) {
            $message = 'Gagal Menyimpan Pekerjaan';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }
    public function hapuspekerjaan(Request $request)
    {
        try {
            $pekerjaan = $request->pekerjaan;
            $dataPelaksanaanAdjust = PelaksanaanAdjust::find($pekerjaan);
            if ($dataPelaksanaanAdjust) {

                $dataPelaksanaanAdjust->delete();

                $message = 'Berhasil Hapus Galian Pekerjaan';
                return $this->sendResponse([], $message, 200);
            }
        } catch (\Throwable $th) {
            $message = 'Gagal Menyimpan Pekerjaan';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }

    public function pekerjaan(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
        ];

        $this->validate(request(), [
            'jumlah' => 'required|string|min:1',
            'item' => 'required',
        ], $messages);

        $message = 'Data Pelaksanaan Adjust';
        $id = $request->id;
        $jumlah = $request->jumlah;
        $harga = $request->harga;
        $item = $request->item;
        $keterangan = $request->keterangan;
        $user_id = auth()->user()->id;

        $dataItem = Item::find($item);
        $harga_item = $dataItem->harga;

        if ($harga === 'malam') {
            $harga_item = $dataItem->harga_malam;
        }

        $total = $jumlah * $harga_item;
        $item_id = $dataItem->id;

        $perencanaan = 'false';

        if (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
            $perencanaan = 'true';
        }

        $data = $this->model()->find($id);

        if ($data) {
            $dataPelaksanaanAdjust = PelaksanaanAdjust::where('item_id', $item)->where('pelaksanaan_pekerjaan_id', $id)->first();

            if (empty($dataPelaksanaanAdjust)) {
                $dataPelaksanaanAdjust = new PelaksanaanAdjust;
            }
            $dataPelaksanaanAdjust->qty = $jumlah;
            $dataPelaksanaanAdjust->keterangan = $keterangan;
            $dataPelaksanaanAdjust->harga = $harga_item;
            $dataPelaksanaanAdjust->total = $total;
            $dataPelaksanaanAdjust->item_id = $item_id;
            $dataPelaksanaanAdjust->user_id = $user_id;
            $dataPelaksanaanAdjust->pelaksanaan_pekerjaan_id = $data->id;
            $dataPelaksanaanAdjust->save();

            $result = [
                'id' => $dataPelaksanaanAdjust->id,
                'jumlah' => $dataPelaksanaanAdjust->qty,
                'total' => $dataPelaksanaanAdjust->total,
                'keterangan' => $dataPelaksanaanAdjust->keterangan,
                'pekerjaan' => $dataPelaksanaanAdjust->pekerjaan,
                'perencanaan' => $perencanaan,
                'item_id' => $dataPelaksanaanAdjust->item_id,
            ];
            return $this->sendResponse($result, $message, 200);
        }
        try {
        } catch (\Throwable $th) {
            $message = 'Gagal Menyimpan Pekerjaan';
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
        $messages = [
            'required' => ':attribute tidak boleh kosong',
        ];

        $this->validate(request(), [
            'jumlah' => 'required|string|min:1',
            'item' => 'required',
        ], $messages);

        $keterangan = $request->keterangan;
        $perencanaan = 'false';
        if (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
            $perencanaan = 'true';
        }

        try {
            $message = 'Data Pekerjaan';
            $id = $request->id;
            $jumlah = $request->jumlah;
            $item = $request->item;
            $harga = $request->harga;

            $dataItem = Item::find($item);
            $harga_item = $dataItem->harga;

            if ($harga === 'malam') {
                $harga_item = $dataItem->harga_malam;
            }

            $total = $jumlah * $harga_item;
            $data = $this->model()->find($id);

            if ($dataItem) {
                $item_id = $dataItem->id;
                $total = $jumlah * $harga_item;

                $existItem = $data->hasItem()->find($item_id);
                if (!empty($existItem)) {
                    $existItem->pivot->qty = $jumlah;
                    $existItem->pivot->harga = $harga_item;
                    $existItem->pivot->total = $total;
                    $existItem->pivot->keterangan = $keterangan;
                    $existItem->pivot->save();
                } else {
                    $listitem[$item_id] = [
                        'keterangan' => $keterangan,
                        'harga' => $harga_item,
                        'qty' => $jumlah,
                        'total' => $total,
                    ];
                    $data->hasItem()->attach($listitem);
                    $existItem = $data->hasItem()->find($item_id);
                }

                $result = [
                    'jumlah' => $existItem->pivot->qty,
                    'total' => $existItem->pivot->total,
                    'keterangan' => $existItem->pivot->keterangan,
                    'pekerjaan' => $dataItem->nama,
                    'perencanaan' => $perencanaan,
                    'item_id' => $item_id,
                ];

                $message = 'Berhasil Menyimpan Pekerjaan';
                return $this->sendResponse($result, $message, 200);
            }
        } catch (\Throwable $th) {
            $message = 'Gagal Menyimpan Pekerjaan';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }

    public function hapusitem(Request $request)
    {
        $id = $request->id;
        try {
            $modul = $request->modul;
            $item = $request->item;
            $data = $this->model()->find($id);
            $data->hasItem()->detach($item);

            $message = 'Berhasil menghapus ' . $modul;
            return $this->sendResponse([], $message, 200);
        } catch (\Throwable $th) {
            $message = 'Gagal Menyimpan Pekerjaan';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }

    public function getStatusAduanAttribute()
    {
        $status = "Belum ditunjuk";
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = "Belum dikerjakan";
                } else if ($this->hasPenunjukanPekerjaan->status == 'proses') {
                    $status  = "Sedang dikerjakan";
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = "Selesai dikerjakan";
                } else if ($this->hasPenunjukanPekerjaan->status == 'approve') {
                    $status  = "Approve Asisten Manajer";
                } else if ($this->hasPenunjukanPekerjaan->status == 'approve manajer') {
                    $status  = "Approve Manajer";
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = "Dikoreksi Pengawas";
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi asmen') {
                    $status  = "Dikoreksi Asmen Pengawas";
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = "Disetujui Manajer";
                } else {
                    $status  = $this->hasPenunjukanPekerjaan->status;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderAttribute()
    {
        $status = 5;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 4;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderManajerAttribute()
    {
        $status = 6;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'approve') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'approve manajer') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 6;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 7;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderPengawasAttribute()
    {
        $status = 6;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'approve manajer') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'approve') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 6;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 7;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderAsemPengawasAttribute()
    {
        $status = 5;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi asmen') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'diadjust') {
                    $status  = 6;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 7;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderAsmenAttribute()
    {
        $status = 5;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi asmen') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'diadjust') {
                    $status  = 6;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 7;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderManajerPengawasAttribute()
    {
        $status = 5;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'koreksi asmen') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'koreksi pengawas') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'diadjust') {
                    $status  = 6;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 7;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderAllAttribute()
    {
        $status = 7;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi') {
                    $status  = 1;
                } else if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi') {
                    $status  = 2;
                } else if ($this->hasPenunjukanPekerjaan->status == 'disetujui') {
                    $status  = 3;
                } else if ($this->hasPenunjukanPekerjaan->status == 'selesai') {
                    $status  = 4;
                } else if ($this->hasPenunjukanPekerjaan->status == 'proses') {
                    $status  = 5;
                } else if ($this->hasPenunjukanPekerjaan->status == 'draft') {
                    $status  = 6;
                }
            }
        }
        return $status;
    }
    public function getStatusOrderPerencanaanAttribute()
    {
        $status = 4;
        if ($this->hasPenunjukanPekerjaan) {;
            if ($this->hasPenunjukanPekerjaan->status) {
                if ($this->hasPenunjukanPekerjaan->status == 'dikoreksi' && $this->hasPenunjukanPekerjaan->tagihan = 'tidak') {
                    $status  = 1;
                }
                if ($this->hasPenunjukanPekerjaan->status == 'selesai koreksi' && $this->hasPenunjukanPekerjaan->tagihan = 'tidak') {
                    $status  = 2;
                }
                if ($this->hasPenunjukanPekerjaan->status == 'diadjust' && $this->hasPenunjukanPekerjaan->tagihan = 'tidak') {
                    $status  = 3;
                }
            }
        }
        return $status;
    }
}
