<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aduan;
use App\Models\PenunjukanPekerjaan;
use App\Models\PelaksanaanPekerjaan;
use App\Models\JenisAduan;
use App\Models\Rekanan;
use DB;

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

        if (auth()->user()->hasRole('superadmin')) {
            $penunjukan = $query->orderBy('created_at', 'desc')->paginate($limit);
        } else {
            $penunjukan = $query->where('wilayah_id', auth()->user()->karyawan->id_wilayah)->orderBy('created_at', 'desc')->paginate($limit);
        }

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

    public function show($slug)
    {
        $aduan = Aduan::where('slug', $slug)->first();
        $penunjukan = PenunjukanPekerjaan::where('aduan_id', $aduan->id)->first();
        $jenisAduan = $aduan->hasJenisAduan->toArray();
        $jenis_aduan = JenisAduan::orderBy('nama')->get();
        $rekanan = Rekanan::orderBy('nama')->get();
        $title = 'Detail Aduan';
        $action = route('penunjukan_pekerjaan.store');

        if ($aduan == null) {
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Data Aduan tidak ditemukan')->with('Class', 'primary');
        }

        return view('penunjukan_pekerjaan.show', compact(
            'aduan',
            'penunjukan',
            'jenisAduan',
            'jenis_aduan',
            'rekanan',
            'title',
            'action'
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';
        $user_id = auth()->user()->id;
        $rekanan_id = $request->rekanan_id;
        $slug = $request->slug;
        $aduan = Aduan::where('slug', $slug)->first();
        $dataPenunjukanPerkerjaan = PenunjukanPekerjaan::count();
        if ($dataPenunjukanPerkerjaan >= 1) {
            $no = str_pad($dataPenunjukanPerkerjaan + 1, 4, "0", STR_PAD_LEFT);
            $nomor_pekerjaan =  $no . "/" . "SPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        } else {
            $no = str_pad(1, 4, "0", STR_PAD_LEFT);
            $nomor_pekerjaan =  $no . "/" . "SPK/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        }

        $penunjukanPekerjaan = PenunjukanPekerjaan::where('aduan_id', $aduan->id)->first();
        if ($penunjukanPekerjaan) {
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Aduan sudah dikerjakan')->with('Class', 'danger');
        }
        try {
            DB::commit();
            $data = new PenunjukanPekerjaan;
            $data->nomor_pekerjaan = $nomor_pekerjaan;
            $data->rekanan_id = $rekanan_id;
            $data->aduan_id = $aduan->id;
            $data->user_id = $user_id;
            $data->status = 'draft';
            $data->save();

            $aduan->status = 'proses';
            $aduan->save();

            $message = 'Berhasil Menyimpan Pelaksanaan Pekerjaan';
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Penunjukan pekerjaan berhasil ditambah')->with('Class', 'primary');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Penunjukan pekerjaan gagal ditambah')->with('Class', 'danger');
        }
    }
}
