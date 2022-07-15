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
use Str;
use Carbon\Carbon;
use App\Models\Wilayah;

class AduanController extends Controller
{

    private $endpoint;
    public function __construct()
    {
        $this->route = 'aduan';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);

        $this->endpoint = env('CUSTOMER_ENDPOINT');
    }

    public function headers()
    {
        return [
            'Authorization' => request()->headers->get('Authorization')
        ];
    }

    public function request()
    {
        return \Http::withHeaders($this->headers())->accept('application/json');
    }

    public function index()
    {
        $title = 'List Aduan';
        $route = 'aduan';
        $search = request()->search;
        $limit = request()->limit ?? 30;


        $query = Aduan::query();
        if ($search) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('no_ticket', 'like', "%" . $search . "%")->orWhere('no_aduan', 'like', "%" . $search . "%");
                });
            }
        }

        if (!auth()->user()->hasRole('superadmin')) {
            if (!auth()->user()->hasRole('rekanan')) {
                if (auth()->user()->hasRole('admin-distribusi') || auth()->user()->hasRole('asisten-manajer-distribusi')) {
                    $query->where('wilayah_id', auth()->user()->karyawan->id_wilayah)->where('kategori_nps', 'dis');
                }
                if (auth()->user()->hasRole('admin-pengendalian-kehilangan-air') || auth()->user()->hasRole('asisten-manajer-pengendalian-kehilangan-air')) {
                    $query->where('wilayah_id', auth()->user()->karyawan->id_wilayah)->where('kategori_nps', 'pka');
                }
            } else {
                $rekanan_id = auth()->user()->id_rekanan;
                $penunjukanAduan = PenunjukanPekerjaan::where('rekanan_id', $rekanan_id)->get()->pluck('aduan_id')->toArray();
                $query->whereIn('id', $penunjukanAduan);
            }
        }

        $aduan = $query->orderBy('status', 'asc')->orderBy('created_at', 'desc')->paginate($limit);
        $count_aduan = $query->count();

        return view('aduan.index', compact(
            'title',
            'route',
            'aduan',
            'count_aduan',
            'search',
            'limit'
        ));
    }

    public function getNumber(Request $request)
    {
        $kategori_aduan = $request->kategori_aduan == '' ? '' : $request->kategori_aduan;
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $id_wilayah = auth()->user()->karyawan->id_wilayah;

        $wilayah = "WIL-" . Wilayah::find($id_wilayah)->singkatan;

        $jabatan_id = auth()->user()->karyawan->jabatan_id;

        $jabatan  = Jabatan::find($jabatan_id);

        if (strpos($jabatan, "Distribusi") !== false) {
            $divisi = "DIS";
        } else {
            $divisi = "PKA";
        }

        if ($kategori_aduan == 'pipa dinas') {
            $dataAduan = Aduan::where('kategori_aduan', 'pipa dinas')->whereBetween(DB::raw('DATE(created_at)'), array($start, $end))->count();
            if ($dataAduan >= 1) {
                $no = str_pad($dataAduan + 1, 4, "0", STR_PAD_LEFT);
                $noAduan =  $no . "/" . "ADU-DS/" . $wilayah . "/" . $divisi . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            } else {
                $no = str_pad(1, 4, "0", STR_PAD_LEFT);
                $noAduan =  $no . "/" . "ADU-DS/" . $wilayah . "/" . $divisi . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            }
        } else {
            $dataAduan = Aduan::where('kategori_aduan', 'pipa premier / skunder')->whereBetween(DB::raw('DATE(created_at)'), array($start, $end))->count();
            if ($dataAduan >= 1) {
                $no = str_pad($dataAduan + 1, 4, "0", STR_PAD_LEFT);
                $noAduan =  $no . "/" . "ADU-SK/" . $wilayah . "/" . $divisi . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            } else {
                $no = str_pad(1, 4, "0", STR_PAD_LEFT);
                $noAduan =  $no . "/" . "ADU-SK/" . $wilayah . "/" . $divisi . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            }
        }

        return $this->sendResponse($noAduan, "No aduan", 200, 1);
    }

    public function create()
    {
        $title = 'Tambah data Aduan';
        $route = 'aduan';
        $action = route('aduan.store');

        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $id_wilayah = auth()->user()->karyawan->id_wilayah;

        $wilayah = "WIL-" . Wilayah::find($id_wilayah)->singkatan;

        $jabatan_id = auth()->user()->karyawan->jabatan_id;

        $jabatan  = Jabatan::find($jabatan_id);

        if (strpos($jabatan, "Distribusi") !== false) {
            $divisi = "DIS";
        } else {
            $divisi = "PKA";
        }

        $dataAduan = Aduan::where('kategori_aduan', 'pipa dinas')->whereBetween(DB::raw('DATE(created_at)'), array($start, $end))->count();
        if ($dataAduan >= 1) {
            $no = str_pad($dataAduan + 1, 4, "0", STR_PAD_LEFT);
            $noAduan =  $no . "/" . "ADU-DS/" . $wilayah . "/" . $divisi . "/"  . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            $noAduanNps =  "NPS/" . $no . "/" . "ADU/" . $wilayah . "/" . $divisi . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        } else {
            $no = str_pad(1, 4, "0", STR_PAD_LEFT);
            $noAduan =  $no . "/" . "ADU-DS/" . $wilayah . "/" . $divisi . "/"  . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            $noAduanNps =  "NPS/" . $no . "/" . "ADU/" . $wilayah . "/" . $divisi . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        }

        $jenis_aduan = JenisAduan::orderBy('nama')->get();

        // return $listPelanggan = $this->request()->get("{$this->endpoint}")->json();
        return view('aduan.create', compact(
            'title',
            'route',
            'noAduan',
            'noAduanNps',
            'action',
            'jenis_aduan'
        ));
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
        ];

        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $this->validate(request(), [
            'no_ticket' => 'required|string',
            'nps' => 'required|string',
            'sumber_informasi' => 'required|string',
            'lokasi' => 'required|string',
            'lat_long' => 'required|string',
        ], $messages);

        $kategori_aduan = $request->kategori_aduan == '' ? '' : $request->kategori_aduan;


        $id_wilayah =  auth()->user()->id_wilayah;

        $wilayah = "WIL-" . Wilayah::find($id_wilayah)->singkatan;

        $jabatan_id = auth()->user()->karyawan->jabatan_id;

        $jabatan  = Jabatan::find($jabatan_id);

        if (strpos($jabatan, "Distribusi") !== false) {
            $divisi = "DIS";
        } else {
            $divisi = "PKA";
        }
        if ($kategori_aduan == 'pipa dinas') {
            $dataAduan = Aduan::where('kategori_aduan', 'pipa dinas')->whereBetween(DB::raw('DATE(created_at)'), array($start, $end))->count();
            if ($dataAduan >= 1) {
                $no = str_pad($dataAduan + 1, 4, "0", STR_PAD_LEFT);
                $noAduan =  $no . "/" . "ADU-DS/"  . $wilayah . "/" . $divisi . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            } else {
                $no = str_pad(1, 4, "0", STR_PAD_LEFT);
                $noAduan =  $no . "/" . "ADU-DS/"  . $wilayah . "/" . $divisi . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            }
        } else {
            $dataAduan = Aduan::where('kategori_aduan', 'pipa premier / skunder')->whereBetween(DB::raw('DATE(created_at)'), array($start, $end))->count();
            if ($dataAduan >= 1) {
                $no = str_pad($dataAduan + 1, 4, "0", STR_PAD_LEFT);
                $noAduan =  $no . "/" . "ADU-SK/"  . $wilayah . "/" . $divisi . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            } else {
                $no = str_pad(1, 4, "0", STR_PAD_LEFT);
                $noAduan =  $no . "/" . "ADU-SK/"  . $wilayah . "/" . $divisi . "/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
            }
        }

        DB::beginTransaction();

        try {
            DB::commit();
            $aduan = new Aduan();
            $aduan->no_ticket = $request->no_ticket;
            $aduan->no_aduan = $noAduan;
            $aduan->no_pelanggan = $request->no_pelanggan;
            $aduan->detail_lokasi = $request->detail_lokasi;
            $aduan->no_hp = $request->no_hp;
            $aduan->nama_pelanggan = $request->nama_pelanggan;
            $aduan->nps = $request->nps;
            $aduan->kategori_aduan = $request->kategori_aduan;
            $aduan->kategori_nps = Str::slug($divisi);
            $aduan->atas_nama = $request->atas_nama;
            $aduan->sumber_informasi = $request->sumber_informasi;
            $aduan->keterangan = isset($request->keterangan) ?  $request->keterangan : '';
            $aduan->lokasi = $request->lokasi;
            $aduan->lat_long = str_replace(array('LatLng(', ')'), '', $request->lat_long);
            $aduan->status = "draft";
            $aduan->wilayah_id =  $id_wilayah;
            $aduan->user_id = auth()->user()->id;
            $aduan->save();
            $aduan->hasJenisAduan()->sync($request->jenis_aduan_id);

            $title = "Aduan Baru";
            $body = "Aduan dengan nomor aduan " . $noAduan . " telah dikirim";
            $modul = "aduan";

            $jabatan = Jabatan::where('wilayah_id', $id_wilayah)->where('nama', 'like', "%Asisten Manajer%")->pluck('id');
            $karyawan = Karyawan::whereIn('jabatan_id', $jabatan)->get();
            foreach ($karyawan as $item) {
                $this->notification($aduan->id, $aduan->slug, $title, $body, $modul, auth()->user()->id, $item->user_id);
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
            'nps' => 'required|string',
            'sumber_informasi' => 'required|string',
            'lokasi' => 'required|string',
            'lat_long' => 'required|string',
        ], $messages);

        $aduan = Aduan::where('slug', $slug)->first();
        $aduan->no_ticket = $request->no_ticket;
        $aduan->nps = $request->nps;
        $aduan->detail_lokasi = $request->detail_lokasi;
        $aduan->nama_pelanggan = $request->nama_pelanggan;
        $aduan->no_pelanggan = $request->no_pelanggan;
        $aduan->no_hp = $request->no_hp;
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
