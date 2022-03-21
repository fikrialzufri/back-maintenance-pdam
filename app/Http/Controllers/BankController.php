<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;

class BankController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'bank';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'nama',
                'alias'    => 'Nama bank',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama bank',
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
                'alias'    => 'Nama Bank',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'metode_pembayaran_id',
                'input'    => 'combo',
                'alias'    => 'Metode Pembayaran',
                'validasi'    => ['required', 'min:1'],
                'value' => $this->combobox('metodepembayaran'),
            ],

        ];
    }

    public function model()
    {
        return new Bank();
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

        $data  = [
            'id' => $model->id,
            'nama' => ucwords($model->nama),
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
    public function metode($id)
    {
        $data = $this->model()->where('metode_pembayaran_id', $id)->get();
        $message = "Data ada";

        $result = [
            'data' => $data,
            'message' =>  $message,
        ];
        return response()->json($result);
    }
}
