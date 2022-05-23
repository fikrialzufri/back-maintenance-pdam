<?php

namespace App\Exports;

use App\Models\Tagihan;
use App\Models\TagihanItem;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ExportTagihan implements FromView
{

    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $data = [];

        $tagihan = Tagihan::find($this->id);
        $data = TagihanItem::where('tagihan_id', $tagihan->id)->orderBy('urutan')->get();

        return view('tagihan.export', compact(
            'tagihan',
            'data'
        ));
    }
}
