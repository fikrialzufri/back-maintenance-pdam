<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aduan;
use App\Models\GalianAsmen;
use App\Models\GalianPekerjaan;
use App\Models\GalianPengawas;
use App\Models\GalianPerencanaan;
use App\Models\GalianPerencanaanAdjust;
use App\Models\Item;
use App\Models\Jabatan;
use App\Models\Jenis;
use App\Models\PenunjukanPekerjaan;
use App\Models\PelaksanaanPekerjaan;
use App\Models\JenisAduan;
use App\Models\Karyawan;
use App\Models\Kategori;
use App\Models\Notifikasi;
use App\Models\PelakasanaanAsmen;
use App\Models\PelakasanaanItem;
use App\Models\PelakasanaanPengawas;
use App\Models\Rekanan;
use App\Models\Tagihan;
use App\Models\Wilayah;
use DB;
use Excel;
use Str;
use Carbon\Carbon;
use App\Models\User;

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
        $kategori = request()->kategori;
        $status = request()->status;
        $rekananid = request()->rekanan_id;
        $limit = request()->limit ?? 50;
        $rekanan_id = null;
        $btnProsesPenunjukan = false;

        $query = Aduan::query();
        $tanggal = '';
        $spk = request()->spk;

        $rekanan = Rekanan::query();

        if (request()->tanggal != '') {
            $date = explode(' - ', request()->tanggal);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
            $tanggal = request()->tanggal;
        } else {
            $start = Carbon::now()->startOfMonth()->format('m/d/Y');
            $end = Carbon::now()->endOfMonth()->format('m/d/Y');
            // $tanggal = $start . " - " . $end;
        }

        // $query = $query->whereBetween(DB::raw('DATE(' . $val['name'] . ')'), array($start, $end));
        if (auth()->user()->hasRole('admin-distribusi') || auth()->user()->hasRole('asisten-manajer-distribusi')) {

            $query->where('wilayah_id', auth()->user()->karyawan->id_wilayah)->where('kategori_nps', 'dis');
        }
        if (auth()->user()->hasRole('manajer-distribusi')) {

            $query->where('kategori_nps', 'dis');
        }
        if (auth()->user()->hasRole('admin-pengendalian-kehilangan-air') || auth()->user()->hasRole('asisten-manajer-pengendalian-kehilangan-air')) {
            $query->where('wilayah_id', auth()->user()->karyawan->id_wilayah)->where('kategori_nps', 'pka');
        }
        if (auth()->user()->hasRole('manajer-pengendalian-kehilangan-air')) {
            $query->where('kategori_nps', 'pka');
        }
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('no_ticket', 'like', "%" . $search . "%")
                    ->orWhere('no_aduan', 'like', "%" . $search . "%")
                    ->orWhere('nama_pelanggan', 'like', "%" . $search . "%")
                    ->orWhere('no_pelanggan', 'like', "%" . $search . "%")
                    ->orWhere('detail_lokasi', 'like', "%" . $search . "%")
                    ->orWhere('nps', 'like', "%" . $search . "%")
                    ->orWhere('atas_nama', 'like', "%" . $search . "%")
                    ->orWhere('sumber_informasi', 'like', "%" . $search . "%")
                    ->orWhere('no_hp', 'like', "%" . $search . "%");
            });
        }
        if ($kategori) {
            $query->where('kategori_aduan', 'like', "%" . $kategori . "%");
        }


        if (!auth()->user()->hasRole('superadmin')) {
            if (auth()->user()->hasRole('rekanan')) {
                $rekanan_id = auth()->user()->id_rekanan;
                $penunjukanAduan = PenunjukanPekerjaan::where('rekanan_id', $rekanan_id);

                if (request()->spk != '') {
                    $penunjukanAduan = $penunjukanAduan->where('nomor_pekerjaan', 'like', '%' . $spk . '%');
                }

                if (request()->tanggal != '') {
                    $PelaksanaanPekerjaan = PelaksanaanPekerjaan::whereBetween(DB::raw('DATE(created_at)'), array($start, $end))->get()->pluck('penunjukan_pekerjaan_id')->toArray();

                    $penunjukanAduan = $penunjukanAduan->whereIn('id', $PelaksanaanPekerjaan);
                }

                if ($status != '') {
                    if ($status != 'all') {
                        $penunjukanAduan = $penunjukanAduan->whereStatus($status);
                    }
                }

                $penunjukanAduan = $penunjukanAduan->get()->pluck('aduan_id')->toArray();


                $query->whereIn('id', $penunjukanAduan);
                $penunjukan = $query->paginate($limit);

                $penunjukan = $penunjukan->setCollection(
                    $penunjukan->sortBy(function ($pekerjaan) {
                        return $pekerjaan->status_order;
                    })
                );
            } elseif (auth()->user()->hasRole('staf-distribusi')) {
                $id_karyawan = auth()->user()->id_karyawan;
                $penunjukanAduan = PenunjukanPekerjaan::where('karyawan_id', $id_karyawan);
                if (request()->spk != '') {
                    $penunjukanAduan = $penunjukanAduan->where('nomor_pekerjaan', 'like', '%' . $spk . '%');
                }

                if (request()->tanggal != '') {
                    $PelaksanaanPekerjaan = PelaksanaanPekerjaan::whereBetween(DB::raw('DATE(created_at)'), array($start, $end))->get()->pluck('penunjukan_pekerjaan_id')->toArray();

                    $penunjukanAduan = $penunjukanAduan->whereIn('id', $PelaksanaanPekerjaan);
                }
                if ($rekananid != 'all') {
                    if ($rekananid) {
                        $penunjukanAduan = $penunjukanAduan->where('rekanan_id', $rekananid);
                    }
                }

                if ($status != '') {
                    if ($status != 'all') {
                        $penunjukanAduan = $penunjukanAduan->whereStatus($status);
                    }
                }

                $penunjukanAduan = $penunjukanAduan->get()->pluck('aduan_id')->toArray();

                $query->whereIn('id', $penunjukanAduan);
                $penunjukan = $query->paginate($limit);

                $penunjukan = $penunjukan->setCollection(
                    $penunjukan->sortBy(function ($pekerjaan) {
                        return $pekerjaan->status_order;
                    })
                );
            } else {
                $list_rekanan_id = auth()->user()->karyawan->hasRekanan->pluck('id');

                if (count($list_rekanan_id) > 0) {
                    $rekanan = $rekanan->whereIn('id', $list_rekanan_id);
                    // return $rekananid;
                    if (isset($rekananid)) {
                        $penunjukanAduan = PenunjukanPekerjaan::whereIn('rekanan_id', [$rekananid]);
                    } else {
                        // return 1;
                        $penunjukanAduan = PenunjukanPekerjaan::whereIn('rekanan_id', $list_rekanan_id);
                    }


                    if (request()->spk != '') {
                        $penunjukanAduan = $penunjukanAduan->where('nomor_pekerjaan', 'like', '%' . $spk . '%');
                    }

                    if (request()->tanggal != '') {
                        $PelaksanaanPekerjaan = PelaksanaanPekerjaan::whereBetween(DB::raw('DATE(created_at)'), array($start, $end))->get()->pluck('penunjukan_pekerjaan_id')->toArray();

                        $penunjukanAduan = $penunjukanAduan->whereIn('id', $PelaksanaanPekerjaan);
                    }

                    if ($status != '') {
                        if ($status != 'all') {
                            $penunjukanAduan = $penunjukanAduan->whereStatus($status);
                        }
                    }

                    $penunjukanAduan = $penunjukanAduan->get()->pluck('aduan_id')->toArray();

                    if (isset($penunjukanAduan)) {
                        $query->whereIn('id', $penunjukanAduan);
                    }

                    $query->orderBy('updated_at', 'desc');
                    $penunjukan = $query->paginate($limit);
                    $penunjukan = $penunjukan->setCollection(
                        $penunjukan->sortBy(function ($pekerjaan) {
                            return $pekerjaan->status_order_pengawas;
                        })
                    );
                } else {
                    $id_wilayah = auth()->user()->karyawan->id_wilayah;
                    $wilayah = Wilayah::find($id_wilayah);
                    $penunjukanAduan = PenunjukanPekerjaan::query();

                    if (request()->spk != '') {
                        $penunjukanAduan = $penunjukanAduan->where('nomor_pekerjaan', 'like', '%' . $spk . '%');
                    }

                    if (request()->tanggal != '') {
                        $PelaksanaanPekerjaan = PelaksanaanPekerjaan::whereBetween(DB::raw('DATE(created_at)'), array($start, $end))->get()->pluck('penunjukan_pekerjaan_id')->toArray();

                        $penunjukanAduan = $penunjukanAduan->whereIn('id', $PelaksanaanPekerjaan);
                    }
                    if ($rekananid != 'all') {
                        if (isset($rekananid)) {
                            $penunjukanAduan = $penunjukanAduan->where('rekanan_id', $rekananid);
                        }
                    }

                    if ($status != '') {
                        if ($status != 'all') {
                            $penunjukanAduan = $penunjukanAduan->whereStatus($status);
                        }
                    }
                    if (request()->spk != '' || request()->tanggal != '' || $rekananid) {
                        $penunjukanAduan = $penunjukanAduan->get()->pluck('aduan_id')->toArray();
                        $query->whereIn('id', $penunjukanAduan);
                    } else {

                        if ($status != '') {
                            if ($status != 'all') {
                                // return $penunjukanAduan->get();
                                // $penunjukanAduan = $penunjukanAduan->get();
                                if ($penunjukanAduan) {
                                    $penunjukanAduan = $penunjukanAduan->get()->pluck('aduan_id')->toArray();
                                    $query->whereIn('id', $penunjukanAduan);
                                }
                            }
                        }
                    }



                    // $query->whereStatus('selesai');
                    if ($wilayah->nama !== 'Wilayah Samarinda') {
                        $query->where('wilayah_id', auth()->user()->karyawan->id_wilayah)->orderBy('status', 'asc')->orderBy('updated_at', 'desc');
                        $penunjukan = $query->paginate($limit);

                        if (auth()->user()->hasRole('asisten-manajer-distribusi')) {
                            $penunjukan = $penunjukan->setCollection(
                                $penunjukan->sortBy(function ($pekerjaan) {
                                    return $pekerjaan->status_order_asmen;
                                })
                            );
                        } elseif (auth()->user()->hasRole('asisten-manajer-pengendalian-kehilangan-air')) {

                            $penunjukan = $penunjukan->setCollection(
                                $penunjukan->sortBy(function ($pekerjaan) {
                                    return $pekerjaan->status_order_asmen;
                                })
                            );
                        }
                    } else {
                        $penunjukan = $query->where('status', '!=', 'draft')->with([
                            'hasPenunjukanPekerjaan' => function ($query) {
                                $query->orderBy('status', 'desc');
                            }
                        ])->orderBy('status', 'desc')->orderBy('updated_at', 'desc');
                        $penunjukan = $query->paginate($limit);

                        if (auth()->user()->hasRole('staf-pengawas')) {
                            $penunjukan = $penunjukan->setCollection(
                                $penunjukan->sortBy(function ($pekerjaan) {
                                    return $pekerjaan->status_order_pengawas;
                                })
                            );
                        } elseif (auth()->user()->hasRole('asisten-manajer-pengawas')) {
                            $penunjukan = $penunjukan->setCollection(
                                $penunjukan->sortBy(function ($pekerjaan) {
                                    return $pekerjaan->status_order_asem_pengawas;
                                })
                            );
                        } elseif (auth()->user()->hasRole('manajer-perawatan')) {
                            $penunjukan = $penunjukan->setCollection(
                                $penunjukan->sortBy(function ($pekerjaan) {
                                    return $pekerjaan->status_order_manajer_pengawas;
                                })
                            );
                        } elseif (auth()->user()->hasRole('manajer-distribusi')) {
                            $penunjukan = $penunjukan->setCollection(
                                $penunjukan->sortBy(function ($pekerjaan) {
                                    return $pekerjaan->status_order_manajer;
                                })
                            );
                        } elseif (auth()->user()->hasRole('manajer-pengendalian-kehilangan-air')) {
                            $penunjukan = $penunjukan->setCollection(
                                $penunjukan->sortBy(function ($pekerjaan) {
                                    return $pekerjaan->status_order_manajer;
                                })
                            );
                        } elseif (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
                            $penunjukan = $penunjukan->setCollection(
                                $penunjukan->sortBy(function ($pekerjaan) {
                                    return $pekerjaan->status_order_perencanaan;
                                })
                            );
                        } else {
                            $penunjukan = $penunjukan->setCollection(
                                $penunjukan->sortBy(function ($pekerjaan) {
                                    return $pekerjaan->status_order;
                                })
                            );
                        }
                    }
                }
            }
        } else {
            // return 1;
            $penunjukan = $query->paginate(50);
            $penunjukan = $penunjukan->setCollection(
                $penunjukan->sortBy(function ($pekerjaan) {
                    return $pekerjaan->status_order_all;
                })
            );
        }

        return $status;

        $rekanan = $rekanan->orderBy('nama')->get();

        return view(
            'penunjukan_pekerjaan.index',
            compact(
                'title',
                'route',
                'tanggal',
                'btnProsesPenunjukan',
                'rekanan_id',
                'rekanan',
                'rekananid',
                'spk',
                'kategori',
                'penunjukan',
                'search',
                'limit',
                'status'
            )
        );
    }

    public function show($slug)
    {
        $aduan = Aduan::where('slug', $slug)->first();

        if ($aduan == null) {
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Data Aduan tidak ditemukan')->with('Class', 'primary');
        }

        $id_wilayah = auth()->user()->id_wilayah;

        $wilayah = Wilayah::find($id_wilayah);

        $querykaryawan = Karyawan::where('pekerjaan', 'ya');
        if ($wilayah) {
            if ($wilayah->nama != 'Samarinda') {
                $jabatan = Jabatan::where('wilayah_id', $wilayah->id)->pluck('id');
                $querykaryawan = $querykaryawan->whereIn('jabatan_id', $jabatan);
            }
        }

        $karyawanPekerja = $querykaryawan->orderBy('nama')->get();

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
        $listDokumentasi = [];
        $daftarDokumentasi = [];

        $totalPekerjaan = 0;


        $action = route('penunjukan_pekerjaan.store');
        $rekanan_id = null;
        $fotoBahan = [];
        $fotoPekerjaan = [];
        $fotoPenyelesaian = [];
        $tombolEdit = '';
        $lat_long_pekerjaan = '';
        $lokasi_pekerjaan = '';
        $pekerjaanUtama = [];
        $list_persetujuan = [];
        $pengawas = false;
        $asmenpengawas = false;
        $perencaan = false;


        $galian = Kategori::whereSlug('galian')->first()->id;

        $jenisGalian = Jenis::where('kategori_id', $galian)->pluck('id')->toArray();
        $listPekerjaanGalian = Item::orderBy('nama')->whereIn('jenis_id', $jenisGalian)->get();
        $listPekerjaan = Item::orderBy('nama')->whereNotIn('jenis_id', $jenisGalian)->get();

        if ($aduan->status != 'draft') {
            $penunjukan = PenunjukanPekerjaan::where('aduan_id', $aduan->id)->with('hasUserMany')->first();
            $query = PelaksanaanPekerjaan::where('penunjukan_pekerjaan_id', $penunjukan->id);

            if (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
                $perencaan = true;
            }

            if (auth()->user()->hasRole('superadmin')) {
                $perencaan = true;
            }
            $action = route('penunjukan_pekerjaan.update', $penunjukan->id);
            $pekerjaanUtama = $query->first();
            if ($pekerjaanUtama) {

                $fotoBahan = (object) $penunjukan->foto_bahan;
                $fotoPekerjaan = (object) $penunjukan->foto_lokasi;
                $fotoPenyelesaian = (object) $penunjukan->foto_penyelesaian;

                $daftarPekerjaan = $query->with("hasItem")->with('hasItemPengawas')->first();

                $daftarGalian = GalianPekerjaan::where('pelaksanaan_pekerjaan_id', $pekerjaanUtama->id)->with('hasGalianPengawas')->get();

                // $action = route('penunjukan_pekerjaan.update');
                $action = route('penunjukan_pekerjaan.update', $pekerjaanUtama->id);

                $lat_long_pekerjaan = $pekerjaanUtama->lat_long;
                $lokasi_pekerjaan = $pekerjaanUtama->lokasi;

                if (auth()->user()->hasRole('asisten-manajer-distribusi')) {

                    $CheckAduan = Aduan::where('id', $aduan->id)->where('kategori_nps', "dis")->first();
                    if ($pekerjaanUtama->status === 'selesai') {
                        if ($CheckAduan) {
                            $tombolEdit = 'bisa';
                        }
                    }
                }
                if (auth()->user()->hasRole('asisten-manajer-pengendalian-kehilangan-air')) {
                    $CheckAduan = Aduan::where('id', $aduan->id)->where('kategori_nps', "pka")->first();

                    if ($pekerjaanUtama->status === 'selesai') {
                        if ($CheckAduan) {
                            $tombolEdit = 'bisa';
                        }
                    }
                }
                if (auth()->user()->hasRole('manajer-distribusi')) {

                    $CheckAduan = Aduan::where('id', $aduan->id)->where('kategori_nps', "dis")->first();
                    if ($pekerjaanUtama->status === 'approve') {
                        if ($CheckAduan) {
                            $tombolEdit = 'bisa';
                        }
                    }
                }
                if (auth()->user()->hasRole('manajer-pengendalian-kehilangan-air')) {
                    $CheckAduan = Aduan::where('id', $aduan->id)->where('kategori_nps', "pka")->first();
                    // return $pekerjaanUtama->status;
                    if ($pekerjaanUtama->status === 'approve') {
                        if ($CheckAduan) {
                            $tombolEdit = 'bisa';
                        }
                    }
                }

                if (auth()->user()->hasRole('staf-pengawas')) {
                    if ($pekerjaanUtama->status === 'approve manajer') {
                        $tombolEdit = 'bisa';
                    }
                    $pengawas = true;
                } elseif (auth()->user()->hasRole('asisten-manajer-pengawas')) {
                    if ($pekerjaanUtama->status === 'koreksi pengawas') {
                        $tombolEdit = 'bisa';
                        $asmenpengawas = true;
                    }
                } elseif (auth()->user()->hasRole('asisten-manajer')) {
                    if ($pekerjaanUtama->status === 'koreksi pengawas') {
                        $tombolEdit = 'bisa';
                        $asmenpengawas = true;
                    }
                } elseif (auth()->user()->hasRole('manajer-perawatan')) {
                    if ($pekerjaanUtama->status === 'koreksi asmen') {
                        $tombolEdit = 'bisa';
                    }
                } elseif (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
                    if ($pekerjaanUtama->status === 'dikoreksi') {
                        $tombolEdit = 'bisa';
                        $asmenpengawas = true;
                    }
                }

                // else {
                //     if ($pekerjaanUtama->status === 'dikoreksi') {
                //         $tombolEdit = 'bisa';
                //     }
                // }

                $list_persetujuan = $penunjukan->list_persetujuan;
            }

            if (auth()->user()->hasRole('rekanan')) {
                $rekanan_id = auth()->user()->id_rekanan;
            }

            $notifikasi = Notifikasi::where('modul_id', $penunjukan->id)->where('to_user_id', auth()->user()->id)->first();
            if ($notifikasi) {
                $notifikasi->status = 'baca';
                $notifikasi->delete();
            }
        } else {
            $notifikasi = Notifikasi::where('modul_id', $aduan->id)->first();
            if ($notifikasi) {
                $notifikasi->status = 'baca';
                $notifikasi->delete();
            }
        }

        $jenisAduan = $aduan->hasJenisAduan->toArray();
        $jenis_aduan = JenisAduan::orderBy('nama')->get();
        $rekanan = Rekanan::orderBy('nama')->get();

        $title = 'Detail Pekerjaan ' . $aduan->nomor_pekerjaan;

        return view(
            'penunjukan_pekerjaan.show',
            compact(
                'aduan',
                'action',
                'pengawas',
                'asmenpengawas',
                'listPekerjaan',
                'listPekerjaanGalian',
                'list_persetujuan',
                'perencaan',
                'penunjukan',
                'pekerjaanUtama',
                'daftarPekerjaan',
                'daftarGalian',
                'daftarBahan',
                'daftarAlatBantu',
                'daftarTransportasi',
                'daftarDokumentasi',
                'lat_long_pekerjaan',
                'karyawanPekerja',
                'lokasi_pekerjaan',
                'rekanan_id',
                'jenisAduan',
                'jenis_aduan',
                'rekanan',
                'title',
                'totalPekerjaan',
                'fotoPekerjaan',
                'fotoPenyelesaian',
                'fotoBahan',
                'tombolEdit',
                'action'
            )
        );
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $message = 'Gagal Menyimpan Pelaksanaan Pekerjaan';

        $messages = [
            'required' => ':attribute tidak boleh kosong',
        ];

        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $this->validate(request(), [
            'rekanan_id' => 'required',
        ], $messages);

        try {
            DB::commit();
            $user_id = auth()->user()->id;
            $rekanan_id = $request->rekanan_id;
            $slug = $request->slug;

            $aduan = Aduan::where('slug', $slug)->first();

            $userAduan = User::find($aduan->user_id);

            $jabatan_id = Karyawan::where('user_id', $userAduan->id)->first()->jabatan_id;
            $jabatan = Jabatan::find($jabatan_id);

            $wilayah = "WIL-" . Wilayah::find($aduan->wilayah_id)->singkatan;
            if (strpos($jabatan, "Distribusi") !== false) {
                $divisi = "DIS";
            } else {
                $divisi = "PKA";
            }

            $notifikasi = Notifikasi::where('modul_id', $aduan->id)->first();
            if ($notifikasi) {
                $notifikasi->status = 'baca';
                $notifikasi->delete();
            }

            $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

            $kategori_aduan = $aduan->kategori_aduan;


            if ($kategori_aduan == 'pipa dinas') {
                $dataPenunjukanPerkerjaan = PenunjukanPekerjaan::where('kategori_aduan', 'pipa dinas')->whereBetween(DB::raw('DATE(created_at)'), array($start, $end))->count();
                if ($dataPenunjukanPerkerjaan >= 1) {
                    $no = str_pad($dataPenunjukanPerkerjaan + 1, 4, "0", STR_PAD_LEFT);
                    $nomor_pekerjaan = $no . "/" . "SPK-DS/" . $divisi . '/' . $wilayah . "/" . date('Y') . "/" . date('m') . "/" . date('d') . "/" . rand(0, 900);
                } else {
                    $no = str_pad(1, 4, "0", STR_PAD_LEFT);
                    $nomor_pekerjaan = $no . "/" . "SPK-DS/" . $divisi . '/' . $wilayah . "/" . date('Y') . "/" . date('m') . "/" . date('d') . "/" . rand(0, 900);
                }
            } else {
                $dataPenunjukanPerkerjaan = PenunjukanPekerjaan::where('kategori_aduan', 'pipa premier / skunder')->whereBetween(DB::raw('DATE(created_at)'), array($start, $end))->count();
                if ($dataPenunjukanPerkerjaan >= 1) {
                    $no = str_pad($dataPenunjukanPerkerjaan + 1, 4, "0", STR_PAD_LEFT);
                    $nomor_pekerjaan = $no . "/" . "SPK-SK/" . $divisi . '/' . $wilayah . "/" . date('Y') . "/" . date('m') . "/" . date('d') . "/" . rand(0, 900);
                } else {
                    $no = str_pad(1, 4, "0", STR_PAD_LEFT);
                    $nomor_pekerjaan = $no . "/" . "SPK-SK/" . $divisi . '/' . $wilayah . "/" . date('Y') . "/" . date('m') . "/" . date('d') . "/" . rand(0, 900);
                }
            }

            $penunjukanPekerjaan = PenunjukanPekerjaan::where('aduan_id', $aduan->id)->first();
            if ($penunjukanPekerjaan) {
                return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Aduan sudah dikerjakan')->with('Class', 'danger');
            } else {

                $data = new PenunjukanPekerjaan;
                $data->nomor_pekerjaan = $nomor_pekerjaan;
                $user_id_karayawan = '';

                $rekanan = Rekanan::find($rekanan_id);
                if (!empty($rekanan)) {
                    $data->rekanan_id = $rekanan_id;
                } else {
                    $karyawan = Karyawan::find($rekanan_id);
                    if ($karyawan) {
                        $user_id_karayawan = $karyawan->user_id;
                        $karyawan_id = $karyawan->id;
                        $data->karyawan_id = $karyawan_id;
                    }
                }
                $data->aduan_id = $aduan->id;
                $data->kategori_aduan = $kategori_aduan;
                $data->kategori_nps = Str::slug($divisi);
                $data->kategori_nps = $divisi;
                $data->user_id = $user_id;
                $data->status = 'draft';
                $data->save();

                $aduan->status = 'proses';
                $aduan->save();

                $title = "Penunjukan Pekerjaan";
                $body = "SPK " . $nomor_pekerjaan . " telah diterbitkan";
                $modul = "penunjukan-pekerjaan";


                if (!empty($rekanan)) {
                    // notif ke reknanan
                    $this->notification($data->id, $data->slug, $title, $body, $modul, auth()->user()->id, $rekanan->hasUser->id);

                    // notif ke staf pengawas
                    if ($rekanan->hasKaryawan) {
                        foreach (collect($rekanan->hasKaryawan) as $key => $value) {
                            $this->notification($data->id, $data->slug, $title, $body, $modul, auth()->user()->id, $value->user_id);
                        }
                    }
                }
                if ($user_id_karayawan != '') {
                    // / notif ke karyawan
                    $this->notification($data->id, $data->slug, $title, $body, $modul, auth()->user()->id, $user_id_karayawan);
                }

                // notif ke karyawan bedasarkan jabatan
                // list jabatan
                $listJabatan = Jabatan::Where('slug', 'manajer-perencanaan')
                    ->orWhere('slug', 'manajer-perawatan')
                    ->orWhere('slug', 'asisten-manajer-perencanaan')
                    ->orWhere('slug', 'asisten-manajer-pengawas')
                    ->orWhere('slug', 'direktur-teknik')
                    ->pluck('id')->toArray();

                // list karyawan bedasarkan jabatan
                $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get();
                if ($listKaryawan) {
                    foreach (collect($listKaryawan) as $i => $kr) {
                        $this->notification($data->id, $data->slug, $title, $body, $modul, auth()->user()->id, $kr->user_id);
                    }
                }

                // notif ke admin distribusi sesuai wilyah
                if ($aduan->wilayah_id) {
                    if ($divisi === "DIS") {
                        $jabatanWilayah = Jabatan::where('slug', "like", "admin-distribusi%")
                            ->orWhere('slug', "like", "asisten-manajer-distribusi%")
                            ->orWhere('slug', 'manajer-distribusi')
                            ->pluck('id')
                            ->toArray();
                    }
                    if ($divisi === "PKA") {
                        $jabatanWilayah = Jabatan::where('slug', "like", "admin-pengendalian-kehilangan-air%")
                            ->orWhere('slug', "like", "asisten-manajer-pengendalian-kehilangan-air%")
                            ->orWhere('slug', 'manajer-pengendalian-kehilangan-air')
                            ->pluck('id')
                            ->toArray();
                    }
                    if ($jabatanWilayah) {
                        $karyawanwilayah = Karyawan::whereIn('jabatan_id', $jabatanWilayah)->get();

                        if ($karyawanwilayah) {
                            foreach (collect($karyawanwilayah) as $in => $krw) {
                                $this->notification($data->id, $data->slug, $title, $body, $modul, auth()->user()->id, $krw->user_id);
                            }
                        }
                    }
                }
            }




            $message = 'Berhasil Menyimpan Pelaksanaan Pekerjaan';
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Penunjukan pekerjaan berhasil ditambah')->with('Class', 'primary');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', $message)->with('Class', 'danger');
        }
    }

    public function update(Request $request, $id)
    {
        $user = [];
        $listitem = [];
        $listitemPengawas = [];
        $listitemPerencanaan = [];
        $listitemAsmenPengawas = [];

        $cekItem = [];
        $cekItemPengawas = [];
        $cekGalianPengawas = [];
        $dataItem = [];
        $dataTotalGalianPerencanaan = [];

        $PelaksanaanPekerjaan = PelaksanaanPekerjaan::find($id);



        DB::beginTransaction();

        try {

            if ($PelaksanaanPekerjaan === null) {

                $penunjukanPekerjaan = PenunjukanPekerjaan::find($id);
                $rekanan_id = $request->rekanan_id;
                $rekanan = Rekanan::find($rekanan_id);
                if (!empty($rekanan)) {
                    $penunjukanPekerjaan->rekanan_id = $rekanan_id;
                } else {
                    $karyawan = Karyawan::find($rekanan_id);
                    if ($karyawan) {
                        $user_id_karayawan = $karyawan->user_id;
                        $karyawan_id = $karyawan->id;
                        $penunjukanPekerjaan->karyawan_id = $karyawan_id;
                    }
                }
                $aduan = Aduan::find($penunjukanPekerjaan->aduan_id);

                $penunjukanPekerjaan->status = 'draft';
                $penunjukanPekerjaan->save();

                $aduan->status = 'proses';
                $aduan->save();

                $nomor_pekerjaan = $penunjukanPekerjaan->nomor_pekerjaan;
                $title = "Perubahan Pekerjaan";
                $body = "SPK " . $nomor_pekerjaan . " telah diubah, ke rekanan " . $rekanan->nama;
                $modul = "penunjukan-pekerjaan";

                if (!empty($rekanan)) {
                    // notif ke reknanan
                    $this->notification($penunjukanPekerjaan->id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $rekanan->hasUser->id);

                    // notif ke staf pengawas
                    if ($rekanan->hasKaryawan) {
                        foreach (collect($rekanan->hasKaryawan) as $key => $value) {
                            $this->notification($penunjukanPekerjaan->id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $value->user_id);
                        }
                    }
                }

                // notif ke karyawan bedasarkan jabatan
                // list jabatan
                $listJabatan = Jabatan::Where('slug', 'manajer-perencanaan')->orWhere('slug', 'manajer-perawatan')->orWhere('slug', 'asisten-manajer-perencanaan')->orWhere('slug', 'asisten-manajer-pengawas')->orWhere('slug', 'direktur-teknik')->get()->pluck('id')->toArray();

                // list karyawan bedasarkan jabatan
                $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get();
                if ($listKaryawan) {
                    foreach (collect($listKaryawan) as $i => $kr) {
                        $this->notification($penunjukanPekerjaan->id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $kr->user_id);
                    }
                }

                // notif ke admin distribusi sesuai wilyah
                if ($aduan->wilayah_id) {
                    if ($aduan->kategori_nps === "dis") {
                        $jabatanWilayah = Jabatan::where('slug', "like", "admin-distribusi%")
                            ->orWhere('slug', "like", "asisten-manajer-distribusi%")
                            ->orWhere('slug', 'manajer-distribusi')
                            ->pluck('id')
                            ->toArray();
                    }
                    if ($aduan->kategori_nps === "pka") {
                        $jabatanWilayah = Jabatan::where('slug', "like", "admin-pengendalian-kehilangan-air%")
                            ->orWhere('slug', "like", "asisten-manajer-pengendalian-kehilangan-air%")
                            ->orWhere('slug', 'manajer-pengendalian-kehilangan-air')
                            ->pluck('id')
                            ->toArray();
                    }
                    if ($jabatanWilayah) {
                        $karyawanwilayah = Karyawan::whereIn('jabatan_id', $jabatanWilayah)->get();

                        if ($karyawanwilayah) {
                            foreach (collect($karyawanwilayah) as $in => $krw) {
                                $this->notification($penunjukanPekerjaan->id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $krw->user_id);
                            }
                        }
                    }
                }

                $message = "Pekerjaan dari SPK " . $penunjukanPekerjaan->nomor_pekerjaan . " berhasil diubah";
                DB::commit();

                return redirect()->route('penunjukan_pekerjaan.show', $aduan->slug)->with('message', $message)->with('Class', 'primary');
            } else {

                $PelaksanaanPekerjaan->status;
                if ($PelaksanaanPekerjaan->status_mobile < 2) {

                    $id_penunjukan = $PelaksanaanPekerjaan->penunjukan_pekerjaan_id;
                    $penunjukanPekerjaan = PenunjukanPekerjaan::find($id_penunjukan);
                    $rekanan_id = $request->rekanan_id;
                    $rekanan = Rekanan::find($rekanan_id);

                    if (!empty($rekanan)) {
                        $penunjukanPekerjaan->rekanan_id = $rekanan_id;
                        $PelaksanaanPekerjaan->rekanan_id = $rekanan_id;
                    } else {
                        $karyawan = Karyawan::find($rekanan_id);
                        if ($karyawan) {
                            $user_id_karayawan = $karyawan->user_id;
                            $karyawan_id = $karyawan->id;
                            $penunjukanPekerjaan->rekanan_id = null;
                            $penunjukanPekerjaan->karyawan_id = $karyawan_id;
                            $PelaksanaanPekerjaan->karyawan_id = $karyawan_id;
                        }
                    }
                    $aduan = Aduan::find($penunjukanPekerjaan->aduan_id);

                    $aduan->status = "proses";
                    $aduan->save();

                    $penunjukanPekerjaan->status = "draft";
                    $penunjukanPekerjaan->save();

                    $PelaksanaanPekerjaan->delete();
                    $PelaksanaanPekerjaan->hasUserMany()->detach();

                    $nomor_pekerjaan = $penunjukanPekerjaan->nomor_pekerjaan;
                    $title = "Perubahan Pekerjaan";
                    $body = "SPK " . $nomor_pekerjaan . " telah diubah, ke rekanan " . $rekanan->nama;
                    $modul = "penunjukan-pekerjaan";

                    if (!empty($rekanan)) {
                        // notif ke reknanan
                        $this->notification($penunjukanPekerjaan->id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $rekanan->hasUser->id);

                        // notif ke staf pengawas
                        if ($rekanan->hasKaryawan) {
                            foreach (collect($rekanan->hasKaryawan) as $key => $value) {
                                $this->notification($penunjukanPekerjaan->id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $value->user_id);
                            }
                        }
                    }


                    // notif ke karyawan bedasarkan jabatan
                    // list jabatan
                    $listJabatan = Jabatan::Where('slug', 'manajer-perencanaan')->orWhere('slug', 'manajer-pengendalian-kehilangan-air')->orWhere('slug', 'manajer-perawatan')->orWhere('slug', 'asisten-manajer-perencanaan')->orWhere('slug', 'asisten-manajer-pengawas')->orWhere('slug', 'direktur-teknik')->get()->pluck('id')->toArray();

                    // list karyawan bedasarkan jabatan
                    $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get();
                    if ($listKaryawan) {
                        foreach (collect($listKaryawan) as $i => $kr) {
                            $this->notification($penunjukanPekerjaan->id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $kr->user_id);
                        }
                    }


                    // notif ke admin distribusi sesuai wilyah
                    if ($aduan->wilayah_id) {
                        if ($aduan->kategori_nps === "dis") {
                            $jabatanWilayah = Jabatan::where('slug', "like", "admin-distribusi%")
                                ->orWhere('slug', "like", "asisten-manajer-distribusi%")
                                ->orWhere('slug', 'manajer-distribusi')
                                ->pluck('id')
                                ->toArray();
                        }
                        if ($aduan->kategori_nps === "pka") {
                            $jabatanWilayah = Jabatan::where('slug', "like", "admin-pengendalian-kehilangan-air%")
                                ->orWhere('slug', "like", "asisten-manajer-pengendalian-kehilangan-air%")
                                ->orWhere('slug', 'manajer-pengendalian-kehilangan-air')

                                ->pluck('id')
                                ->toArray();
                        }
                        if ($jabatanWilayah) {
                            $karyawanwilayah = Karyawan::whereIn('jabatan_id', $jabatanWilayah)->get();

                            if ($karyawanwilayah) {
                                foreach (collect($karyawanwilayah) as $in => $krw) {
                                    $this->notification($penunjukanPekerjaan->id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $krw->user_id);
                                }
                            }
                        }
                    }

                    $message = "Rekanan atau pekerja dari SPK " . $nomor_pekerjaan . " berhasil diubah";
                    DB::commit();

                    return redirect()->route('penunjukan_pekerjaan.show', $aduan->slug)->with('message', $message)->with('Class', 'primary');
                } else {
                    if (auth()->user()->hasRole('asisten-manajer-distribusi')) {
                        $status = 'approve';
                    } elseif (auth()->user()->hasRole('asisten-manajer-pengendalian-kehilangan-air')) {
                        $status = 'approve';
                    } elseif (auth()->user()->hasRole('manajer-distribusi')) {
                        $status = 'approve manajer';
                    } elseif (auth()->user()->hasRole('manajer-pengendalian-kehilangan-air')) {
                        $status = 'approve manajer';
                    } elseif (auth()->user()->hasRole('staf-pengawas')) {
                        // pekerjaan
                        if ($PelaksanaanPekerjaan->status === 'approve manajer') {

                            if ($request->qty_pengawas) {
                                foreach ($request->qty_pengawas as $key => $value) {

                                    $cekItem[$key] = PelakasanaanItem::where('item_id', $key)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan->id)->first();

                                    $dataItem[$key] = Item::find($key);

                                    if ($cekItem[$key]) {
                                        $listitem[$key] = [
                                            'keterangan' => $cekItem[$key]->keterangan,
                                            'harga' => $cekItem[$key]->harga,
                                            'qty' => $cekItem[$key]->qty,
                                            'total' => str_replace(",", ".", $value) * $cekItem[$key]->harga,
                                        ];
                                        $listitemPengawas[$key] = [
                                            'keterangan' => isset($request->keterangan_pengawas[$key]) ? $request->keterangan_pengawas[$key] : null,
                                            'harga' => $cekItem[$key]->harga,
                                            'qty' => str_replace(",", ".", $value),
                                            'total' => str_replace(",", ".", $value) * $cekItem[$key]->harga,
                                        ];
                                    } else {

                                        if ($dataItem[$key]) {
                                            $listitem[$key] = [
                                                'keterangan' => null,
                                                'harga' => isset($request->jenis_harga[$key]) && $request->jenis_harga[$key] === "siang" ? $dataItem[$key]->harga : $dataItem[$key]->harga_malam,
                                                'qty' => 0,
                                                'total' => (float) $value * (float) isset($request->jenis_harga[$key]) && $request->jenis_harga[$key] === "siang" ? $dataItem[$key]->harga : $dataItem[$key]->harga_malam,
                                            ];
                                            $listitemPengawas[$key] = [
                                                'keterangan' => isset($request->keterangan_pengawas[$key]) ? $request->keterangan_pengawas[$key] : null,
                                                'harga' => isset($request->jenis_harga[$key]) && $request->jenis_harga[$key] === "siang" ? $dataItem[$key]->harga : $dataItem[$key]->harga_malam,
                                                'qty' => str_replace(",", ".", $value),
                                                'total' => (float) isset($request->jenis_harga[$key]) && $request->jenis_harga[$key] === "siang" ? $dataItem[$key]->harga : $dataItem[$key]->harga_malam * str_replace(",", ".", $value)
                                            ];
                                        }
                                    }
                                }
                            }
                            // end pekerjaan
                            $status = 'koreksi pengawas';
                            // $status = $PelaksanaanPekerjaan->status;
                        } else {
                            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Pekerjaan gagal disetujui')->with('Class', 'danger');
                        }
                    } else if (auth()->user()->hasRole('asisten-manajer-pengawas')) {
                        // pekerjaan
                        if ($PelaksanaanPekerjaan->status === 'koreksi pengawas') {
                            if ($request->qty_pengawas) {
                                foreach ($request->qty_pengawas as $key => $value) {

                                    $cekItem[$key] = PelakasanaanItem::where('item_id', $key)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan->id)->first();

                                    $dataItem[$key] = Item::find($key);

                                    if ($cekItem[$key]) {
                                        $cekItemPengawas[$key] = PelakasanaanPengawas::where('item_id', $key)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan->id)->first();

                                        if ($cekItemPengawas[$key]) {

                                            $listitem[$key] = [
                                                'keterangan' => $cekItem[$key]->keterangan,
                                                'harga' => $cekItem[$key]->harga,
                                                'qty' => $cekItem[$key]->qty,
                                                'total' => str_replace(",", ".", $value) * $cekItemPengawas[$key]->harga,
                                            ];
                                            $listitemPengawas[$key] = [
                                                'keterangan' => $cekItemPengawas[$key]->keterangan,
                                                'harga' => $cekItemPengawas[$key]->harga,
                                                'qty' => $cekItemPengawas[$key]->qty,
                                                'total' => str_replace(",", ".", $value) * $cekItemPengawas[$key]->harga,
                                            ];
                                            $listitemAsmenPengawas[$key] = [
                                                'keterangan' => isset($request->keterangan_pengawas[$key]) ? $request->keterangan_pengawas[$key] : null,
                                                'harga' => $cekItemPengawas[$key]->harga,
                                                'qty' => str_replace(",", ".", $value),
                                                'total' => str_replace(",", ".", $value) * $cekItemPengawas[$key]->harga,
                                            ];
                                        }
                                    } else {
                                        if ($dataItem[$key]) {
                                            $listitem[$key] = [
                                                'keterangan' => null,
                                                'harga' => isset($request->jenis_harga[$key]) && $request->jenis_harga[$key] === "siang" ? $dataItem[$key]->harga : $dataItem[$key]->harga_malam,
                                                'qty' => 0,
                                                'total' => (float) isset($request->jenis_harga[$key]) && $request->jenis_harga[$key] === "siang" ? $dataItem[$key]->harga : $dataItem[$key]->harga_malam * str_replace(",", ".", $value),
                                            ];
                                            $listitemPengawas[$key] = [
                                                'keterangan' => null,
                                                'harga' => isset($request->jenis_harga[$key]) && $request->jenis_harga[$key] === "siang" ? $dataItem[$key]->harga : $dataItem[$key]->harga_malam,
                                                'qty' => 0,
                                                'total' => (float) isset($request->jenis_harga[$key]) && $request->jenis_harga[$key] === "siang" ? $dataItem[$key]->harga : $dataItem[$key]->harga_malam * str_replace(",", ".", $value),
                                            ];
                                            $listitemAsmenPengawas[$key] = [
                                                'keterangan' => isset($request->keterangan_pengawas[$key]) ? $request->keterangan_pengawas[$key] : null,
                                                'harga' => isset($request->jenis_harga[$key]) && $request->jenis_harga[$key] === "siang" ? $dataItem[$key]->harga : $dataItem[$key]->harga_malam,
                                                'qty' => str_replace(",", ".", $value),
                                                'total' => (float) isset($request->jenis_harga[$key]) && $request->jenis_harga[$key] === "siang" ? $dataItem[$key]->harga : $dataItem[$key]->harga_malam * str_replace(",", ".", $value),
                                            ];
                                        }
                                    }
                                }
                            }

                            // end pekerjaan;
                            $status = 'koreksi asmen';
                        } else {
                            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Pekerjaan gagal disetujui')->with('Class', 'danger');
                        }
                        // $status = $PelaksanaanPekerjaan->status;
                    } else if (auth()->user()->hasRole('manajer-perawatan')) {
                        // pekerjaan
                        if ($PelaksanaanPekerjaan->status === 'koreksi asmen') {

                            $status = 'dikoreksi';
                        } else {
                            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Pekerjaan gagal disetujui')->with('Class', 'danger');
                        }
                        // $status = $PelaksanaanPekerjaan->status;
                    } else if (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
                        if ($PelaksanaanPekerjaan->status === 'dikoreksi') {

                            $status = 'selesai koreksi';
                            $PelaksanaanPekerjaan->keterangan_barang = '';
                            if ($request->harga_perencanaan_pekerjaan) {
                                foreach ($request->harga_perencanaan_pekerjaan as $key => $value) {

                                    $cekItem[$key] = PelakasanaanItem::where('item_id', $key)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan->id)->first();
                                    if ($cekItem[$key]) {


                                        $cekItemPengawas[$key] = PelakasanaanPengawas::where('item_id', $key)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan->id)->first();

                                        $cekItemAsmenPengawas[$key] = PelakasanaanAsmen::where('item_id', $key)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan->id)->first();
                                        if ($cekItemAsmenPengawas[$key]) {

                                            // rekanan
                                            $listitem[$key] = [
                                                'keterangan' => $cekItem[$key]->keterangan,
                                                'harga' => $cekItem[$key]->harga,
                                                'qty' => $cekItem[$key]->qty,
                                                'total' => str_replace(".", "", $value) * $cekItemAsmenPengawas[$key]->qty,
                                            ];

                                            // harga pengawas
                                            $listitemPengawas[$key] = [
                                                'keterangan' => $cekItemPengawas[$key]->keterangan,
                                                'harga' => $cekItemPengawas[$key]->harga,
                                                'qty' => $cekItemPengawas[$key]->qty,
                                                'total' => str_replace(".", "", $value) * $cekItemAsmenPengawas[$key]->qty,
                                            ];
                                            $listitemAsmenPengawas[$key] = [
                                                'keterangan' => $cekItemAsmenPengawas[$key]->keterangan,
                                                'harga' => $cekItemAsmenPengawas[$key]->harga,
                                                'qty' => $cekItemAsmenPengawas[$key]->qty,
                                                'total' => str_replace(".", "", $value) * $cekItemAsmenPengawas[$key]->qty,
                                            ];
                                            // harga perencanaan
                                            $listitemPerencanaan[$key] = [
                                                'keterangan' => isset($request->keterangan_perencanaan_pekerjaan[$key]) ? $request->keterangan_perencanaan_pekerjaan[$key] : null,
                                                'harga' => str_replace(".", "", $value),
                                                'total' => str_replace(".", "", $value) * $cekItemAsmenPengawas[$key]->qty,
                                            ];
                                        }
                                    }
                                }
                            }
                        } else if ($PelaksanaanPekerjaan->status === 'selesai koreksi') {
                            $status = 'diadjust';
                            $PelaksanaanPekerjaan->keterangan_barang = '';

                        } else {
                            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Pekerjaan gagal disetujui')->with('Class', 'danger');
                        }


                    }

                    if ($PelaksanaanPekerjaan) {

                        $PelaksanaanPekerjaan->status = $status;
                        $PelaksanaanPekerjaan->save();

                        // koreksi yang ada angkanya
                        if (auth()->user()->hasRole('staf-pengawas')) {
                            // galian pengawas
                            $cekItemGalian = [];
                            $datapanjang = [];
                            $datalebar = [];
                            $datadalam = [];
                            $dataketerangan = [];
                            $dataTotalGalian = [];
                            $datahargagalian = [];
                            $dataIdGalianPekerjaan = [];
                            if (isset($request->panjang_pengawas)) {
                                foreach ($request->panjang_pengawas as $in => $gal) {
                                    $cekItemGalian[$in] = GalianPekerjaan::where('item_id', $in)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan->id)->first();

                                    $datapanjang[$in] = str_replace(",", ".", $gal);
                                    $datalebar[$in] = isset($request->lebar_pengawas[$in]) ? str_replace(",", ".", $request->lebar_pengawas[$in]) : 0;
                                    $datadalam[$in] = isset($request->dalam_pengawas[$in]) ? str_replace(",", ".", $request->dalam_pengawas[$in]) : 0;
                                    $dataketerangan[$in] = isset($request->keterangan_pengawas_galian[$in]) ? $request->keterangan_pengawas_galian[$in] : null;

                                    $datahargagalian[$in] = isset($request->jenis_harga_galian[$in]) ? $request->jenis_harga_galian[$in] : null;

                                    $dataItem[$in] = Item::find($in);

                                    $harga_satuan[$in] = isset($request->jenis_harga_galian[$in]) && $request->jenis_harga_galian[$in] === "siang" ? $dataItem[$in]->harga : $dataItem[$in]->harga_malam;


                                    if ($cekItemGalian[$in]) {
                                        $harga_satuan[$in] = $cekItemGalian[$in]->harga_satuan;

                                        $dataTotalGalian[$in] = $request->dalam_pengawas[$in] === "0.00" ? (str_replace(",", ".", $gal) * $datalebar[$in]) * $harga_satuan[$in] : (str_replace(",", ".", $gal) * $datalebar[$in] * $datadalam[$in]) * $harga_satuan[$in];

                                        // update galian
                                        $cekItemGalian[$in]->total = $dataTotalGalian[$in];
                                        $cekItemGalian[$in]->save();
                                        // create galian pengawas
                                        $dataIdGalianPekerjaan[$in] = $cekItemGalian[$in]->id;
                                    } else {

                                        // data total
                                        $dataTotalGalian[$in] = $request->dalam_pengawas[$in] === "0.00" ? (str_replace(",", ".", $gal) * $datalebar[$in]) * $harga_satuan[$in] : (str_replace(",", ".", $gal) * $datalebar[$in] * $datadalam[$in]) * $harga_satuan[$in];

                                        // create galian
                                        $newGajian[$in] = new GalianPekerjaan;
                                        $newGajian[$in]->pelaksanaan_pekerjaan_id = $PelaksanaanPekerjaan->id;
                                        $newGajian[$in]->item_id = $in;
                                        $newGajian[$in]->panjang = 0;
                                        $newGajian[$in]->lebar = 0;
                                        $newGajian[$in]->dalam = 0;

                                        // harga satuan
                                        $newGajian[$in]->harga_satuan = isset($request->jenis_harga_galian[$in]) && $request->jenis_harga_galian[$in] === "siang" ? $dataItem[$in]->harga : $dataItem[$in]->harga_malam;

                                        $newGajian[$in]->total = $dataTotalGalian[$in];

                                        // jenis harga
                                        $newGajian[$in]->harga = isset($request->jenis_harga_galian[$in]) ? $request->jenis_harga_galian[$in] : "siang";
                                        $newGajian[$in]->user_id = auth()->user()->id;

                                        $newGajian[$in]->save();

                                        // create galian pengawas

                                        $dataIdGalianPekerjaan[$in] = $newGajian[$in]->id;
                                    }
                                    $newGajianPengawas[$in] = GalianPengawas::where('galian_id', $dataIdGalianPekerjaan[$in])->first();
                                    if ($dataIdGalianPekerjaan[$in] != null) {
                                        $newGajianPengawas[$in] = new GalianPengawas;
                                    }

                                    $newGajianPengawas[$in]->galian_id = $dataIdGalianPekerjaan[$in];
                                    $newGajianPengawas[$in]->item_id = $in;
                                    $newGajianPengawas[$in]->panjang = $datapanjang[$in];
                                    $newGajianPengawas[$in]->lebar = $datalebar[$in];
                                    $newGajianPengawas[$in]->dalam = $datadalam[$in];
                                    $newGajianPengawas[$in]->harga_satuan = $harga_satuan[$in];
                                    $newGajianPengawas[$in]->total = $dataTotalGalian[$in];
                                    $newGajianPengawas[$in]->keterangan = $dataketerangan[$in];
                                    $newGajianPengawas[$in]->user_id = auth()->user()->id;
                                    $newGajianPengawas[$in]->save();
                                }
                                // end galian
                            }
                            if (isset($listitem) || isset($listitemPengawas)) {
                                $PelaksanaanPekerjaan->hasItem()->sync($listitem);
                                $PelaksanaanPekerjaan->hasItemPengawas()->sync($listitemPengawas);
                            }

                        }

                        if (auth()->user()->hasRole('asisten-manajer-pengawas')) {
                            // galian
                            // galian pengawas

                            $cekItemGalian = [];
                            $cekItemGalianPengawas = [];
                            $datapanjang = [];
                            $datalebar = [];
                            $datadalam = [];
                            $dataketerangan = [];
                            $dataTotalGalian = [];
                            $datahargagalian = [];
                            $dataIdGalianPekerjaan = [];
                            if (isset($request->panjang_pengawas)) {
                                foreach ($request->panjang_pengawas as $in => $gal) {
                                    $cekItemGalian[$in] = GalianPekerjaan::where('item_id', $in)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan->id)->first();
                                    $cekItemGalianPengawas[$in] = GalianPekerjaan::where('item_id', $in)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan->id)->first();

                                    $datapanjang[$in] = (float) str_replace(",", ".", $gal);
                                    $datalebar[$in] = isset($request->lebar_pengawas[$in]) ? (float) str_replace(",", ".", $request->lebar_pengawas[$in]) : 0;
                                    $datadalam[$in] = isset($request->dalam_pengawas[$in]) ? (float) str_replace(",", ".", $request->dalam_pengawas[$in]) : 0;
                                    $dataketerangan[$in] = isset($request->keterangan_pengawas_galian[$in]) ? $request->keterangan_pengawas_galian[$in] : null;

                                    $datahargagalian[$in] = isset($request->jenis_harga_galian[$in]) ? $request->jenis_harga_galian[$in] : null;

                                    $dataItem[$in] = Item::find($in);

                                    $harga_satuan[$in] = isset($request->jenis_harga_galian[$in]) && $request->jenis_harga_galian[$in] === "siang" ? $dataItem[$in]->harga : $dataItem[$in]->harga_malam;


                                    if ($cekItemGalian[$in]) {
                                        $harga_satuan[$in] = (float) $cekItemGalian[$in]->harga_satuan;

                                        $dataTotalGalian[$in] =
                                            $request->dalam_pengawas[$in] === 0.00 ? ($datapanjang[$in] * $datalebar[$in]) * $harga_satuan[$in] : ($datapanjang[$in] * $datalebar[$in] * $datadalam[$in]) * $harga_satuan[$in];

                                        // update galian
                                        $cekItemGalian[$in]->total = $dataTotalGalian[$in];
                                        $cekItemGalian[$in]->save();
                                        // update pengawas
                                        $cekItemGalianPengawas[$in]->total = $dataTotalGalian[$in];
                                        $cekItemGalianPengawas[$in]->save();
                                        // create galian pengawas
                                        $dataIdGalianPekerjaan[$in] = $cekItemGalian[$in]->id;
                                    } else {

                                        // data total
                                        $dataTotalGalian[$in] = $request->dalam_pengawas[$in] === "0" ? ($datapanjang[$in] * $datalebar[$in]) * $harga_satuan[$in] : ($datapanjang[$in] * $datalebar[$in] * $datadalam[$in]) * $harga_satuan[$in];

                                        // create galian
                                        $newGajian[$in] = new GalianPekerjaan;
                                        $newGajian[$in]->pelaksanaan_pekerjaan_id = $PelaksanaanPekerjaan->id;
                                        $newGajian[$in]->item_id = $in;
                                        $newGajian[$in]->panjang = 0;
                                        $newGajian[$in]->lebar = 0;
                                        $newGajian[$in]->dalam = 0;

                                        // harga satuan
                                        $newGajian[$in]->harga_satuan = isset($request->jenis_harga_galian[$in]) && $request->jenis_harga_galian[$in] === "siang" ? $dataItem[$in]->harga : $dataItem[$in]->harga_malam;

                                        $newGajian[$in]->total = $dataTotalGalian[$in];

                                        // jenis harga
                                        $newGajian[$in]->harga = isset($request->jenis_harga_galian[$in]) ? $request->jenis_harga_galian[$in] : "siang";
                                        $newGajian[$in]->user_id = auth()->user()->id;

                                        $newGajian[$in]->save();

                                        // create galian pengawas

                                        $dataIdGalianPekerjaan[$in] = $newGajian[$in]->id;
                                    }
                                    $newGajianPengawas[$in] = GalianAsmen::where('galian_id', $dataIdGalianPekerjaan[$in])->first();
                                    if ($dataIdGalianPekerjaan[$in] != null) {
                                        $newGajianPengawas[$in] = new GalianAsmen;
                                    }

                                    $newGajianPengawas[$in]->galian_id = $dataIdGalianPekerjaan[$in];
                                    $newGajianPengawas[$in]->item_id = $in;
                                    $newGajianPengawas[$in]->panjang = $datapanjang[$in];
                                    $newGajianPengawas[$in]->lebar = $datalebar[$in];
                                    $newGajianPengawas[$in]->dalam = $datadalam[$in];
                                    $newGajianPengawas[$in]->harga_satuan = $harga_satuan[$in];
                                    $newGajianPengawas[$in]->total = $dataTotalGalian[$in];
                                    $newGajianPengawas[$in]->keterangan = $dataketerangan[$in];
                                    $newGajianPengawas[$in]->user_id = auth()->user()->id;
                                    $newGajianPengawas[$in]->save();
                                }
                            }
                            // end galian
                            if (isset($listitem) || isset($listitemPengawas) || $listitemAsmenPengawas) {
                                $PelaksanaanPekerjaan->hasItem()->sync($listitem);
                                $PelaksanaanPekerjaan->hasItemPengawas()->sync($listitemPengawas);
                                $PelaksanaanPekerjaan->hasItemAsmenPengawas()->sync($listitemAsmenPengawas);
                            }

                        }
                        if (auth()->user()->hasRole('asisten-manajer-perencanaan')) {

                            if ($request->harga_perencanaan_pekerjaan) {
                                $PelaksanaanPekerjaan->hasItem()->sync($listitem);
                                $PelaksanaanPekerjaan->hasItemPengawas()->sync($listitemPengawas);
                                $PelaksanaanPekerjaan->hasItemPerencanaan()->sync($listitemPerencanaan);
                            }

                            $newGalianPerencanaan = [];
                            if ($request->harga_galian) {
                                foreach ($request->harga_galian as $gal => $galian) {
                                    $cekItemGalian[$gal] = GalianPekerjaan::find($gal);

                                    $cekGalianPengawas[$gal] = GalianPengawas::where('galian_id', $gal)->first();
                                    $cekGalianAsmenPengawas[$gal] = GalianAsmen::where('galian_id', $gal)->first();

                                    if (isset($cekGalianAsmenPengawas[$gal])) {
                                        if ($cekItemGalian[$gal] != null) {
                                            $dataTotalGalianPerencanaan[$gal] = $cekGalianAsmenPengawas[$gal]->volume_asmen * str_replace(".", "", $galian);

                                            // update galian rekanan
                                            $cekItemGalian[$gal]->total = $dataTotalGalianPerencanaan[$gal];
                                            $cekItemGalian[$gal]->save();
                                            if ($cekGalianAsmenPengawas[$gal]) {

                                                // update galian pengawas
                                                // if (isset($cekGalianAsmenPengawas[$gal])) {

                                                //     $cekGalianPengawas[$gal]->total = $dataTotalGalianPerencanaan[$gal];
                                                //     $cekGalianPengawas[$gal]->save();
                                                // }

                                                $cekGalianAsmenPengawas[$gal]->total = $dataTotalGalianPerencanaan[$gal];
                                                $cekGalianAsmenPengawas[$gal]->save();

                                                // GalianPengawas ada
                                                $newGalianPerencanaan[$gal] = new GalianPerencanaan;
                                                $newGalianPerencanaan[$gal]->galian_id = $cekItemGalian[$gal]->id;
                                                $newGalianPerencanaan[$gal]->item_id = isset($cekItemGalian[$gal]->item_id) ? $cekItemGalian[$gal]->item_id : null;
                                                $newGalianPerencanaan[$gal]->total = $dataTotalGalianPerencanaan[$gal];
                                                $newGalianPerencanaan[$gal]->harga_satuan = str_replace(".", "", $galian);
                                                $newGalianPerencanaan[$gal]->keterangan = isset($request->keterangan_perencanaa_galian[$gal]) ? $request->keterangan_perencanaa_galian[$gal] : null;
                                                $newGalianPerencanaan[$gal]->user_id = auth()->user()->id;
                                                $newGalianPerencanaan[$gal]->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $user[auth()->user()->id] = [
                            'keterangan' => $status,
                        ];
                        $PelaksanaanPekerjaan->hasUserMany()->attach($user);

                        $penunjukanPekerjaan = PenunjukanPekerjaan::find($PelaksanaanPekerjaan->penunjukan_pekerjaan_id);

                        if ($penunjukanPekerjaan) {
                            $nomor_pekerjaan = $penunjukanPekerjaan->nomor_pekerjaan;
                            $penunjukanPekerjaan->status = $status;
                            $penunjukanPekerjaan->updated_at = Carbon::now();
                            $penunjukanPekerjaan->save();

                            $penunjukanPekerjaan->hasUserMany()->attach($user);
                            $rekanan = Rekanan::find($PelaksanaanPekerjaan->rekanan_id)->first();
                            $title = "Pekerjaan Telah dikoreksi";
                            $body = "SPK " . $nomor_pekerjaan . " telah dikoreksi";
                            $modul = "penunjukan-pekerjaan";



                            // return  $penunjukanPekerjaan;
                            $message = 'Berhasil Mengoreksi Pelaksanaan Pekerjaan';
                            $aduan = Aduan::find($penunjukanPekerjaan->aduan_id);
                            if ($aduan) {
                                $aduan->updated_at = Carbon::now();
                                $aduan->save();
                            }

                            if (!empty($rekanan)) {
                                // notif ke reknanan
                                $this->notification($penunjukanPekerjaan->aduan_id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $rekanan->hasUser->id);

                                // notif ke staf pengawas
                                if ($rekanan->hasKaryawan) {
                                    foreach (collect($rekanan->hasKaryawan) as $key => $value) {
                                        $this->notification($penunjukanPekerjaan->id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $value->user_id);
                                    }
                                }
                            }

                            // notif ke admin distribusi sesuai wilyah
                            if ($aduan->wilayah_id) {
                                $jabatanWilayah = Jabatan::query();

                                if ($aduan->kategori_nps === "dis") {
                                    $jabatanWilayah = $jabatanWilayah->orwhere('slug', "like", "admin-distribusi%")->orWhere('slug', 'manajer-distribusi');
                                }
                                if ($aduan->kategori_nps === "pka") {
                                    $jabatanWilayah = $jabatanWilayah->orWhere('slug', "like", "admin-pengendalian-kehilangan-air%")->orWhere('slug', 'manajer-pengendalian-kehilangan-air%');
                                }

                                $jabatanWilayah = $jabatanWilayah->orWhere('wilayah_id', $aduan->wilayah_id)
                                    ->orWhere('slug', 'manajer-perawatan')
                                    ->orWhere('slug', 'asisten-manajer-perencanaan')
                                    ->orWhere('slug', 'asisten-manajer-pengawas')
                                    ->pluck('id')
                                    ->toArray();

                                if ($jabatanWilayah) {
                                    $karyawanwilayah = Karyawan::whereIn('jabatan_id', $jabatanWilayah)->get();

                                    if ($karyawanwilayah) {
                                        foreach (collect($karyawanwilayah) as $in => $krw) {
                                            $this->notification($penunjukanPekerjaan->id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $krw->user_id);
                                        }
                                    }
                                }
                            }

                            DB::commit();
                            return redirect()->route('penunjukan_pekerjaan.show', $aduan->slug)->with('message', $message)->with('Class', 'primary');
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', "Jaringan Bermasalah")->with('Class', 'danger');
        }
    }

    public function updateAdjust(Request $request, $id)
    {
        $user = [];
        $listitem = [];
        $cekItem = [];
        $listitemPengawas = [];
        $listitemPerencanaan = [];
        $cekGalianPengawas = [];
        $dataTotalGalianPerencanaan = [];

        $PelaksanaanPekerjaan = PelaksanaanPekerjaan::find($id);
        $PelaksanaanPekerjaan->status;

        // pekerjaan
        DB::beginTransaction();
        try {
            if ($request->qty_perencanaan) {
                foreach ($request->qty_perencanaan as $key => $value) {

                    $cekItem[$key] = PelakasanaanItem::where('item_id', $key)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan->id)->first();
                    $harga_satuan[$key] = str_replace(".", "", $request->harga_satuan[$key]);
                    if ($cekItem[$key]) {
                        $listitem[$key] = [
                            'keterangan' => $cekItem[$key]->keterangan,
                            'harga' => $cekItem[$key]->harga,
                            'qty' => $cekItem[$key]->qty,
                            'total' => ($harga_satuan[$key] * str_replace(",", ".", $value)),
                        ];
                        $listitemPerencanaan[$key] = [
                            'keterangan' => isset($request->keterangan_perencanaan[$key]) ? $request->keterangan_perencanaan[$key] : null,
                            'harga' => isset($request->harga_satuan[$key]) ? (float) str_replace(".", "", $request->harga_satuan[$key]) : null,
                            'qty' => str_replace(",", ".", $value),
                            'total' => ($harga_satuan[$key] * str_replace(",", ".", $value)),
                        ];
                    } else {
                        $listitem[$key] = [
                            'keterangan' => null,
                            'harga' => isset($request->harga_satuan[$key]) ? (float) str_replace(".", "", $request->harga_satuan[$key]) : 0,
                            'qty' => str_replace(",", ".", $value),
                            'total' => ($harga_satuan[$key] * str_replace(",", ".", $value)),
                        ];
                        $listitemPerencanaan[$key] = [
                            'keterangan' => isset($request->keterangan_perencanaan[$key]) ? $request->keterangan_perencanaan[$key] : null,
                            'harga' => isset($request->harga_satuan[$key]) ? (float) str_replace(".", "", $request->harga_satuan[$key]) : null,
                            'qty' => str_replace(",", ".", $value),
                            'total' => ($harga_satuan[$key] * str_replace(",", ".", $value)),
                        ];
                    }
                }
            }
            // end pekerjaan

            // return $listitem;

            if (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
                if ($PelaksanaanPekerjaan->status === 'selesai koreksi') {
                    $status = 'diadjust';
                    $PelaksanaanPekerjaan->keterangan_barang = '';

                } else {
                    return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Pekerjaan gagal diadjust')->with('Class', 'danger');
                }
            }

            // return $request;

            if ($PelaksanaanPekerjaan) {

                $user[auth()->user()->id] = [
                    'keterangan' => $status,
                ];

                $PelaksanaanPekerjaan->status = $status;
                $PelaksanaanPekerjaan->save();
                if (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
                    if ($request->qty_perencanaan) {
                        $PelaksanaanPekerjaan->hasItem()->sync($listitem);
                        $PelaksanaanPekerjaan->hasItemPerencanaanAdujst()->sync($listitemPerencanaan);
                    }
                    // galian
                    if ($request->panjang_perencanaan) {
                        foreach ($request->panjang_perencanaan as $gal => $galian) {
                            $cekItemGalian[$gal] = GalianPekerjaan::find($gal);

                            $cekGalianPengawas[$gal] = GalianPengawas::where('galian_id', $gal)->first();
                            $cekGalianAsmenPengawas[$gal] = GalianAsmen::where('galian_id', $gal)->first();
                            $harga_satuan[$gal] = str_replace(".", "", $request->harga_satuan_galian[$gal]);

                            $datapanjang[$gal] = str_replace(",", ".", $galian);

                            $datadalam[$gal] = isset($request->dalam_perencanaan[$gal]) ? str_replace(",", ".", $request->dalam_perencanaan[$gal]) : 0;

                            $datalebar[$gal] = isset($request->lebar_perencanaan[$gal]) ? str_replace(",", ".", $request->lebar_perencanaan[$gal]) : 0;
                            $dataketerangan[$gal] = isset($request->keterangan_perencanaan_galian[$gal]) ? $request->keterangan_perencanaan_galian[$gal] : null;

                            $dataTotalGalianPerencanaan[$gal] = $datadalam[$gal] === 0.00 ? ((float) $datapanjang[$gal]
                                * (float) $datalebar[$gal])
                                * (float) $harga_satuan[$gal] : ((float) $datapanjang[$gal] *
                                    (float) $datalebar[$gal] *
                                    (float) $datadalam[$gal]) *
                                (float) $harga_satuan[$gal];

                            if ($cekItemGalian[$gal]) {
                                if ($cekGalianAsmenPengawas[$gal]) {
                                    // update galian rekanan
                                    $cekItemGalian[$gal]->total = $dataTotalGalianPerencanaan[$gal];
                                    $cekItemGalian[$gal]->save();
                                    //  update galian pengawas
                                    if ($cekGalianPengawas[$gal]) {
                                        $cekGalianPengawas[$gal]->total = $dataTotalGalianPerencanaan[$gal];
                                        $cekGalianPengawas[$gal]->save();
                                    }

                                    $cekGalianAsmenPengawas[$gal]->total = $dataTotalGalianPerencanaan[$gal];
                                    $cekGalianAsmenPengawas[$gal]->save();

                                    //
                                    $newGalianPerencanaan[$gal] = new GalianPerencanaanAdjust;
                                    $newGalianPerencanaan[$gal]->galian_id = $cekItemGalian[$gal]->id;
                                    $newGalianPerencanaan[$gal]->item_id = $cekItemGalian[$gal]->item_id;
                                    $newGalianPerencanaan[$gal]->panjang = $datapanjang[$gal];
                                    $newGalianPerencanaan[$gal]->dalam = $datadalam[$gal];
                                    $newGalianPerencanaan[$gal]->lebar = $datalebar[$gal];
                                    $newGalianPerencanaan[$gal]->total = $dataTotalGalianPerencanaan[$gal];
                                    $newGalianPerencanaan[$gal]->harga_satuan = $harga_satuan[$gal];
                                    $newGalianPerencanaan[$gal]->keterangan = $dataketerangan[$gal];
                                    $newGalianPerencanaan[$gal]->harga = isset($request->jenis_harga[$gal]) ? $request->jenis_harga[$gal] : "siang";
                                    $newGalianPerencanaan[$gal]->user_id = auth()->user()->id;
                                    $newGalianPerencanaan[$gal]->save();
                                }
                            } else {
                                // galian rekanan
                                $newGalianRekanan[$gal] = new GalianPekerjaan;
                                $newGalianRekanan[$gal]->item_id = $gal;
                                $newGalianRekanan[$gal]->panjang = 0;
                                $newGalianRekanan[$gal]->lebar = 0;
                                $newGalianRekanan[$gal]->dalam = 0;
                                $newGalianRekanan[$gal]->total = $dataTotalGalianPerencanaan[$gal];
                                $newGalianRekanan[$gal]->harga_satuan = $harga_satuan[$gal];
                                $newGalianRekanan[$gal]->harga = isset($request->jenis_harga[$gal]) ? $request->jenis_harga[$gal] : "siang";
                                $newGalianRekanan[$gal]->keterangan = null;
                                $newGalianRekanan[$gal]->user_id = auth()->user()->id;
                                $newGalianRekanan[$gal]->pelaksanaan_pekerjaan_id = $PelaksanaanPekerjaan->id;
                                $newGalianRekanan[$gal]->save();

                                // perecanaan adjust
                                $newGalianPerencanaanAdjust[$gal] = new GalianPerencanaanAdjust;
                                $newGalianPerencanaanAdjust[$gal]->galian_id = $newGalianRekanan[$gal]->id;
                                $newGalianPerencanaanAdjust[$gal]->item_id = $gal;
                                $newGalianPerencanaanAdjust[$gal]->panjang = $datapanjang[$gal];
                                $newGalianPerencanaanAdjust[$gal]->dalam = $datadalam[$gal];
                                $newGalianPerencanaanAdjust[$gal]->lebar = $datalebar[$gal];
                                $newGalianPerencanaanAdjust[$gal]->total = $dataTotalGalianPerencanaan[$gal];
                                $newGalianPerencanaanAdjust[$gal]->harga_satuan = $harga_satuan[$gal];
                                $newGalianPerencanaanAdjust[$gal]->keterangan = $dataketerangan[$gal];
                                $newGalianPerencanaanAdjust[$gal]->harga = isset($request->jenis_harga[$gal]) ? $request->jenis_harga[$gal] : "siang";
                                $newGalianPerencanaanAdjust[$gal]->user_id = auth()->user()->id;
                                $newGalianPerencanaanAdjust[$gal]->save();
                            }
                        }
                    }
                }

                $PelaksanaanPekerjaan->hasUserMany()->attach($user);

                $penunjukanPekerjaan = PenunjukanPekerjaan::find($PelaksanaanPekerjaan->penunjukan_pekerjaan_id);

                if ($penunjukanPekerjaan) {
                    $nomor_pekerjaan = $penunjukanPekerjaan->nomor_pekerjaan;
                    $penunjukanPekerjaan->status = $status;
                    $penunjukanPekerjaan->save();

                    $penunjukanPekerjaan->hasUserMany()->attach($user);

                    $rekanan = Rekanan::find($PelaksanaanPekerjaan->rekanan_id)->first();
                    $title = "Pekerjaan Telah dikoreksi";
                    $body = "SPK " . $nomor_pekerjaan . " telah dikoreksi";
                    $modul = "penunjukan-pekerjaan";

                    $this->notification($penunjukanPekerjaan->aduan_id, $penunjukanPekerjaan->slug, $title, $body, $modul, auth()->user()->id, $rekanan->hasUser->id);

                    // return  $penunjukanPekerjaan;
                    $message = 'Berhasil Mengoreksi Pelaksanaan Pekerjaan';
                    $aduan = Aduan::find($penunjukanPekerjaan->aduan_id);

                    DB::commit();
                    return redirect()->route('penunjukan_pekerjaan.show', $aduan->slug)->with('message', $message)->with('Class', 'primary');
                }
            }


        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Pekerjaan gagal ditambah')->with('Class', 'danger');
        }
    }

    /**
     * data adjust
     *
     * @return \Illuminate\Http\Response
     * @param \Illuminate\Http\Request
     */
    public function adjust($slug)
    {
        $aduan = Aduan::where('slug', $slug)->first();

        if ($aduan == null) {
            return redirect()->route('penunjukan_pekerjaan.index')->with('message', 'Data Aduan tidak ditemukan')->with('Class', 'primary');
        }

        $id_wilayah = auth()->user()->id_wilayah;

        $wilayah = Wilayah::find($id_wilayah);

        $querykaryawan = Karyawan::where('pekerjaan', 'ya');
        if ($wilayah) {
            if ($wilayah->nama != 'Samarinda') {
                $jabatan = Jabatan::where('wilayah_id', $wilayah->id)->pluck('id');
                $querykaryawan = $querykaryawan->whereIn('jabatan_id', $jabatan);
            }
        }

        $karyawanPekerja = $querykaryawan->orderBy('nama')->get();

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
        $listDokumentasi = [];
        $daftarDokumentasi = [];

        $totalPekerjaan = 0;


        $action = route('penunjukan_pekerjaan.store');
        $rekanan_id = null;
        $fotoBahan = [];
        $fotoPekerjaan = [];
        $fotoPenyelesaian = [];
        $tombolEdit = '';
        $lat_long_pekerjaan = '';
        $lokasi_pekerjaan = '';
        $pekerjaanUtama = [];
        $pengawas = false;
        $perencaan = false;


        $galian = Kategori::whereSlug('galian')->first()->id;

        $jenisGalian = Jenis::where('kategori_id', $galian)->pluck('id')->toArray();
        $listPekerjaanGalian = Item::orderBy('nama')->whereIn('jenis_id', $jenisGalian)->get();
        $listPekerjaan = Item::orderBy('nama')->whereNotIn('jenis_id', $jenisGalian)->get();

        if ($aduan->status != 'draft') {
            $penunjukan = PenunjukanPekerjaan::where('aduan_id', $aduan->id)->first();
            $list_persetujuan = [];
            $list_persetujuan = $penunjukan->list_persetujuan;
            $query = PelaksanaanPekerjaan::where('penunjukan_pekerjaan_id', $penunjukan->id);

            if (auth()->user()->hasRole('asisten-manajer-perencanaan')) {
                $perencaan = true;
            }

            if (auth()->user()->hasRole('superadmin')) {
                $perencaan = true;
            }

            $pekerjaanUtama = $query->where('tagihan', 'tidak')->first();
            if ($pekerjaanUtama) {
                $fotoBahan = (object) $penunjukan->foto_bahan;
                $fotoPekerjaan = (object) $penunjukan->foto_lokasi;
                $fotoPenyelesaian = (object) $penunjukan->foto_penyelesaian;

                $daftarPekerjaan = $query->with("hasItem")->with('hasItemPengawas')->first();

                $daftarGalian = GalianPekerjaan::where('pelaksanaan_pekerjaan_id', $pekerjaanUtama->id)->with('hasGalianPengawas')->get();

                $action = route('penunjukan_pekerjaan.adjust.ubah', $pekerjaanUtama->id);

                $lat_long_pekerjaan = $pekerjaanUtama->lat_long;
                $lokasi_pekerjaan = $pekerjaanUtama->lokasi;

                if (auth()->user()->hasRole('staf-pengawas')) {
                    if ($pekerjaanUtama->status === 'selesai') {
                        $tombolEdit = 'bisa';
                    }
                    $pengawas = true;
                } else {
                    if ($pekerjaanUtama->status === 'dikoreksi') {
                        $tombolEdit = 'bisa';
                    }
                }
            }

            if (auth()->user()->hasRole('rekanan')) {
                $rekanan_id = auth()->user()->id_rekanan;
            }

            $notifikasi = Notifikasi::where('modul_id', $penunjukan->id)->where('to_user_id', auth()->user()->id)->first();
            if ($notifikasi) {
                $notifikasi->status = 'baca';
                $notifikasi->delete();
            }
        } else {
            $notifikasi = Notifikasi::where('modul_id', $aduan->id)->first();
            if ($notifikasi) {
                $notifikasi->status = 'baca';
                $notifikasi->delete();
            }
        }

        $jenisAduan = $aduan->hasJenisAduan->toArray();
        $jenis_aduan = JenisAduan::orderBy('nama')->get();
        $rekanan = Rekanan::orderBy('nama')->get();



        $title = 'Detail Pekerjaan ' . $aduan->nomor_pekerjaan;

        return view(
            'penunjukan_pekerjaan.adjust',
            compact(
                'aduan',
                'action',
                'pengawas',
                'listPekerjaan',
                'list_persetujuan',
                'listPekerjaanGalian',
                'perencaan',
                'penunjukan',
                'pekerjaanUtama',
                'daftarPekerjaan',
                'daftarGalian',
                'daftarBahan',
                'daftarAlatBantu',
                'daftarTransportasi',
                'daftarDokumentasi',
                'lat_long_pekerjaan',
                'karyawanPekerja',
                'lokasi_pekerjaan',
                'rekanan_id',
                'jenisAduan',
                'jenis_aduan',
                'rekanan',
                'title',
                'totalPekerjaan',
                'fotoPekerjaan',
                'fotoPenyelesaian',
                'fotoBahan',
                'tombolEdit',
                'action'
            )
        );
    }

    public function opennotifikasi($id)
    {
        $notifikasi = Notifikasi::where('id', $id)->where('to_user_id', auth()->user()->id)->first();

        if ($notifikasi) {
            $notifikasi->status = 'baca';
            if ($notifikasi->modul === 'tagihan') {
                $tagihan = Tagihan::find($notifikasi->modul_id);
                if ($tagihan) {
                    $notifikasi->delete();
                    return redirect()->route('tagihan.show', $tagihan->slug);
                }
            }
            if ($notifikasi->modul === 'penunjukan-pekerjaan') {
                $penunjukanAduan = PenunjukanPekerjaan::find($notifikasi->modul_id);
                $aduan = Aduan::find($notifikasi->modul_id);
                if ($penunjukanAduan) {
                    $notifikasi->delete();
                    $aduan = Aduan::find($penunjukanAduan->aduan_id);
                    return redirect()->route('penunjukan_pekerjaan.show', $aduan->slug);
                }
                if ($aduan) {
                    $notifikasi->delete();
                    return redirect()->route('penunjukan_pekerjaan.show', $aduan->slug);
                }
            }
            if ($notifikasi->modul === 'pelaksanaan-pekerjaan') {
                $PelaksanaanPekerjaan = PelaksanaanPekerjaan::find($notifikasi->modul_id);
                $aduan = Aduan::find($notifikasi->modul_id);
                if ($PelaksanaanPekerjaan) {
                    $aduan = Aduan::find($PelaksanaanPekerjaan->aduan_id);
                    $notifikasi->delete();
                    return redirect()->route('penunjukan_pekerjaan.show', $aduan->slug);
                }
                if ($aduan) {
                    $notifikasi->delete();
                    return redirect()->route('penunjukan_pekerjaan.show', $aduan->slug);
                }
            }
            if ($notifikasi->modul === 'aduan') {
                $aduan = Aduan::find($notifikasi->modul_id);
                if ($aduan) {
                    $notifikasi->delete();
                    return redirect()->route('penunjukan_pekerjaan.show', $aduan->slug);
                }
            }
            $notifikasi->delete();
        }



        return redirect()->route('penunjukan_pekerjaan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        $title = "Upload Data Pekerjaan";
        $route = "$this->route";
        $action = route('penunjukan_pekerjaan.upload');

        return view(
            'penunjukan_pekerjaan.upload',
            compact(
                "title",
                "route",
                "action",
            )
        );
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

        $dataNoTagihan = '';
        $dataTanggalTagihan = [];
        $dataCv = [];
        $dataSpk = [];
        $dataLokasi = [];
        $dataJamMulai = [];
        $dataJamSelesai = [];
        $dataTanggalMulai = [];
        $dataTanggalSelesai = [];
        $dataJenisPekerjaan = [];
        $dataNamaPekerjaan = [];
        $dataJumlahPekerjaan = [];
        $dataPanjangGalian = [];
        $dataLebarGalian = [];
        $dataDalamGalian = [];
        $dataPekerjaan = [];
        $dataAduan = [];
        $dataRekanan = [];
        $PelaksanaanPekerjaan = [];
        $listitem = [];
        $tagihan = [];

        $wilayah = Wilayah::first();

        $file = $request->hasFile('file');
        $total = 0;
        if ($file) {
            $item = Excel::toArray('', request()->file('file'), null, null);
            foreach ($item[0] as $k => $val) {
                $dataItem[$k] = $val;
            }
            foreach ($dataItem as $index => $item) {
                if ($index > 2) {

                    $dataNoTagihan = $item[1];
                    $dataTanggalTagihan[$index] = $item[2];
                    $dataCv[$index] = $item[3];
                    $dataSpk[$index] = $item[4];
                    $dataLokasi[$index] = $item[5];
                    $dataJamMulai[$index] = $item[6];
                    $dataJamSelesai[$index] = $item[7];
                    $dataTanggalMulai[$index] = $item[8];
                    $dataTanggalSelesai[$index] = $item[9];
                    $dataJenisPekerjaan[$index] = $item[10];
                    $dataNamaPekerjaan[$index] = $item[11];
                    $dataJumlahPekerjaan[$index] = (float) $item[12];
                    $dataPanjangGalian[$index] = $item[13];
                    $dataLebarGalian[$index] = $item[14];
                    $dataDalamGalian[$index] = $item[15];

                    if (
                        $dataNoTagihan[$index] != null &&
                        $dataTanggalTagihan[$index] != null &&
                        $dataCv[$index] != null &&
                        $dataSpk[$index] != null &&
                        $dataLokasi[$index] != null &&
                        $dataJamMulai[$index] != null &&
                        $dataJamSelesai[$index] != null &&
                        $dataTanggalMulai[$index] != null &&
                        $dataTanggalSelesai[$index] != null &&
                        $dataJenisPekerjaan[$index] != null &&
                        $dataNamaPekerjaan[$index] != null &&
                        $dataJumlahPekerjaan[$index] != null &&
                        $dataPanjangGalian[$index] != null &&
                        $dataLebarGalian[$index] != null &&
                        $dataDalamGalian[$index] != null
                    ) {

                        $dataRekanan[$index] = Rekanan::where('nama', 'like', '%' . $dataCv[$index] . '%')->first();

                        if ($dataRekanan[$index]) {
                            $dataPekerjaan[$index] = PenunjukanPekerjaan::where('nomor_pekerjaan', $dataSpk[$index])->where('rekanan_id', $dataRekanan[$index]->id)->first();

                            if (empty($dataPekerjaan[$index])) {
                                $dataAduan[$index] = new Aduan();
                                $dataAduan[$index]->no_ticket = $dataSpk[$index];
                                $dataAduan[$index]->no_aduan = $dataSpk[$index];
                                $dataAduan[$index]->no_pelanggan = $dataCv[$index];
                                $dataAduan[$index]->detail_lokasi = $dataLokasi[$index];
                                $dataAduan[$index]->no_hp = $dataRekanan[$index]->no_hp;
                                $dataAduan[$index]->nps = $dataSpk[$index];
                                $dataAduan[$index]->atas_nama = $dataRekanan[$index]->nama;
                                $dataAduan[$index]->sumber_informasi = $dataRekanan[$index]->nama;
                                $dataAduan[$index]->lokasi = $dataLokasi[$index];
                                $dataAduan[$index]->keterangan = "Aduan dari internal";
                                $dataAduan[$index]->lat_long = '-0.475303, 117.14647';
                                $dataAduan[$index]->status = "selesai";
                                $dataAduan[$index]->wilayah_id = $wilayah->id;
                                $dataAduan[$index]->user_id = auth()->user()->id;
                                $dataAduan[$index]->save();

                                $dataPekerjaan[$index] = new PenunjukanPekerjaan;
                                $dataPekerjaan[$index]->nomor_pekerjaan = $dataSpk[$index];
                                $dataPekerjaan[$index]->rekanan_id = $dataRekanan[$index]->id;
                                $dataPekerjaan[$index]->aduan_id = $dataAduan[$index]->id;
                                $dataPekerjaan[$index]->tagihan = 'ya';
                                $dataPekerjaan[$index]->user_id = auth()->user()->id;
                                $dataPekerjaan[$index]->status = 'selesai';
                                $dataPekerjaan[$index]->save();
                            }

                            $PelaksanaanPekerjaan[$index] = PelaksanaanPekerjaan::where('penunjukan_pekerjaan_id', $dataPekerjaan[$index]->id)->where('rekanan_id', $dataRekanan[$index]->id)->first();

                            if (empty($PelaksanaanPekerjaan[$index])) {
                                $PelaksanaanPekerjaan[$index] = new PelaksanaanPekerjaan;
                                $PelaksanaanPekerjaan[$index]->nomor_pelaksanaan_pekerjaan = $dataSpk[$index];
                                $PelaksanaanPekerjaan[$index]->penunjukan_pekerjaan_id = $dataPekerjaan[$index]->id;
                                $PelaksanaanPekerjaan[$index]->rekanan_id = $dataRekanan[$index]->id;
                                $PelaksanaanPekerjaan[$index]->aduan_id = $dataPekerjaan[$index]->aduan_id;
                                $PelaksanaanPekerjaan[$index]->lokasi = $dataLokasi[$index];
                                $PelaksanaanPekerjaan[$index]->lat_long = '-0.475303, 117.14647';
                                $PelaksanaanPekerjaan[$index]->user_id = auth()->user()->id;
                                $PelaksanaanPekerjaan[$index]->tanggal_mulai = Carbon::parse($dataTanggalMulai[$index])->format('Y-m-d');
                                $PelaksanaanPekerjaan[$index]->tanggal_selesai = Carbon::parse($dataTanggalSelesai[$index])->format('Y-m-d');
                                $PelaksanaanPekerjaan[$index]->status = 'selesai';
                                $PelaksanaanPekerjaan[$index]->save();
                            }

                            $dataItem[$index] = Item::where('nama', 'like', '%' . $dataNamaPekerjaan[$index] . '%')->first();
                            if ($dataItem[$index]) {
                                $harga_item[$index] = $dataItem[$index]->harga;
                                if ($dataJenisPekerjaan[$index] !== 'Galian') {
                                    if ($dataJumlahPekerjaan != '') {

                                        $listitem[$dataItem[$index]->id] = [
                                            'keterangan' => '',
                                            'harga' => $harga_item[$index],
                                            'qty' => $dataJumlahPekerjaan[$index],
                                            'total' => $dataJumlahPekerjaan[$index] * $harga_item[$index],
                                        ];
                                        $PelaksanaanPekerjaan[$index]->hasItem()->sync($listitem);
                                    }
                                } else {
                                    $dataGalian[$index] = GalianPekerjaan::where('item_id', $item)->where('pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan[$index]->id)->first();

                                    if (empty($dataGalian[$index])) {
                                        $dataGalian[$index] = new GalianPekerjaan;
                                    }
                                    $dataGalian[$index]->panjang = $dataPanjangGalian[$index];
                                    $dataGalian[$index]->lebar = $dataLebarGalian[$index];
                                    $dataGalian[$index]->dalam = $dataDalamGalian[$index];
                                    $dataGalian[$index]->harga = 'siang';
                                    $dataGalian[$index]->total = 0;
                                    $dataGalian[$index]->item_id = $dataItem[$index]->id;
                                    $dataGalian[$index]->user_id = auth()->user()->id;
                                    $dataGalian[$index]->pelaksanaan_pekerjaan_id = $PelaksanaanPekerjaan[$index]->id;
                                    $dataGalian[$index]->save();
                                }
                            }
                        }
                    }
                }
            }


            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil diupload dengan total item :' . $total)->with('Class', 'success');
        }
        // try { } catch (\Throwable $th) {
        //     //throw $th;
        //     return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' gagal diupload')->with('Class', 'success');
        // }
    }

    public function rekanan()
    {
        //nama title
        $title = "Rekapan Rekanan";

        //nama route dan action route
        $route = $this->route;
        $search = request()->search;
        $query = Rekanan::query();

        if ($search) {
            $query->where('nama', 'like', "%" . $search . "%")->orWhere('nama_penangung_jawab', 'like', "%" . $search . "%")->orWhere('no_hp', 'like', "%" . $search . "%");
        }

        $rekanan = $query->paginate(50);

        return view(
            'penunjukan_pekerjaan.rekanan',
            compact(
                'title',
                'search',
                'rekanan'
            )
        );
    }
    public function rekapan($slug)
    {
        //nama title
        $rekanan = Rekanan::whereSlug($slug)->first();

        $title = "Rekapan Rekanan - " . $rekanan->nama;
        $route = $this->route;

        $query = PelaksanaanPekerjaan::query();
        $query->where('rekanan_id', $rekanan->id);

        $start = Carbon::now()->subMonths(2)->startOfMonth()->format('Y-m-d') . ' 00:00:01';
        $end = Carbon::now()->endOfMonth()->format('Y-m-d') . ' 23:59:59';

        $query->where(function ($query) {
            $query->where('status', 'selesai koreksi')
                ->orWhere('status', 'diadjust');
        })->where('tagihan', 'tidak')->whereBetween(DB::raw('DATE(tanggal_selesai)'), array($start, $end));
        $penunjukan = $query->get();

        $total_lokasi = $query->count();

        $bulan = bulan_indonesia(Carbon::now()) . ' ' . date('Y');

        return view(
            'penunjukan_pekerjaan.rekapan',
            compact(
                'title',
                'penunjukan',
                'total_lokasi',
                'bulan',
                'rekanan'
            )
        );
    }
    public function kirimwa()
    {
        //nama title

        $title = "Rekapan Rekanan";


        return view(
            'penunjukan_pekerjaan.rekanan',
            compact(
                'title',
                'search',
                'rekanan'
            )
        );
    }
}
