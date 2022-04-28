<?php

namespace App\Http\Controllers;

use App\Models\GalianPekerjaan;
use App\Models\Tagihan;
use App\Traits\CrudTrait;

class TagihanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'tagihan';
        $this->tambah = 'false';
        $this->index = 'tagihan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }


    public function configHeaders()
    {
        return [
            [
                'name'    => 'nomor_tagihan',
                'alias'    => 'Nomor Tagihan',
            ],
            [
                'name'    => 'rekanan',
                'alias'    => 'Nama Rekanan',
            ],
            [
                'name'    => 'tanggal',
                'alias'    => 'Tanggal',
            ],
            [
                'name'    => 'rekanan',
                'alias'    => 'Nama Rekanan',
            ],
            [
                'name'    => 'status',
                'alias'    => 'Status',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nomor_tagihan',
                'input'    => 'text',
                'alias'    => 'Nomor Tagihan',
                'value'    => null
            ],
        ];
    }
    public function configForm()
    {
        return [];
    }

    public function show($slug)
    {
        $query =  Tagihan::whereSlug($slug);
        return $tagihan = $query->with(['hasPelaksanaanPekerjaan' => function ($q) {
            $q->with('hasGalianPekerjaan')->orderBy('created_at', 'asc');
        }])->first();
        $title =  "Proses Tagihan Nomor :" . $tagihan->nomor_tagihan;
        $action = route('tagihan.store', $tagihan->id);
        $galianPekerjaan = [];

        foreach ($tagihan as $key => $value) {
            $galianPekerjaan[$key] = [
                'id' => $value->hasGalianPekerjaan->panjang,
                'id' => $value->hasGalianPekerjaan->lebar,
                'id' => $value->hasGalianPekerjaan->dalam,
                'id' => format_uang($value->hasGalianPekerjaan->total),
                'id' => $value->hasGalianPekerjaan->bongkaran,
                'id' => $value->hasGalianPekerjaan->keterangan,
            ];
        }

        return $galianPekerjaan;

        return view('tagihan.show', compact(
            'action',
            'title',
            'tagihan'
        ));
    }

    public function store(Request $request)
    {
        return 1;
    }

    public function model()
    {
        return new Tagihan();
    }
}
