<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aduan;
use App\Models\Item;
use App\Models\Jenis;
use App\Models\PenunjukanPekerjaan;
use App\Models\PelaksanaanPekerjaan;
use App\Models\JenisAduan;
use App\Models\Kategori;
use App\Models\Notifikasi;
use App\Models\Rekanan;
use DB;

class PenunjukanPekerjaanController extends Controller
{
    public function __construct()
    {
        $this->tambah = 'false';
        $this->route = 'penunjukan_pekerjaan';
        $this->middleware('permission:view-penunjukan-pekerjaan', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-penunjukan-pekerjaan', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-penunjukan-pekerjaan', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-penunjukan-pekerjaan', ['only' => ['delete']]);
    }

    public function index()
    {
        $title = 'List Pekerjaan';
        $route = 'penunjukan_pekerjaan';
        $search = request()->search;
        $limit = request()->limit ?? 30;

        $query = Aduan::query()->orderBy('status', 'asc');
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
        $jenisPekerjaan = [];
        $jenisBahan = [];
        $jenisBajenisAlatBanturang = [];
        $jenisTransportasi = [];

        $listPekerjaan = [];
        $listBahan = [];
        $listAlatBantu = [];
        $listTransportasi = [];

        $kategoriPekerjaan = Kategori::whereSlug('pekerjaan')->first();
        if ($kategoriPekerjaan) {
            $jenisPekerjaan = Jenis::where('kategori_id', $kategoriPekerjaan->id)->get()->pluck('id');
            $listPekerjaan = Item::whereIn('jenis_id', $jenisPekerjaan)->get();
        }

        $kategoriBahan = Kategori::whereSlug('bahan')->first();
        if ($kategoriBahan) {
            $jenisBahan = Jenis::where('kategori_id', $kategoriBahan->id)->get()->pluck('id');
            $listBahan = Item::whereIn('jenis_id', $jenisBahan)->get();
        }

        $kategoriAlatBantu = Kategori::whereSlug('alat-bantu')->first();
        if ($kategoriAlatBantu) {
            $jenisAlatBantu = Jenis::where('kategori_id', $kategoriAlatBantu->id)->get();
            $listAlatBantu = Item::whereIn('jenis_id', $jenisAlatBantu)->get();
        }

        $kategoriTransportasi = Kategori::whereSlug('transportasi')->first();
        if ($jenisTransportasi) {
            $jenisTransportasi = Jenis::where('kategori_id', $kategoriTransportasi->id)->get()->pluck('id');
            $listTransportasi = Item::whereIn('jenis_id', $jenisTransportasi)->get();
        }

        $pekerjaan = PelaksanaanPekerjaan::where('penunjukan_pekerjaan_id', $penunjukan->id)->with(["hasItem" => function ($q) use ($jenisPekerjaan) {
            $q->whereIn('item.jenis_id', $jenisPekerjaan);
        }])->first();

        $daftarBahan = PelaksanaanPekerjaan::where('penunjukan_pekerjaan_id', $penunjukan->id)->with(["hasItem" => function ($q) use ($jenisBahan) {
            $q->whereIn('item.jenis_id', $jenisBahan);
        }])->first();

        $daftarAlatBantu = PelaksanaanPekerjaan::where('penunjukan_pekerjaan_id', $penunjukan->id)->with(["hasItem" => function ($q) use ($jenisAlatBantu) {
            $q->whereIn('item.jenis_id', $jenisAlatBantu);
        }])->first();

        $daftarTransportasi = PelaksanaanPekerjaan::where('penunjukan_pekerjaan_id', $penunjukan->id)->with(["hasItem" => function ($q) use ($jenisTransportasi) {
            $q->whereIn('item.jenis_id', $jenisTransportasi);
        }])->first();

        $jenisAduan = $aduan->hasJenisAduan->toArray();
        $jenis_aduan = JenisAduan::orderBy('nama')->get();
        $rekanan = Rekanan::orderBy('nama')->get();

        $notifikasi = Notifikasi::where('modul_id', $aduan->id)->first();
        if ($notifikasi) {
            $notifikasi->status = 'baca';
            $notifikasi->save();
        }

        $title = 'Detail Aduan ' . $aduan->nomor_pekerjaan;
        $action = route('penunjukan_pekerjaan.store');

        if ($aduan == null) {
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Data Aduan tidak ditemukan')->with('Class', 'primary');
        }

        return view('penunjukan_pekerjaan.show', compact(
            'aduan',
            'penunjukan',
            'pekerjaan',
            'daftarBahan',
            'daftarAlatBantu',
            'daftarTransportasi',
            'jenisAduan',
            'jenis_aduan',
            'rekanan',
            'title',
            'listPekerjaan',
            'listBahan',
            'listAlatBantu',
            'listTransportasi',
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

        $notifikasi = Notifikasi::where('modul_id', $aduan->id)->first();
        if ($notifikasi) {
            $notifikasi->status = 'baca';
            $notifikasi->save();
        }

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

        $rekanan = Rekanan::find($rekanan_id);

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

            $title = "Penunjukan Pekerjaan Baru";
            $body = "SPK " . $nomor_pekerjaan . " telah diterbitkan";
            $modul = "penunjukan-pekerjaan";

            $this->notification($data->id, $data->slug, $title, $body, $modul, auth()->user()->id, $rekanan->hasUser->id);

            $message = 'Berhasil Menyimpan Pelaksanaan Pekerjaan';
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Penunjukan pekerjaan berhasil ditambah')->with('Class', 'primary');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Penunjukan pekerjaan gagal ditambah')->with('Class', 'danger');
        }
    }

    public function notifikasi($id)
    {
        $notifikasi = Notifikasi::where('modul_id', $id)->where('to_user_id', auth()->user()->id)->first();
        $notifikasi->status = 'baca';
        $notifikasi->save();

        $aduan = Aduan::where('id', $id)->first();

        return redirect()->route('penunjukan_pekerjaan.show', $aduan->slug);
    }
}
