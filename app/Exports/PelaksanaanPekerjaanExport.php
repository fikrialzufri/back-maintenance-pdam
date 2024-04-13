<?php

namespace App\Exports;

use App\Models\PelaksanaanPekerjaan;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class PelaksanaanPekerjaanExport implements FromView
{

    protected $start, $end;

    function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function view(): View
    {
        // $data = [];
        $start = $this->start;
        $end = $this->end;
        $data = PelaksanaanPekerjaan::whereBetween('created_at', [$start, $end])->orderBy('created_at')->get();
        // dd($data);
        return view('penunjukan_pekerjaan.export', compact(
            'data',
            'end'
        ));
    }
}
