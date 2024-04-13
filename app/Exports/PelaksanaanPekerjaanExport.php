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

        $data = PelaksanaanPekerjaan::with([
            'hasPenunjukanPekerjaan'
        ])
            ->with('hasPenunjukanPekerjaan', 'hasItem', 'hasItemPengawas', 'hasItemAsmenPengawas', 'hasItemPerencanaan', 'hasItemPerencanaanAdujst')
            ->when($status != null, function ($q) use ($status) {
                if ($status != 'all') {
                    return $q->where('status', $status);
                }
            })
            ->whereBetween('created_at', [$start, $end])->orderBy('created_at')->get();
        ;
        // dd($data);
        return view(
            'penunjukan_pekerjaan.export',
            compact(
                'data',
                'end'
            )
        );
    }
}
