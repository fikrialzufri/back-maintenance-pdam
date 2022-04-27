<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisAduan;
use App\Models\Aduan;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Notifikasi;
use App\Models\PenunjukanPekerjaan;
use DB;

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
            $query = $query->where('no_ticket', 'like', "%" . $search . "%")->orWhere('no_aduan', 'like', "%" . $search . "%");
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

        $jenis_aduan = JenisAduan::orderBy('nama')->get();
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
            'no_ticket' => 'required|string',
            'mps' => 'required|string',
            'sumber_informasi' => 'required|string',
            'keterangan' => 'required|string',
            'lokasi' => 'required|string',
            'lat_long' => 'required|string',
        ], $messages);

        $dataAduan = Aduan::count();
        if ($dataAduan >= 1) {
            $no = str_pad($dataAduan + 1, 4, "0", STR_PAD_LEFT);
            $noAduan =  $no . "/" . "ADB/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        } else {
            $no = str_pad(1, 4, "0", STR_PAD_LEFT);
            $noAduan =  $no . "/" . "ADB/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        }
        $id_wilayah =  auth()->user()->id_wilayah;
        DB::beginTransaction();

        try {
            $aduan = new Aduan();
            $aduan->no_ticket = $request->no_ticket;
            $aduan->no_aduan = $noAduan;
            $aduan->mps = $request->mps;
            $aduan->atas_nama = $request->atas_nama;
            $aduan->sumber_informasi = $request->sumber_informasi;
            $aduan->keterangan = $request->keterangan;
            $aduan->lokasi = $request->lokasi;
            $aduan->lat_long = str_replace(array('LatLng(', ')'), '', $request->lat_long);
            $aduan->status = "draft";
            $aduan->wilayah_id =  $id_wilayah;
            $aduan->user_id = auth()->user()->id;
            $aduan->save();
            $aduan->hasJenisAduan()->sync($request->jenis_aduan_id);
            DB::commit();

            $title = "Aduan Baru";
            $body = "Aduan dengan nomor aduan " . $noAduan . " telah dikirim";
            $modul = "aduan";

            $jabatan = Jabatan::where('wilayah_id', $id_wilayah)->where('nama', 'like', "%Asisten Manager%")->pluck('id');
            $karyawan = Karyawan::whereIn('jabatan_id', $jabatan)->get();
            foreach ($karyawan as $item) {
                $this->notification($aduan->id, $title, $body, $modul, auth()->user()->id, $item->user_id);
            }

            return redirect()->route('aduan.index')->with('message', 'Aduan berhasil ditambah')->with('Class', 'primary');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('aduan.index')->with('message', 'Aduan gagal ditambah')->with('Class', 'danger');
        }
    }

    public function edit(Request $request, $slug)
    {
        $aduan = Aduan::where('slug', $slug)->first();
        $jenisAduan = $aduan->hasJenisAduan->pluck('id')->toArray();
        $jenis_aduan = JenisAduan::orderBy('nama')->get();
        $title = "Ubah Aduan " . $aduan->title;
        $action = route('aduan.update', $slug);

        if ($aduan == null) {
            return redirect()->route('aduan.index')->with('message', 'Data Aduan tidak ditemukan')->with('Class', 'primary');
        }

        return view('aduan.edit', compact(
            'aduan',
            'jenis_aduan',
            'jenisAduan',
            'title',
            'action'
        ));
    }

    public function update(Request $request, $slug)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
        ];

        $this->validate(request(), [
            'no_ticket' => 'required|string',
            'mps' => 'required|string',
            'sumber_informasi' => 'required|string',
            'keterangan' => 'required|string',
            'lokasi' => 'required|string',
            'lat_long' => 'required|string',
        ], $messages);

        $aduan = Aduan::where('slug', $slug)->first();
        $aduan->no_ticket = $request->no_ticket;
        $aduan->mps = $request->mps;
        $aduan->atas_nama = $request->atas_nama;
        $aduan->sumber_informasi = $request->sumber_informasi;
        $aduan->keterangan = $request->keterangan;
        $aduan->lokasi = $request->lokasi;
        $aduan->lat_long = str_replace(array('LatLng(', ')'), '', $request->lat_long);
        $aduan->user_id = auth()->user()->id;
        $aduan->save();
        $aduan->hasJenisAduan()->sync($request->jenis_aduan_id);

        return redirect()->route('aduan.index')->with('message', 'Aduan berhasil diubah')->with('Class', 'primary');
    }

    public function destroy($slug)
    {
        $aduan = Aduan::where('slug', $slug)->first();
        $aduan->delete();
        $aduan->hasJenisAduan()->detach();

        return redirect()->route('aduan.index')->with('message', 'Aduan berhasil dihapus')->with('Class', 'primary');
    }
}
