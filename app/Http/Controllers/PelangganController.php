<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Promosi;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PelangganController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'pelanggan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'nama',
                'alias'    => 'Nama Pelanggan',
            ],
            [
                'name'    => 'no_hp',
                'alias'    => 'No HP',
            ],
            [
                'name'    => 'email',
                'alias'    => 'Email',
            ],
            [
                'name'    => 'alamat',
                'alias'    => 'Alamat',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Pelanggan',
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
                'alias'    => 'Nama Pelanggan',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'no_hp',
                'input'    => 'number',
                'alias'    => 'No HP',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'email',
                'alias'    => 'Email',
                'input'    => 'email',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'alamat',
                'input'    => 'textarea',
                'alias'    => 'Alamat',
            ],

        ];
    }

    public function model()
    {
        return new Pelanggan();
    }

    public function pelanggan(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
        ];

        $this->validate(request(), [
            'no_hp' => 'required|unique:pelanggan',
        ], $messages);
        // return $request;

        $no_hp = $request->no_hp;
        $message = "Pelanggan berhasil tersimpan";
        $data = $this->model();
        $data->nama = $request->nama;
        $data->no_hp = $no_hp;
        $data->email = $request->email;
        $data->save();
        $data  = [
            'id' => $data->id,
            'no_hp' => $data->no_hp,
            'nama' => ucwords($data->nama),
        ];
        $result = [
            'data' => $data,
            'message' =>  $message,
        ];
        return response()->json($result);
    }
    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = $this->model()->find($id);
        $message = "Data ada";
        $btn = '';
        $jumlahBonusNota = $model->bonus_nota;
        $promosi = Promosi::where('nota', '=', $jumlahBonusNota)->where('nota', '!=', 0)->first();
        $produk = [];

        if ($promosi) {
            if (isset($promosi->hasProduk)) {
                $btn = 'disable';
                foreach ($promosi->hasProduk as $key => $value) {
                    $produk[$key] = [
                        'id' => $value->id,
                        'nama' => $value->nama,
                        'diskon' => $value->pivot->diskon,
                        'type_diskon' => $value->pivot->type_diskon,

                    ];
                }
            }
        } else {
            $promosi = Promosi::where('mulai', '<=', Carbon::now())->where('selesai', '>=', Carbon::now())->orderBy('created_at', 'asc')->first();
            if ($promosi) {
                foreach ($promosi->hasProduk as $key => $value) {
                    $produk[$key] = [
                        'id' => $value->id,
                        'nama' => $value->nama,
                        'diskon' => $value->pivot->diskon,
                        'type_diskon' => $value->pivot->type_diskon,
                    ];
                }
            }
        }
        $promosi_id = '';
        $nama_promosi = '';
        $keterangan = '';
        if ($promosi) {
            $promosi_id = $promosi->id;
            $nama_promosi = $promosi->nama;
            $keterangan = $promosi->keterangan;
        }
        $data  = [
            'id' => $model->id,
            'nama' => ucwords($model->nama),
            'no_hp' => $model->no_hp,
            'promosi_id' =>  $promosi_id,
            'nama_promosi' =>  $nama_promosi,
            'keterangan' =>  $keterangan,
            'btn' =>  $btn,
            'produk' =>  $produk,
        ];
        $result = [
            'data' => $data,
            'message' =>  $message,
        ];
        return response()->json($result);
    }
}
