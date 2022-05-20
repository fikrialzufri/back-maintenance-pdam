<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aduan;
use App\Models\GalianPekerjaan;
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

        $jenisPekerjaan = [];
        $jenisBahan = [];
        $jenisBajenisAlatBanturang = [];
        $jenisTransportasi = [];

        $listPekerjaan = [];
        $listBahan = [];
        $listAlatBantu = [];
        $listTransportasi = [];
        $penunjukan = [];
        $pekerjaanUtama = [];
        $daftarPekerjaan = [];
        $daftarGalian = [];
        $daftarBahan = [];
        $daftarAlatBantu = [];
        $daftarTransportasi = [];

        $kategoriPekerjaan = Kategori::whereSlug('pekerjaan')->first();
        if ($kategoriPekerjaan) {
            $jenisPekerjaan = Jenis::where('kategori_id', $kategoriPekerjaan->id)->get()->pluck('id');
            $listPekerjaan = Item::whereIn('jenis_id', $jenisPekerjaan)->get();
        }

        $kategoriGalian = Kategori::whereSlug('galian')->first();
        if ($kategoriGalian) {
            $jenisGalian = Jenis::where('kategori_id', $kategoriGalian->id)->get()->pluck('id');
            $listGalian = Item::whereIn('jenis_id', $jenisGalian)->get();
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
        if ($kategoriTransportasi) {
            $jenisTransportasi = Jenis::where('kategori_id', $kategoriTransportasi->id)->get()->pluck('id');
            $listTransportasi = Item::whereIn('jenis_id', $jenisTransportasi)->get();
        }
        if ($aduan->status !== 'draft') {
            $penunjukan = PenunjukanPekerjaan::where('aduan_id', $aduan->id)->first();

            $query = PelaksanaanPekerjaan::where('penunjukan_pekerjaan_id', $penunjukan->id);

            $pekerjaanUtama = $query->first();

            if ($pekerjaanUtama) {

                $daftarPekerjaan = $query->with(["hasItem" => function ($q) use ($listPekerjaan) {
                    $q->whereIn('item.id', $listPekerjaan->pluck('id'));
                }])->first();

                $daftarBahan = $query->with(["hasItem" => function ($q) use ($listBahan) {
                    $q->whereIn('item.id', $listBahan->pluck('id'));
                }])->first();

                $daftarGalian = GalianPekerjaan::where('pelaksanaan_pekerjaan_id', $pekerjaanUtama->id)->get();

                $daftarAlatBantu = $query->with(["hasItem" => function ($q) use ($listAlatBantu) {
                    $q->whereIn('item.id', $listAlatBantu->pluck('id'));
                }])->first();

                $daftarTransportasi = $query->with(["hasItem" => function ($q) use ($listTransportasi) {
                    $q->whereIn('item.id', $listTransportasi->pluck('id'));
                }])->first();
            }
        }


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
            'pekerjaanUtama',
            'daftarPekerjaan',
            'daftarGalian',
            'daftarBahan',
            'daftarAlatBantu',
            'daftarTransportasi',
            'jenisAduan',
            'jenis_aduan',
            'rekanan',
            'title',
            'listPekerjaan',
            'listGalian',
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        $title =  "Upload Data rekanan";
        $route = $this->route;
        $action = route('rekanan.upload');

        return view('rekanan.upload', compact(
            "title",
            "route",
            "action",
        ));
    }
    /**
     * upload data
     *
     * @return \Illuminate\Http\Response
     * @param \Illuminate\Http\Request
     */
    public function uploaddata(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $dataRekanan = [];
        $dataCV = [];
        $dataNama = [];
        $dataKtp = [];
        $dataNoHp = [];
        $dataAlamat = [];
        $dataUser = [];
        $dataUserName = [];
        $dataPassword = [];
        $dataEmail = [];
        $itemExist = [];

        $file = $request->hasFile('file');
        $total = 0;
        try {
            if ($file) {
                $item = Excel::toArray('', request()->file('file'), null, null);
                foreach ($item[0] as $k => $val) {
                    $dataItem[$k] = $val;
                }
                foreach ($dataItem as $index => $item) {
                    $dataCV[$index] = $item[1];
                    $dataNama[$index] = $item[2];
                    $dataKtp[$index] = $item[3];
                    $dataNoHp[$index] = $item[4];
                    $dataAlamat[$index] = $item[5];
                    $dataUserName[$index] = $item[6];
                    $dataPassword[$index] = $item[7];
                    $dataEmail[$index] = $item[8];

                    if ($index > 2) {
                        $dataUser[$index] = User::where('username', 'LIKE', '%' .  $dataCV[$index] . "%")->first();
                        if (!$dataUser[$index]) {
                            $dataUser[$index] = new User;
                            $dataUser[$index]->name =  $dataUserName[$index];
                            $dataUser[$index]->username =  $dataUserName[$index];
                            $dataUser[$index]->password =  bcrypt($dataPassword[$index]);
                            $dataUser[$index]->email =  $dataEmail[$index];
                            $dataUser[$index]->save();
                        }
                        $dataRekanan[$index] = Rekanan::where('nama', 'LIKE', '%' . $dataCV[$index] . "%")->first();
                        if (!$dataRekanan[$index]) {
                            if ($dataNama[$index] != null) {
                                $Rekanan = new Rekanan;
                                $Rekanan->nama =  $dataCV[$index];
                                $Rekanan->nama_penangung_jawab =  $dataNama[$index];
                                $Rekanan->nik =  $dataKtp[$index];
                                $Rekanan->no_hp =  $dataNoHp[$index];
                                $Rekanan->alamat =  $dataAlamat[$index];
                                $Rekanan->user_id =  $dataUser[$index]->id;
                                $Rekanan->save();
                                $total = ++$index;
                            }
                        }
                    }
                }
                return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil diupload dengan total item :' . $total)->with('Class', 'success');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' gagal diupload')->with('Class', 'success');
        }
    }
}
