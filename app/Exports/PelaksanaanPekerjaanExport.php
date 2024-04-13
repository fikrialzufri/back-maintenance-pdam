<?php

namespace App\Exports;

use App\Models\PelaksanaanPekerjaan;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class PelaksanaanPekerjaanExport implements FromView
{
    // kategori=&rekanan_id=&tanggal=&status=
    protected $start, $end, $kategori, $rekanan_id, $status;


    function __construct($start, $end, $kategori, $rekanan_id, $status)
    {
        $this->start = $start;
        $this->end = $end;
        $this->kategori = $kategori;
        $this->rekanan_id = $rekanan_id;
        $this->status = $status;
    }

    public function view(): View
    {
        // $data = [];
        $start = $this->start;
        $end = $this->end;
        $kategori = $this->kategori;
        $rekanan_id = $this->rekanan_id;
        $status = $this->status;

        $data = PelaksanaanPekerjaan::with('hasItem', 'hasPenunjukanPekerjaan.hasAduan', 'hasItemPengawas')
            ->with(['hasPenunjukanPekerjaan.hasAduan' => function ($query) use ($kategori) {
                $query->where('aduan.kategori_aduan', $kategori);
            }])->whereBetween('created_at', [$start, $end])->orderBy('created_at')->get();
        // dd($data);
        return view('penunjukan_pekerjaan.export', compact(
            'data',
            'end'
        ));
    }
}
