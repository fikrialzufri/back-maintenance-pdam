<?php

namespace App\Exports;

use App\Models\PelaksanaanPekerjaan;
use Maatwebsite\Excel\Concerns\FromCollection;
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
            ->with([
                'hasAduan' => function ($query) use ($kategori, $status) {
                    // $query->where('aduan.kategori_aduan', $kategori);
                    $query->where('aduan.kategori_aduan', $kategori);
                }
            ])
            ->whereBetween('created_at', [$start, $end])->get();

        // sort by
        // $data = collect($data);
        $data = collect($data);

        if (auth()->user()->hasRole('staf-pengawas')) {
            // $data = $data->setCollection(
            $data->sortBy(function ($pekerjaan) {
                return $pekerjaan->status_order_pengawas;
            });
            // );
        } elseif (auth()->user()->hasRole('asisten-manajer-pengawas')) {
            // $data = $data->setCollection(
            // );
            $data->sortBy(function ($pekerjaan) {
                return $pekerjaan->status_order_asem_pengawas;
            });
        } elseif (auth()->user()->hasRole('manajer-perawatan')) {
            // $data = $data->setCollection(
            // );
            $data->sortBy(function ($pekerjaan) {
                return $pekerjaan->status_order_manajer_pengawas;
            });
        } elseif (auth()->user()->hasRole('manajer-distribusi')) {
            $data->sortBy(function ($pekerjaan) {
                return $pekerjaan->status_order_manajer;
            });
        } elseif (auth()->user()->hasRole('manajer-pengendalian-kehilangan-air')) {

            $data->sortBy(function ($pekerjaan) {
                return $pekerjaan->status_order_manajer;
            });
        } elseif (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
            $data->sortBy(function ($pekerjaan) {
                return $pekerjaan->status_order_perencanaan;
            });
        } else {

            $data->sortBy(function ($pekerjaan) {
                return $pekerjaan->status_order;
            });
        }
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
