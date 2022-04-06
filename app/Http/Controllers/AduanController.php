<?php

namespace App\Http\Controllers;

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
            $query = $query->where('no_ticket', 'like', "%".$search."%")->orWhere('title', 'like', "%".$search."%");
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

    public function create()
    {
        $title = 'Tambah data Aduan';
        $route = 'aduan';
        $action = route('aduan.store');

        $jenis_aduan = JenisAduan::orderBy('name')->get();
        return view('aduan.create', compact(
            'title',
            'route',
            'action',
            'jenis_aduan'
        ));
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
        ];

        $this->validate(request(), [
            'title' => 'required|string',
            'sumber_informasi' => 'required|string',
            'body' => 'required|string',
            'lokasi' => 'required|string',
            'lat_long' => 'required|string',
        ], $messages);

        $aduan = new Aduan();
        $aduan->no_ticket = "Aduan-" . date('YmdHis') . "-" . Str::random(5);
        $aduan->title = $request->title;
        $aduan->sumber_informasi = $request->sumber_informasi;
        $aduan->jenis_aduan_id = $request->jenis_aduan_id;
        $aduan->body = $request->body;
        $aduan->lokasi = $request->lokasi;
        $aduan->lat_long = str_replace(array('LatLng(', ')'), '', $request->lat_long);
        $aduan->status = "Terbuka";
        $aduan->user_id = auth()->user()->id;
        $aduan->save();
        
        return redirect()->route('aduan.index')->with('message', 'Aduan berhasil ditamba');
    }

    public function edit(Request $request, $slug)
    {
        $jenis_aduan = JenisAduan::orderBy('name')->get();
        $aduan = Aduan::where('slug', $slug)->first();

        if ($adua == null) {
            return redirect()->route('aduan.index')->with('message', 'Data Aduan tidak ditemukan');
        }

        // TODO: WORK ON THIS TOMORROW
    }
}
