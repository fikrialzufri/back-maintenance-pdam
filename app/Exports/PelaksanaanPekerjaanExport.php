<?php

namespace App\Exports;

use App\Models\PelaksanaanPekerjaan;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\View\View;

class PelaksanaanPekerjaanExport implements FromView, WithChunkReading
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

    public function chunkSize(): int
    {
        return 1000; // Adjust as needed
    }

    public function view(): View
    {
        // $data = [];
        $start = $this->start;
        $end = $this->end;
        $kategori = $this->kategori;
        $rekanan_id = $this->rekanan_id;
        $status = $this->status;
        $end = $this->end;
        $kategori = $this->kategori;
        $rekanan_id = $this->rekanan_id;
        $status = $this->status;
        $title = "List Pekerjaan";
        $data = PelaksanaanPekerjaan::with('hasItem', 'hasAduan', 'hasItemPengawas', 'hasItemAsmenPengawas', 'hasItemPerencanaan', 'hasItemPerencanaanAdujst', 'hasGalianPekerjaan')
            ->when($status != null, function ($q) use ($status) {
                if ($status != 'all') {
                    return $q->where('status', $status);
                }
            })
            ->when($rekanan_id != null, function ($q) use ($rekanan_id) {
                if ($rekanan_id != 'all') {
                    return $q->where('rekanan_id', $rekanan_id);
                }
            })
            ->whereHas('hasAduan', function ($query)  use ($kategori) {
                if ($kategori != 'all') {

                    $query->where('kategori_aduan', $kategori);
                }
            })
            ->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->get();

        return view(
            'penunjukan_pekerjaan.export',
            compact(
                'data',
                'title',
                'end'
            )
        );
    }
}
