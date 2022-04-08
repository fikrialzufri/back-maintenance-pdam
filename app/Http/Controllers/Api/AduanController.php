<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\JenisAduan;
use App\Models\Aduan;
use Str;

class AduanController extends Controller
{
    public function __construct()
    {
        $this->route = 'aduan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function index()
    {
        $title = 'List Aduan';
        $route = 'aduan';
        $search = request()->search;
        $limit = request()->limit ?? 30;

        $query = Aduan::query();
        if ($search) {
            $query = $query->where('no_ticket', 'like', "%" . $search . "%")->orWhere('title', 'like', "%" . $search . "%");
        }

        $aduan = $query->orderBy('created_at', 'desc')->paginate($limit);
        $count_aduan = $query->count();
        $no = $limit * ($aduan->currentPage() - 1);

        return view('aduan.index', compact(
            'title',
            'route',
            'aduan',
            'no',
            'count_aduan',
            'search',
            'limit'
        ));
    }
}
