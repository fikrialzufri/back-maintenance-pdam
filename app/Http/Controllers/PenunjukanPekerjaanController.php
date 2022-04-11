<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aduan;
use App\Models\PenunjukanPekerjaan;

class PenunjukanPekerjaanController extends Controller
{
    public function __construct()
    {
        $this->route = 'penunjukan_pekerjaan';
        $this->middleware('permission:view-penunjukan-pekerjaan', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-penunjukan-pekerjaan', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-penunjukan-pekerjaan', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-penunjukan-pekerjaan', ['only' => ['delete']]);
    }

    public function index()
    {
        $title = 'List Penunjukan Pekerjaan';
        $route = 'penunjukan_pekerjaan';
        $search = request()->search;
        $limit = request()->limit ?? 30;

        $query = Aduan::query();
        if ($search) {
            $query = $query->where('no_ticket', 'like', "%" . $search . "%")->orWhere('no_aduan', 'like', "%" . $search . "%");
        }

        $penunjukan = $query->where('wilayah_id', auth()->user()->karyawan->id_wilayah)->orderBy('created_at', 'desc')->paginate($limit);
        $count_penunjukan = $query->count();
        $no = $limit * ($penunjukan->currentPage() - 1);

        return view('penunjukan_pekerjaan.index', compact(
            'title',
            'route',
            'penunjukan',
            'no',
            'count_penunjukan',
            'search',
            'limit'
        ));
    }
}