<?php

namespace App\Http\Controllers;

use App\Exports\ExportTagihan;
use App\Models\Aduan;
use App\Models\Item;
use App\Models\Jabatan;
use App\Models\Jenis;
use App\Models\Karyawan;
use App\Models\Notifikasi;
use App\Models\PelaksanaanPekerjaan;
use App\Models\PenunjukanPekerjaan;
use App\Models\Rekanan;
use App\Models\Satuan;
use App\Models\Tagihan;
use App\Models\TagihanItem;
use App\Models\Wilayah;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Excel;
use Carbon\Carbon;
use DB;
use Storage;
use Str;

class TagihanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'tagihan';
        $this->tambah = 'false';
        $this->index = 'tagihan';
        $this->sort = 'created_at';
        $this->desc = 'desc';
        $this->paginate = 25;
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }


    public function configHeaders()
    {
        return [
            [
                'name' => 'nomor_tagihan',
                'alias' => 'Nomor Tagihan',
            ],
            [
                'name' => 'rekanan',
                'alias' => 'Nama Rekanan',
            ],
            [
                'name' => 'no_hp_rekanan',
                'alias' => 'Nomor Hp Rekanan',
            ],
            // [
            //     'name' => 'no_spk',
            //     'alias' => 'No Spk',
            // ],
            [
                'name' => 'tanggal',
                'alias' => 'Tanggal Tagihan',
            ],
            [
                'name' => 'kode_vocher',
                'alias' => 'Nomor Voucher',
            ],
            [
                'name' => 'total_lokasi_pekerjaan',
                'alias' => 'Total Lokasi',
            ],
            [
                'name' => 'total_tagihan',
                'input' => 'rupiah',
                'alias' => 'Total Tagihan',
            ],
        ];
    }
    public function configSearch()
    {
        if (auth()->user()->hasRole('rekanan')) {
            # code...
            return [
                [
                    'name' => 'nomor_tagihan',
                    'input' => 'text',
                    'alias' => 'Nomor Tagihan',
                    'value' => null
                ],
                [
                    'name' => 'kode_vocher',
                    'input' => 'text',
                    'alias' => 'Nomor Voucher',
                    'value' => null
                ],
                [
                    'name' => 'created_at',
                    'input' => 'daterange',
                    'alias' => 'Tanggal',
                    'value' => null
                ],
            ];
        }else{
            return [
                [
                    'name' => 'nomor_tagihan',
                    'input' => 'text',
                    'alias' => 'Nomor Tagihan',
                    'value' => null
                ],
                [
                    'name' => 'kode_vocher',
                    'input' => 'text',
                    'alias' => 'Nomor Voucher',
                    'value' => null
                ],
                [
                    'name' => 'rekanan_id',
                    'input' => 'combo',
                    'alias' => 'Rekanan',
                    'value' => $this->combobox(
                        'Rekanan',
                    )
                ],
                [
                    'name' => 'created_at',
                    'input' => 'daterange',
                    'alias' => 'Tanggal',
                    'value' => null
                ],
            ];
        }
    }
    public function configForm()
    {
        return [];
    }

    public function index()
    {
        //nama title
        if (!isset($this->title)) {
            $title = ucwords($this->route);
        } else {
            $title = ucwords($this->title);
        }

        //nama route
        $route = $this->route;

        //nama relation
        $relations = $this->relations;

        //nama jumlah pagination
        $paginate = $this->paginate;

        //declare nilai serch pertama
        $search = null;

        //memanggil configHeaders
        $configHeaders = $this->configHeaders();

        //memangil model peratama
        $query = $this->model()::query();

        //button
        $button = null;

        //tambah data
        $tambah = $this->tambah;

        //tambah data
        $upload = $this->upload;

        $export = null;

        if ($this->configButton()) {
            $button = $this->configButton();
        }
        //mulai pencarian --------------------------------
        $searches = $this->configSearch();
        $searchValues = [];
        $n = 0;
        $countAll = 0;
        $queryArray = [];

        $queryRaw = '';

        foreach ($searches as $key => $val) {
            $search[$key] = request()->input($val['name']);
            $hasilSearch[$val['name']] = $search[$key];

            if ($search[$key]) {
                if ($val['input'] != 'daterange') {
                    # code...
                    $searchValues[$key] = preg_split('/\s+/', $search[$key], -1, PREG_SPLIT_NO_EMPTY);

                    if (count($searchValues[$key]) == 1) {
                        foreach ($searchValues[$key] as $index => $value) {
                            $query->where($val['name'], 'like', "%{$value}%");
                            $countAll = $countAll + 1;
                        }
                    } else {
                        $lastquery = '';

                        foreach ($searchValues[$key] as $index => $word) {
                            if (preg_match("/^[a-zA-Z0-9]+$/", $word) == 1) {

                                if ($queryRaw) {
                                    $count = $this->model()->whereRaw(rtrim($queryRaw, " and"))->count();
                                    if ($count > 0) {
                                        $countAll = $countAll + 1;
                                        $lastquery = $queryRaw;

                                        $queryRaw .= $val['name'] . ' LIKE "%' . $word . '%" and ';
                                        if ($this->model()->whereRaw(rtrim($queryRaw, " and"))->count() == 0) {
                                            $queryRaw = $lastquery;
                                        }
                                    }
                                } else {
                                    $count = $this->model()->where($val['name'], 'like', "%{$word}%")->count();
                                    if ($count > 0) {
                                        $countAll = $countAll + 1;

                                        $queryRaw .= $val['name'] . ' LIKE "%' . $word . '%" and ';
                                        continue;
                                    }
                                }
                            }
                        }
                    }

                    if ($queryRaw) {
                        $query->whereRaw(rtrim($queryRaw, " and "));
                    }
                    if (count($queryArray) > 0) {
                        $query->where($queryArray);
                    }
                } else {

                    $date = explode(' - ', request()->input($val['name']));
                    $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
                    $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
                    $query = $query->whereBetween(DB::raw('DATE(' . $val['name'] . ')'), array($start, $end));

                    $export .= 'from=' . $start . '&to=' . $end;
                    $countAll = $countAll + 1;
                }

                if ($countAll == 0) {
                    $query->where('id', "");
                }
            }
            $export .= $val['name'] . '=' . $search[$key] . '&';
        }

        // return $ayam;

        //akhir pencarian --------------------------------
        // relatio
        // sort by
        if ($this->user) {
            if (!Auth::user()->hasRole('superadmin') && !Auth::user()->hasRole('admin')) {
                $query->where('user_id', Auth::user()->id);
            }
        }

        if (!auth()->user()->hasRole('superadmin')) {
            if (auth()->user()->hasRole('rekanan')) {
                $rekanan_id = auth()->user()->id_rekanan;

                $query->where('rekanan_id', $rekanan_id);
            } elseif (auth()->user()->hasRole('manajer-distribusi')) {
                $aduan = Aduan::where('kategori_nps', 'dis')->pluck('id')->toArray();

                $PelaksanaanPekerjaan = PelaksanaanPekerjaan::whereIn('aduan_id', $aduan)->pluck('id')->toArray();

                $query->whereHas('hasPelaksanaanPekerjaan', function ($q) use ($PelaksanaanPekerjaan) {
                    $q->whereIn('tagihan_pelaksanaan.pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan);
                });
            } elseif (auth()->user()->hasRole('manajer-pengendalian-kehilangan-air')) {

                $aduan = Aduan::where('kategori_nps', 'pka')->pluck('id')->toArray();

                $PelaksanaanPekerjaan = PelaksanaanPekerjaan::whereIn('aduan_id', $aduan)->pluck('id')->toArray();

                $query->whereHas('hasPelaksanaanPekerjaan', function ($q) use ($PelaksanaanPekerjaan) {
                    $q->whereIn('tagihan_pelaksanaan.pelaksanaan_pekerjaan_id', $PelaksanaanPekerjaan);
                });
            } else {
                $list_rekanan_id = auth()->user()->karyawan->hasRekanan->pluck('id');
                if (count($list_rekanan_id) > 0) {
                    $query->whereIn('rekanan_id', $list_rekanan_id);
                }
            }
        }
        //mendapilkan data model setelah query pencarian

        if (auth()->user()->hasRole('manajer-perencanaan')) {
            $query->orderByRaw("status = 'dikirim' DESC")->orderByRaw("status = 'proses' DESC")->orderByRaw("status = 'disetujui asmenkas' DESC")->orderByRaw("status = 'disetujui mankeu' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC");
        }
        if (auth()->user()->hasRole('direktur-teknik')) {
            $query->orderByRaw("status = 'proses' DESC")->orderByRaw("status = 'disetujui' DESC")->orderByRaw("status = 'disetujui asmenkas' DESC")->orderByRaw("status = 'disetujui mankeu' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC");
        }
        if (auth()->user()->hasRole('asisten-manajer-tata-usaha')) {
            $query->orderByRaw("status = 'disetujui' DESC")->orderByRaw("status = 'disetujui asmentu' DESC")->orderByRaw("status = 'disetujui asmenkas' DESC")->orderByRaw("status = 'disetujui mankeu' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC");
        }
        if (auth()->user()->hasRole('manajer-umum-dan-kesekretariatan')) {
            $query->orderByRaw("status = 'disetujui asmentu' DESC")->orderByRaw("status = 'disetujui mu' DESC")->orderByRaw("status = 'disetujui asmenkas' DESC")->orderByRaw("status = 'disetujui mankeu' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC");
        }
        if (auth()->user()->hasRole('direktur-umum')) {
            $query->orderByRaw("status = 'disetujui mu' DESC")->orderByRaw("status = 'disetujui dirum' DESC")->orderByRaw("status = 'disetujui asmenkas' DESC")->orderByRaw("status = 'disetujui mankeu' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC");
        }
        if (auth()->user()->hasRole('direktur-utama')) {
            $query->orderByRaw("status = 'disetujui dirum' DESC")->orderByRaw("status = 'disetujui dirut' DESC")->orderByRaw("status = 'disetujui asmenkas' DESC")->orderByRaw("status = 'disetujui mankeu' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC");
        }

        if (auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan')) {
            $query->orderByRaw("status = 'disetujui dirut' DESC")->orderByRaw("status = 'disetujui asmenanggaran' DESC")->orderByRaw("status = 'disetujui asmenkas' DESC")->orderByRaw("status = 'disetujui mankeu' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC");
        }
        if (auth()->user()->hasRole('asisten-manajer-akuntansi')) {
            $query->orderByRaw("status = 'disetujui asmenanggaran' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC")->orderByRaw("status = 'disetujui asmenkas' DESC")->orderByRaw("status = 'disetujui mankeu' DESC");
        }

        if (auth()->user()->hasRole('manajer-keuangan')) {
            $query->orderByRaw("status = 'disetujui asmenakuntan' DESC")->orderByRaw("status = 'disetujui mankeu' DESC")->orderByRaw("status = 'disetujui asmenkas' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC");
        }
        if (auth()->user()->hasRole('asisten-manajer-kas')) {
            $query->orderByRaw("status = 'disetujui mankeu' DESC")->orderByRaw("status = 'disetujui asmenkas' DESC")->orderByRaw("status = 'disetujui asmenakuntan' DESC");
        }
        $query->orderBy('created_at', 'asc');
        if ($paginate) {

            $data = $query->paginate($paginate);
        } else {
            $data = $query->get();
        }

        // return $button;
        $template = 'template.index';
        if ($this->index) {
            $template = $this->index . '.index';
        }

        // $dataSudahBayar = Tagihan::whereNotNull('kode_vocher')->get();
        // $dataBelumBayar = Tagihan::whereNull('kode_vocher')->with('hasPelaksanaanPekerjaan')->get();
        // $sumtotalSudahBayar = 0;

        // if (isset($dataSudahBayar)) {
        //     # code...
        //     foreach ($dataSudahBayar as  $tsb) {
        //         # code...
        //         $pkpSudahBayar = 'tidak';
        //         $ppnSudahBayar = 0;
        //         $totalSudahBayar = 0;
        //         if ($tsb->hasPelaksanaanPekerjaan) {
        //             foreach ($tsb->hasPelaksanaanPekerjaan as $key => $hpp) {

        //                 $totalSudahBayar += $hpp->total_pekerjaan;
        //             }
        //         }
        //         $totalSudahBayar = pembulatan($totalSudahBayar);
        //         $totalSudahBayar = str_replace(".", "", $totalSudahBayar);



        //         if ($tsb->hasRekanan) {
        //             if ($tsb->hasRekanan->pkp) {
        //                 if ($tsb->hasRekanan->pkp === 'ya') {
        //                     $ppnSudahBayar = ($totalSudahBayar * 11) / 100;
        //                 }
        //             }
        //         }

        //         $sumtotalSudahBayar += $totalSudahBayar + $ppnSudahBayar;
        //     }

        //     $sumtotalSudahBayar;
        // }
        // $sumtotalbelumBayar = 0;
        // if (isset($dataBelumBayar)) {
        //     # code...
        //     foreach ($dataBelumBayar as  $tbb) {
        //         # code...
        //         $pkpbelumBayar = 'tidak';
        //         $ppnbelumBayar = 0;
        //         $totalbelumBayar = 0;
        //         if ($tbb->hasPelaksanaanPekerjaan) {
        //             foreach ($tbb->hasPelaksanaanPekerjaan as $key => $hbp) {

        //                 $totalbelumBayar += $hbp->total_pekerjaan;
        //             }
        //         }
        //         $totalbelumBayar = pembulatan($totalbelumBayar);
        //         $totalbelumBayar = str_replace(".", "", $totalbelumBayar);



        //         if ($tbb->hasRekanan) {
        //             if ($tbb->hasRekanan->pkp) {
        //                 if ($tbb->hasRekanan->pkp === 'ya') {
        //                     $ppnbelumBayar = ($totalbelumBayar * 11) / 100;
        //                 }
        //             }
        //         }

        //         $sumtotalbelumBayar += $totalbelumBayar + $ppnbelumBayar;
        //     }
        // }

        // return  $export;

        return view(
            $template,
            compact(
                "title",
                "data",
                'searches',
                'hasilSearch',
                'button',
                'tambah',
                'upload',
                'search',
                'export',
                // 'sumtotalSudahBayar',
                // 'sumtotalbelumBayar',
                'configHeaders',
                'route'
            )
        );
    }

    public function show($slug)
    {
        $query = Tagihan::whereSlug($slug);

        $nomor_tagihan = '';
        $rekanan = '';
        $tanggal_tagihan = '';
        $total = 0;
        $total_lokasi = 0;
        $tagihanItem = [];
        $wilayahId = [];
        $aduanId = [];
        $tagihan = $query->orderBy('created_at', 'desc')->first();
        $action = '';
        if ($tagihan) {

            if (isset($tagihan->hasPelaksanaanPekerjaan)) {
                $PelaksanaanPekerjaan = $tagihan->hasPelaksanaanPekerjaan();
                $tanggalSelesai = $PelaksanaanPekerjaan->pluck('tanggal_selesai')->toArray();;
                $tanggalPertama = min(array_map(function ($item) {
                    return $item;
                }, $tanggalSelesai));
                $tanggalAkhir = min(array_map(function ($item) {
                    return $item;
                }, $tanggalSelesai));

                if ($PelaksanaanPekerjaan) {
                    $pelaksanaan = $PelaksanaanPekerjaan->pluck('id')->toArray();
                    $aduanId = $PelaksanaanPekerjaan->pluck('aduan_id')->toArray();
                    $wilayahId = Aduan::whereIn('id', $aduanId)->pluck('wilayah_id')->toArray();
                    $katagori_nps = Aduan::whereIn('id', $aduanId)->pluck('kategori_nps')->toArray();
                    $katagori_nps_unique = array_unique($katagori_nps);
                    $wilayahId = array_unique($wilayahId);
                }
            }
            $nomor_tagihan = $tagihan->nomor_tagihan;
            $action = route('tagihan.update', $tagihan->id);

            $total = $tagihan->tagihan + $tagihan->galian;
            $totalAnggaran = $total;
            $total = pembulatan($total);
            $total = str_replace(".", "", $total);
            $ppn = ($total * 11) / 100;
            $grand_total = $total + $ppn;

            $total_lokasi = $tagihan->total_lokasi_pekerjaan;
            $tanggal_tagihan = tanggal_indonesia($tagihan->tanggal_tagihan);
            $rekanan = $tagihan->rekanan;

            $notifikasi = Notifikasi::where('modul_id', $tagihan->id)->where('to_user_id', auth()->user()->id)->first();
            if ($notifikasi) {
                $notifikasi->status = 'baca';
                $notifikasi->delete();
            }

            $pkp = 'tidak';
            if ($tagihan->hasRekanan->pkp) {
                if ($tagihan->hasRekanan->pkp == 'ya') {
                    $pkp = 'ya';
                }
            }

            $ktp = $tagihan->hasRekanan->ktp;

            $npwp = $tagihan->hasRekanan->npwp;

            $title = "Proses Tagihan Nomor :" . $tagihan->nomor_tagihan;
            $filename = "Tagihan Nomor :" . $tagihan->nomor_tagihan;

            $dataitem = Item::all();
            $bntSetuju = true;
            $user = auth()->user()->id;
            $list_persetujuan = [];
            $perencaan = true;
            $keuangan = false;

            if (auth()->user()->hasRole('rekanan')) {
                $perencaan = false;
            }

            if (auth()->user()->hasRole('superadmin')) {
                $perencaan = true;
            }


            if (auth()->user()->hasRole('manajer-perencanaan')) {

                $bntSetuju = false;
            }
            if (auth()->user()->hasRole('direktur-teknik')) {

                // list jabatan
                $listJabatan = Jabatan::where('slug', 'manajer-perencanaan')->pluck('id')->toArray();

                // list karyawan bedasarkan jabatan
                $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get()->pluck('user_id')->toArray();

                $list_persetujuan = $query->whereHas('hasUserMany', function ($q) use ($listKaryawan) {
                    $q->whereIn('tagihan_user.user_id', $listKaryawan);
                })->count();

                if ($list_persetujuan > 0) {
                    $bntSetuju = false;
                }
            }


            if (auth()->user()->hasRole('asisten-manajer-tata-usaha')) {

                // list jabatan
                $listJabatan = Jabatan::where('slug', 'direktur-teknik')->pluck('id')->toArray();

                // list karyawan bedasarkan jabatan
                $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get()->pluck('user_id')->toArray();

                $list_persetujuan = $query->whereHas('hasUserMany', function ($q) use ($listKaryawan) {
                    $q->whereIn('tagihan_user.user_id', $listKaryawan);
                })->count();

                if ($list_persetujuan > 0) {
                    $bntSetuju = false;
                }
            }
            if (auth()->user()->hasRole('manajer-umum-dan-kesekretariatan')) {

                // list jabatan
                $listJabatan = Jabatan::where('slug', 'asisten-manajer-tata-usaha')->pluck('id')->toArray();

                // list karyawan bedasarkan jabatan
                $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get()->pluck('user_id')->toArray();

                $list_persetujuan = $query->whereHas('hasUserMany', function ($q) use ($listKaryawan) {
                    $q->whereIn('tagihan_user.user_id', $listKaryawan);
                })->count();

                if ($list_persetujuan > 0) {
                    $bntSetuju = false;
                }
            }
            if (auth()->user()->hasRole('direktur-umum')) {

                // list jabatan
                $listJabatan = Jabatan::where('slug', 'manajer-umum-dan-kesekretariatan')->pluck('id')->toArray();

                // list karyawan bedasarkan jabatan
                $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get()->pluck('user_id')->toArray();

                $list_persetujuan = $query->whereHas('hasUserMany', function ($q) use ($listKaryawan) {
                    $q->whereIn('tagihan_user.user_id', $listKaryawan);
                })->count();

                if ($list_persetujuan > 0) {
                    $bntSetuju = false;
                }
            }
            if (auth()->user()->hasRole('direktur-utama')) {

                // list jabatan
                $listJabatan = Jabatan::where('slug', 'direktur-umum')->pluck('id')->toArray();

                // list karyawan bedasarkan jabatan
                $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get()->pluck('user_id')->toArray();

                $list_persetujuan = $query->whereHas('hasUserMany', function ($q) use ($listKaryawan) {
                    $q->whereIn('tagihan_user.user_id', $listKaryawan);
                })->count();

                if ($list_persetujuan > 0) {
                    $bntSetuju = false;
                }
            }
            if (auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan')) {

                // list jabatan
                $listJabatan = Jabatan::where('slug', 'direktur-utama')->pluck('id')->toArray();

                // list karyawan bedasarkan jabatan
                $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get()->pluck('user_id')->toArray();

                $list_persetujuan = $query->whereHas('hasUserMany', function ($q) use ($listKaryawan) {
                    $q->whereIn('tagihan_user.user_id', $listKaryawan);
                })->count();

                if ($list_persetujuan > 0) {
                    $bntSetuju = false;
                }
            }
            if (auth()->user()->hasRole('asisten-manajer-akuntansi')) {

                // list jabatan
                $listJabatan = Jabatan::where('slug', 'asisten-manajer-perencanaan-keuangan')->pluck('id')->toArray();

                // list karyawan bedasarkan jabatan
                $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get()->pluck('user_id')->toArray();

                $list_persetujuan = $query->whereHas('hasUserMany', function ($q) use ($listKaryawan) {
                    $q->whereIn('tagihan_user.user_id', $listKaryawan);
                })->count();

                if ($list_persetujuan > 0) {
                    $bntSetuju = false;
                }
            }
            if (auth()->user()->hasRole('manajer-keuangan')) {

                // list jabatan
                $listJabatan = Jabatan::where('slug', 'asisten-manajer-akuntansi')->pluck('id')->toArray();

                // list karyawan bedasarkan jabatan
                $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get()->pluck('user_id')->toArray();

                $list_persetujuan = $query->whereHas('hasUserMany', function ($q) use ($listKaryawan) {
                    $q->whereIn('tagihan_user.user_id', $listKaryawan);
                })->count();

                if ($list_persetujuan > 0) {
                    $bntSetuju = false;
                }
            }
            if (auth()->user()->hasRole('asisten-manajer-kas')) {

                // list jabatan
                $listJabatan = Jabatan::where('slug', 'manajer-keuangan')->pluck('id')->toArray();

                // list karyawan bedasarkan jabatan
                $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get()->pluck('user_id')->toArray();

                $list_persetujuan = $query->whereHas('hasUserMany', function ($q) use ($listKaryawan) {
                    $q->whereIn('tagihan_user.user_id', $listKaryawan);
                })->count();

                if ($list_persetujuan > 0) {
                    $bntSetuju = false;
                }
            }


            if (auth()->user()->hasRole('keuangan')) {
                $bntSetuju = true;
                $keuangan = false;
                if ($tagihan->status === 'disetujui dirut') {
                    $keuangan = true;
                    $bntSetuju = false;
                }
            }

            if (isset($tagihan->list_persetujuan)) {
                $list_persetujuan = (object) $tagihan->list_persetujuan;
                foreach ($tagihan->list_persetujuan as $key => $value) {
                    if ($value->id === $user) {
                        $bntSetuju = true;
                    }
                }
            }

            return view(
                'tagihan.show',
                compact(
                    'action',
                    'title',
                    'dataitem',
                    'total',
                    'rekanan',
                    'filename',
                    'list_persetujuan',
                    'totalAnggaran',
                    'bntSetuju',
                    'perencaan',
                    'total_lokasi',
                    'ktp',
                    'npwp',
                    'ppn',
                    'grand_total',
                    'nomor_tagihan',
                    'tagihanItem',
                    'keuangan',
                    'tanggal_tagihan',
                    'pkp',
                    'tagihan'
                )
            );
        }
        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' tidak ada')->with('Class', 'success');
    }

    public function create()
    {
        //nama title
        if (!isset($this->title)) {
            $title = "Tambah " . ucwords($this->route);
        } else {
            $title = "Tambah " . ucwords($this->title);
        }

        //nama route dan action route
        $route = $this->route;
        $store = "store";

        //memanggil config form
        $form = $this->configform();

        $count = count($form);

        $colomField = $this->colomField($count);

        $countColom = $this->countColom($count);
        $countColomFooter = $this->countColomFooter($count);

        $rekanan_id = auth()->user()->id_rekanan;

        $pkp = 'tidak';

        $query = PelaksanaanPekerjaan::query();
        if (!auth()->user()->hasRole('superadmin')) {
            if (auth()->user()->hasRole('rekanan')) {
                $rekanan_id = auth()->user()->id_rekanan;
                $query->where('rekanan_id', $rekanan_id);
                $rekanan = Rekanan::find($rekanan_id);

                if ($rekanan->pkp) {
                    if ($rekanan->pkp == 'ya') {
                        $pkp = 'ya';
                    }
                }
            }
            //  else {
            //     $list_rekanan_id = auth()->user()->karyawan->hasRekanan->pluck('id');

            //     if (count($list_rekanan_id) > 0) {
            //         $query->whereIn('rekanan_id', $list_rekanan_id);
            //     }
            // }
        }


        // $hasValue = $this->hasValue;
        $start = Carbon::now()->subMonths(15)->startOfMonth()->format('Y-m-d') . ' 00:00:01';
        $end = Carbon::now()->endOfMonth()->format('Y-m-d') . ' 23:59:59';

        // ->where('status', 'selesai koreksi');
        $query
            ->where(function ($sql) {
                $sql->where('status', 'selesai koreksi')->orWhere('status', 'diadjust');
            })
            ->where('tagihan', 'tidak')
            ->whereBetween(DB::raw('DATE(tanggal_selesai)'), array($start, $end));

        $penunjukan = $query->with('hasItem')->get();

        $totalPekerjaan = 0;
        $ppn = 0;
        foreach ($penunjukan as $key => $value) {
            $totalPekerjaan += $value->total_pekerjaan;
        }
        $totalPekerjaan = pembulatan($totalPekerjaan);
        $totalPekerjaan = str_replace(".", "", $totalPekerjaan);
        if ($pkp == 'ya') {
            $ppn = ($totalPekerjaan * 11) / 100;
        }
        $grand_total = $totalPekerjaan + $ppn;

        // $grand_total =

        return view(
            'tagihan.form',
            compact(
                'title',
                'form',
                'countColom',
                'colomField',
                'penunjukan',
                'countColomFooter',
                'totalPekerjaan',
                'grand_total',
                'pkp',
                'ppn',
                'store',
                'route'
                // 'hasValue'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = $this->model()->find($id);

        $PelaksanaanPekerjaan = $data->hasPelaksanaanPekerjaan();
        // list jabatan
        $listJabatan = Jabatan::query();

        if ($PelaksanaanPekerjaan) {
            $aduanId = $PelaksanaanPekerjaan->pluck('aduan_id')->toArray();
            $katagori_nps = Aduan::whereIn('id', $aduanId)->pluck('kategori_nps')->toArray();
            $katagori_nps_unique = array_unique($katagori_nps);

            if (in_array('dis', $katagori_nps_unique)) {
                $listJabatan = $listJabatan->orWhere('slug', 'manajer-distribusi');
            }
            if (in_array('pka', $katagori_nps_unique)) {
                $listJabatan = $listJabatan->orWhere('slug', 'manajer-pengendalian-kehilangan-air');
            }
        }

        $listJabatan = $listJabatan->orWhere('slug', 'manajer-perencanaan')->orWhere('slug', 'direktur-teknik')->pluck('id')->toArray();
        // list karyawan bedasarkan jabatan
        $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get();

        DB::beginTransaction();
        $user = [];


        $bulan = getRomawi(date('m'));
        $tahun = date('Y');
        // return $request->no_kwitansi_check;
        if (auth()->user()->hasRole('asisten-manajer-tata-usaha')) {

            $messages = [
                'required' => ':attribute tidak boleh kosong',
                'unique' => ':attribute tidak boleh sama',
                'same' => 'Password dan konfirmasi password harus sama',
            ];

            if ($data->pkp == 'ya') {
                $this->validate(request(), [
                    'no_faktur_pajak_rekanan' => 'required|unique:tagihan,no_faktur_pajak,' . $id,
                    'bukti_pembayaran_rekanan' => 'required|unique:tagihan,bukti_pembayaran,' . $id,
                    'e_billing_rekanan' => 'required|unique:tagihan,e_billing,' . $id,
                    'e_spt_rekanan' => 'required|unique:tagihan,e_spt,' . $id,
                    'berita_acara_rekanan' => 'unique:tagihan,berita_acara,' . $id,
                    'no_kwitansi_rekanan' => 'required|unique:tagihan,no_kwitansi,' . $id,
                ], $messages);
            } else {
                $this->validate(request(), [
                    'no_kwitansi_rekanan' => 'required|unique:tagihan,no_kwitansi,' . $id,
                ], $messages);
            }

            // return $status = 'disetujui asmentu';
        }
        try {
            if ($data) {
                if (auth()->user()->hasRole('manajer-perencanaan')) {
                    $status = 'proses';
                }
                if (auth()->user()->karyawan) {
                    $namakaryawan = auth()->user()->karyawan->nama;
                }

                if (auth()->user()->hasRole('direktur-teknik')) {
                    $status = 'disetujui';
                    // $data->nomor_tagihan = $nomor_tagihan;
                    // $data->slug = Str::slug($nomor_tagihan);
                }
                if (auth()->user()->hasRole('asisten-manajer-tata-usaha')) {

                    $status = 'disetujui asmentu';
                    $data->no_kwitansi_check = $request->no_kwitansi_check;
                    $data->berita_acara_check = $request->berita_acara_check;
                    $data->no_faktur_pajak_check = $request->no_faktur_pajak_check;
                    $data->bukti_pembayaran_check = $request->bukti_pembayaran_check;
                    $data->e_billing_check = $request->e_billing_check;
                    $data->e_spt_check = $request->e_spt_check;
                }


                if (auth()->user()->hasRole('manajer-umum-dan-kesekretariatan')) {
                    $status = 'disetujui mu';
                }
                if (auth()->user()->hasRole('direktur-umum')) {
                    $status = 'disetujui dirum';
                }
                if (auth()->user()->hasRole('direktur-utama')) {
                    $status = 'disetujui dirut';
                }


                if (auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan')) {
                    $status = 'disetujui asmenanggaran';

                    $kode_anggaran = $request->kode_anggaran;
                    $pelaksanaan_pekerjaan_id = $request->pelaksanaan_pekerjaan_id;

                    // loop pelaksanaan_pekerjaan_id
                    foreach ($pelaksanaan_pekerjaan_id as $key => $value) {
                        $pelaksanaan = PelaksanaanPekerjaan::find($value);
                        $pelaksanaan->kode_anggaran = $kode_anggaran[$key];
                        $pelaksanaan->save();
                    }

                    // $data->kode_anggaran = $request->kode_anggaran;
                }
                if (auth()->user()->hasRole('asisten-manajer-akuntansi')) {
                    $status = 'disetujui asmenakuntan';
                    $data->kode_vocher = $request->kode_voucher;
                    // $data->total_bayar = str_replace(".", "", $request->total_bayar);
                }
                if (auth()->user()->hasRole('manajer-keuangan')) {
                    $status = 'disetujui mankeu';
                }
                if (auth()->user()->hasRole('asisten-manajer-kas')) {
                    $status = 'disetujui asmenkas';
                }

                $message = 'Berhasil Membayar Tagihan : ' . $data->nomor_tagihan_setujuh;
                $title = "Tagihan telah dibayar";
                $body = "Nomor Tagihan " . $data->nomor_tagihan_setujuh . " telah disetujui oleh " . $namakaryawan;
                $modul = "tagihan";

                $data->status = $status;
                $data->save();

                $user[auth()->user()->id] = [
                    'keterangan' => $status,
                ];
                $data->hasUserMany()->attach($user);

                $namakaryawan = '';



                $title = "Tagihan telah setujui";
                $body = "Nomor Tagihan " . $data->nomor_tagihan_setujuh . " telah disetujui oleh " . $namakaryawan;
                $modul = "tagihan";

                $rekanan = Rekanan::find($data->rekanan_id);
                if ($rekanan) {
                    // $this->notification($data->id, $data->slug, $title, $body, $modul, auth()->user()->id, $rekanan->user_id);

                    if ($listKaryawan) {
                        foreach (collect($listKaryawan) as $i => $kr) {
                            if (auth()->user()->id !== $kr->user_id) {
                                $this->notification($data->id, $data->slug, $title, $body, $modul, auth()->user()->id, $kr->user_id);
                            }
                        }
                    }
                }
                DB::commit();

                return redirect()->route('tagihan.index')->with('message', $message)->with('Class', 'primary');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('tagihan.index')->with('message', 'Tagihan gagal disetujui')->with('Class', 'danger');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $pelaksanaan = $request->pelaksanaan;
        try {
            $tanggal_tagihan = Carbon::now();
            $tagihan = $this->model()->count();
            $bulan = date('m');

            DB::beginTransaction();

            // $PelaksanaanPekerjaan = PelaksanaanPekerjaan::whereIn('id', $pelaksanaan)
            //     ->where('tagihan', 'tidak')->first();

            // if (!auth()->user()->hasRole('superadmin')) {
            //     if (auth()->user()->hasRole('rekanan')) {
            //     } else {
            //         $rekanan_id = $PelaksanaanPekerjaan->rekanan_id;
            //     }
            // }
            $rekanan_id = auth()->user()->id_rekanan;

            // foreach ($pelaksanaan as $value) {
            //     $PelaksanaanPekerjaan = PelaksanaanPekerjaan::where('id', $value)
            //         ->where('tagihan', 'tidak')
            //         ->where(
            //             'rekanan_id',
            //             $rekanan_id
            //         )->first();

            //     // if ($PelaksanaanPekerjaan) {
            //     //     if ($PelaksanaanPekerjaan->total_pekerjaan > 40000000) {

            //     //         DB::rollback();

            //     //         return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' ada yang lebih dari Rp. 40.000.00 (Empat Puluh juta rupiah) di SPK ' . $PelaksanaanPekerjaan->nomor_pelaksanaan_pekerjaan)->with('Class', 'danger');
            //     //     }
            //     // }
            // }
            $rekanan = Rekanan::find($rekanan_id);

            $singkatan = "";

            if ($rekanan) {

                $singkatan = $rekanan->singkatan;
            }

            if ($tagihan >= 1) {
                $no = str_pad($tagihan + 1, 4, "0", STR_PAD_LEFT);
                $nomor_tagihan = $no . "/" . rand(0, 900) . "/" . "BAPP-" . $singkatan;
            } else {
                $no = str_pad(1, 4, "0", STR_PAD_LEFT);
                $nomor_tagihan = $no . "/" . rand(0, 900) . "/" . "BAPP-" . $singkatan;
            }



            $title = "Tagihan telah dibuat";
            $body = "Nomor Tagihan " . $nomor_tagihan . " telah dibuat";
            $modul = "tagihan";

            // list jabatan
            // $listJabatan = Jabatan::query();


            // if ($PelaksanaanPekerjaan) {
            //     $aduanId = $PelaksanaanPekerjaan->pluck('aduan_id')->toArray();
            //     $katagori_nps = Aduan::whereIn('id', $aduanId)->pluck('kategori_nps')->toArray();
            //     $katagori_nps_unique = array_unique($katagori_nps);

            //     if (in_array('dis', $katagori_nps_unique)) {
            //         $listJabatan = $listJabatan->orWhere('slug', 'manajer-distribusi');
            //     }
            //     if (in_array('pka', $katagori_nps_unique)) {
            //         $listJabatan = $listJabatan->orWhere('slug', 'manajer-pengendalian-kehilangan-air');
            //     }
            // }

            // $listJabatan = $listJabatan->orWhere('slug', 'manajer-perencanaan')->orWhere('slug', 'direktur-teknik')->pluck('id')->toArray();
            // // list karyawan bedasarkan jabatan
            // $listKaryawan = Karyawan::whereIn('jabatan_id', $listJabatan)->get();


            if (auth()->user()->hasRole('rekanan')) {
                // $rekanan = Rekanan::find($rekanan_id);
                // notif ke staf pengawas
                // if ($rekanan->hasKaryawan) {
                //     foreach (collect($rekanan->hasKaryawan) as $key => $value) {
                //         $this->notification($data->id, $data->slug, $title, $body, $modul, auth()->user()->id, $value->user_id);
                //     }
                // }
            }

            // if ($listKaryawan) {
            //     foreach (collect($listKaryawan) as $i => $kr) {
            //         $this->notification($data->id, $data->slug, $title, $body, $modul, auth()->user()->id, $kr->user_id);
            //     }
            // }

            $fifteenMinutesAgo = Carbon::now()->subMinutes(15);

            $hasRecentPosts = $this->model()->where('created_at', '>=', $fifteenMinutesAgo) ->where(
                'rekanan_id',
                $rekanan_id
            )->exists();

            if ($hasRecentPosts) {
                return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . " " . ' sudah berhasil ditambahkan')->with('Class', 'success');
            }

            PelaksanaanPekerjaan::whereIn('id', $pelaksanaan)
                ->where('tagihan', 'tidak')
                ->where(
                    'rekanan_id',
                    $rekanan_id
                )->update(['tagihan' => 'ya']);

            $penunjukanPekerjaanIds = PelaksanaanPekerjaan::whereIn('id', $pelaksanaan)
                ->where('rekanan_id', $rekanan_id)
                ->pluck('penunjukan_pekerjaan_id')
                ->toArray();

            // Update tagihan in PenunjukanPekerjaan
            PenunjukanPekerjaan::whereIn('id', $penunjukanPekerjaanIds)
                ->where('tagihan', 'tidak')
                ->update(['tagihan' => 'ya']);

            $data = $this->model();
            $data->nomor_tagihan = $nomor_tagihan;
            $data->tanggal_tagihan = $tanggal_tagihan;
            $data->rekanan_id = $rekanan_id;
            $data->user_id = auth()->user()->id;
            $data->status = 'dikirim';
            $data->save();

            $data->hasPelaksanaanPekerjaan()->sync($pelaksanaan);
            DB::commit();


            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . " " . $nomor_tagihan . ' Berhasil Ditambahkan')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' gagal Ditambahkan')->with('Class', 'success');
        }
    }
    /**
     * upload the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        $title = "Upload Data Tagihan";
        $route = $this->route;
        $dataRekanan = Rekanan::get();
        $action = route('tagihan.uploaddata');
        $month = [];

        for ($m = 1; $m <= 12; $m++) {
            $month[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }

        return view(
            'tagihan.upload',
            compact(
                "title",
                "route",
                "month",
                "dataRekanan",
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

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
            'same' => 'Password dan konfirmasi password harus sama',
        ];

        $nomor_tagihan = $request->nomor_tagihan_setujuh;
        $total_lokasi = $request->total_lokasi;
        $rekanan_id = $request->rekanan_id;

        $this->validate(request(), [
            'nomor_tagihan' => 'required',
            'unique:tagihan,nomor_tagihan,' . $nomor_tagihan . ',NULL,id,rekanan_id,' . $rekanan_id,
            'rekanan_id' => 'required',
            'bulan' => 'required',
        ], $messages);

        $tahun = Carbon::now()->formatLocalized("%Y");
        $tanggal = Carbon::now()->daysInMonth;
        $bulan = $request->bulan;

        $tanggal_tagihan = Carbon::parse($tahun . '-' . $bulan . "-" . $tanggal)->format('Y-m-d');



        $dataJenisItem = Jenis::where('nama', 'LIKE', "%baru%")->first();
        $satuan = Satuan::where('nama', 'LIKE', "%pcs%")->first();

        $dataItem = [];
        $dataListItem = [];
        $ListItem = [];
        $dataJenisHarga = [];
        $itemExist = [];
        $dataJumlah = [];
        $dataHarga = [];
        $hargaItem = [];
        $dataMaster = [];
        $dataHargaMaster = [];
        $dataHargaUraian = [];

        // DB::beginTransaction();

        $file = $request->hasFile('file');
        $total_uraian = 0;
        $total_master = 0;
        if ($file) {
            $tagihan = $this->model()
                ->where('nomor_tagihan', $nomor_tagihan)
                ->where('rekanan_id', $rekanan_id)
                ->where('tanggal_tagihan', $tanggal_tagihan)
                ->first();

            if (empty($tagihan)) {
                $tagihan = new Tagihan;
            }
            $tagihan->nomor_tagihan_setujuh = $nomor_tagihan;
            $tagihan->rekanan_id = $rekanan_id;
            $tagihan->total_lokasi = $total_lokasi;
            $tagihan->tanggal_tagihan = $tanggal_tagihan;
            $tagihan->user_id = auth()->user()->id;
            $tagihan->status = 'dikirim';
            $tagihan->save();

            $item = Excel::toArray('', request()->file('file'), null, null);
            foreach ($item[0] as $k => $val) {
                $dataItem[$k] = $val;
            }
            $nomor = 0;
            $countAll = [];
            $count = 0;

            $dataItemExist = [];
            $lastquery = [];
            $nomorkey = 0;
            $urutan = 1;

            foreach ($dataItem as $index => $item) {
                if ($index > 3) {

                    $dataNoPekerjaan[$index] = $item[1] == null ? '' : $item[1];
                    $dataNama[$index] = $item[2] == null ? '' : $item[2];
                    $dataJenisHarga[$index] = $item[3];
                    $dataJumlah[$index] = $item[4] == null ? 0 : $item[4];
                    $dataHarga[$index] = $item[5] == null ? 0 : $item[5];

                    $ListItem[$nomor] = [
                        'no_pekerjaan' => $dataNoPekerjaan[$index],
                        'uraian' => $dataNama[$index],
                        // 'master' =>  $dataMaster[$index],
                        'harga_uraian' => $dataHarga[$index],
                        // 'harga_master' =>  $itemExist[$index]->harga,
                        'jumlah' => number_format($dataJumlah[$index], 2),
                        'jenis_harga' => $dataJenisHarga[$index]
                        // 'total_master' =>  $item[2] * $hargaItem[$index],
                    ];
                    $nomor++;
                }
            }

            // return $ListItem;

            $countAll = [];
            foreach ($ListItem as $key => $val) {

                $searchValues[$key] = preg_split('/\s+/', $val['uraian'], -1, PREG_SPLIT_NO_EMPTY);
                $queryRaw = '';
                $countAll[$key] = 0;

                $dataListItem[$key] = Item::query();
                if (count($searchValues[$key]) == 1) {
                    foreach ($searchValues[$key] as $j => $kword) {
                        if ($kword !== "Dan") {
                            $dataListItem[$key]->where('nama', 'like', "%{$kword}%");
                            $countAll[$key] = $countAll[$key] + 1;
                        }
                    }
                } else {
                    $nomorkey = 0;
                    foreach ($searchValues[$key] as $i => $word) {
                        if (preg_match("/^[a-zA-Z0-9]+$/", $word) == 1) {
                            if ($queryRaw) {
                                $count = Item::whereRaw(rtrim($queryRaw, " and"))->count();
                                if ($count > 0) {
                                    $lastquery = $queryRaw;

                                    if ($word !== "Dan") {
                                        $queryRaw .= 'nama LIKE "%' . $word . '%" and ';
                                        $countAll[$key] = $countAll[$key] + 1;

                                        if (Item::whereRaw(rtrim($queryRaw, " and"))->count() == 0) {
                                            $queryRaw = $lastquery;
                                        }
                                    }
                                }
                            } else {
                                if (Item::where('nama', 'like', "%{$word}%")->count() > 0) {
                                    if ($word !== "Dan") {
                                        $countAll[$key] = $countAll[$key] + 1;
                                        $queryRaw .= 'nama LIKE "%' . $word . '%" and ';
                                    }
                                } else {
                                    // $countAll[$key] = 0;
                                    continue;
                                }
                            }

                            $nomorkey++;
                        }
                    }
                }
                // if ($countAll > 1) {
                //     $dataListItem[$key]->where('id',  "");
                // }

                $dataItemExist[$key] = rtrim($queryRaw, " and");
                if ($queryRaw) {
                    $dataListItem[$key]->whereRaw($dataItemExist[$key]);
                }

                if ($countAll[$key] == 0) {
                    $dataListItem[$key]->where('id', "");
                }

                if ($dataListItem[$key]->first()) {
                    $itemExist[$key] = $dataListItem[$key]->first();
                    if ($val['jenis_harga'] === 'malam') {
                        $hargaItem[$key] = $itemExist[$key]->harga_malam;
                    } else {
                        $hargaItem[$key] = $itemExist[$key]->harga;
                    }
                    $total_master++;
                    $dataMaster[$key] = $itemExist[$key]->nama;

                    $dataListItem[$key] = [
                        'uraian' => $val['uraian'],
                        'count' => $countAll[$key],
                        'master' => $itemExist[$key]->nama,
                        'harga_uraian' => $val['harga_uraian'],
                        'harga_master' => $itemExist[$key]->harga,
                        'jumlah' => $val['jumlah'],
                        'jenis_harga' => $val['jenis_harga'],
                        // 'total_master' =>  $item[2] * $hargaItem[$index],
                        'grand_total' => $itemExist[$key]->harga * $val['jumlah'],
                    ];
                    $total_master++;
                } else {
                    $dataListItem[$key] = [
                        'uraian' => $val['uraian'],
                        'count' => $countAll[$key],
                        'master' => '',
                        'harga_uraian' => $val['harga_uraian'],
                        'harga_master' => 0,
                        'jumlah' => $val['jumlah'],
                        'jenis_harga' => $val['jenis_harga'],
                        'grand_total' => 0,
                    ];
                    $itemExist[$key] = new Item;
                    $itemExist[$key]->nama = $val['uraian'];
                    $itemExist[$key]->harga = 0;
                    $itemExist[$key]->harga_malam = 0;
                    $itemExist[$key]->satuan_id = $satuan->id;
                    $itemExist[$key]->jenis_id = $dataJenisItem->id;
                    $itemExist[$key]->save();
                    $dataMaster[$key] = '';
                    $total_uraian++;
                }



                $tagihanItem[$key] = TagihanItem::where('tagihan_id', $tagihan->id)->where('item_id', $itemExist[$key]->id)->where('urutan', $key + 1)->first();

                if (empty($tagihanItem[$key])) {
                    $tagihanItem[$key] = new TagihanItem;
                }

                $tagihanItem[$key]->uraian = $val['uraian'];
                $tagihanItem[$key]->no_pekerjaan = $val['no_pekerjaan'];
                $tagihanItem[$key]->master = $dataMaster[$key];
                $tagihanItem[$key]->jumlah = $val['jumlah'];
                $tagihanItem[$key]->harga_uraian = $val['harga_uraian'];
                $tagihanItem[$key]->harga_master = $itemExist[$key]->harga;
                $tagihanItem[$key]->total_uraian = $val['harga_uraian'] * $val['jumlah'];
                $tagihanItem[$key]->total_master = $itemExist[$key]->harga * $val['jumlah'];
                $tagihanItem[$key]->jenis_harga = 'malam';

                if ($val['jenis_harga'] === 'malam') {
                } else {
                    $tagihanItem[$key]->jenis_harga = 'siang';
                }

                if ($val['harga_uraian'] >= $itemExist[$key]->harga) {
                    $tagihanItem[$key]->grand_total = $itemExist[$key]->harga * $val['jumlah'];
                } elseif ($val['harga_uraian'] <= $itemExist[$key]->harga) {
                    $tagihanItem[$key]->grand_total = $val['harga_uraian'] * $val['jumlah'];
                }

                if ($val['harga_uraian'] != $itemExist[$key]->harga) {
                    $tagihanItem[$key]->selisih = 'ya';
                    if ($val['harga_uraian'] >= $itemExist[$key]->harga) {
                        $tagihanItem[$key]->grand_total_adjust = $itemExist[$key]->harga * $val['jumlah'];
                        $tagihanItem[$key]->total_adjust = $itemExist[$key]->harga;
                    } elseif ($val['harga_uraian'] <= $itemExist[$key]->harga) {
                        $tagihanItem[$key]->grand_total_adjust = $val['harga_uraian'] * $val['jumlah'];
                        $tagihanItem[$key]->total_adjust = $val['harga_uraian'];
                    }
                } else {
                    $tagihanItem[$key]->selisih = 'tidak';
                    if ($val['harga_uraian'] >= $itemExist[$key]->harga) {
                        $tagihanItem[$key]->grand_total_adjust = $itemExist[$key]->harga * $val['jumlah'];
                        $tagihanItem[$key]->total_adjust = $itemExist[$key]->harga;
                    } elseif ($val['harga_uraian'] <= $itemExist[$key]->harga) {
                        $tagihanItem[$key]->grand_total_adjust = $val['harga_uraian'] * $val['jumlah'];
                        $tagihanItem[$key]->total_adjust = $val['harga_uraian'];
                    }
                }

                $tagihanItem[$key]->urutan = $urutan;
                $tagihanItem[$key]->item_id = $itemExist[$key]->id;
                $tagihanItem[$key]->tagihan_id = $tagihan->id;
                $tagihanItem[$key]->save();

                $urutan++;
            }
            // return $dataListItem;

            return redirect()->route($this->route . '.show', $tagihan->slug)->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil diupload dengan total item dari kamus data sebanyak : ' . $total_master . ' dan data yang baru sebanyak : ' . $total_uraian)->with('Class', 'success');
        }
        try {
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' gagal diupload')->with('Class', 'success');
        }
    }

    public function adjust(Request $request)
    {
        $id = $request->id;
        $harga = $request->harga;
        $jumlah = $request->jumlah;

        $grand_total = $jumlah * str_replace(".", "", $harga);
        $item_id = $request->item_id;

        $itemData = Item::find($item_id);
        // return $id;
        try {
            $query = TagihanItem::find($id);
            $query->total_adjust = str_replace(".", "", $harga);
            $query->tanggal_adjust = date('Y-m-d H:i:s');
            $query->selisih = 'tidak';
            $query->item_id = $item_id;
            $query->grand_total = $grand_total;
            $query->master = $itemData->nama;
            $query->save();

            $result = [
                'tanggal' => $query->tanggal_adjust_indo,
                'id' => $query->id,
                'grand_total' => $query->grand_total,
            ];
            $message = 'Data Tagihan berhasil diubah';
            return $this->sendResponse($result, $message, 200);
        } catch (\Throwable $th) {
            $message = 'Data tidak Tagihan ada';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }

    public function exxceltagihan()
    {
        $id = request()->get('id') ?: "";
        $tagihan = Tagihan::find($id);
        return Excel::download(new ExportTagihan($id), 'Export Tagihan ' . $tagihan->nomor_tagihan_setujuh . ' - Rekanan ' . $tagihan->rekanan . '.xlsx');
    }

    public function wordtagihan()
    {

        $id = request()->get('id') ?: "";
        $word = request()->get('word') ?: "";
        $tagihan = Tagihan::find($id);
        $now = '';
        $nowRekanan = '';
        $tanggal = '';
        $tanggalDirut = '';
        $bulan = bulan_indonesia(Carbon::parse($tagihan->tanggal_adjust));
        $tahun = Carbon::parse($tagihan->tanggal_adjust)->format('Y');
        $tahunBap = Carbon::parse($tagihan->tanggal_adjust)->format('Y');
        if ($tagihan->hasPelaksanaanPekerjaan()) {
            $PelaksanaanPekerjaan = $tagihan->hasPelaksanaanPekerjaan();
            $tanggalSelesai = $PelaksanaanPekerjaan->pluck('tanggal_selesai')->toArray();;
            $tanggalPertama = min(array_map(function ($item) {
                return $item;
            }, $tanggalSelesai));
            $tanggalAkhir = max(array_map(function ($item) {
                return $item;
            }, $tanggalSelesai));

            $bulanAwal = bulan_indonesia(Carbon::parse($tanggalPertama));
            $bulanAkhir = bulan_indonesia(Carbon::parse($tanggalAkhir));

            if ($bulanAwal == $bulanAkhir) {
                $bulan = $bulanAwal;
            } else {
                $bulan = $bulanAwal . ' s/d ' . $bulanAkhir;
            }
            $tahunBap = Carbon::parse($tanggalAkhir)->format('Y');
        }
        if (isset($tagihan->list_persetujuan_direktur_teknik['created_at']) && $word != "bapp") {
            $tanggal = $tagihan->list_persetujuan_direktur_teknik['created_at'];
            $tahun = Carbon::parse($tanggal)->format('Y');
            $now = tanggal_indonesia_terbilang($tanggal, true, false);
            $nowRekanan = tanggal_indonesia($tanggal, false, false);
            $tanggal = tanggal_indonesia(Carbon::parse($tanggal), false, false);


            $wilayah = [];
        }
        if (isset($tagihan->list_persetujuan_direktur_utama['created_at']) && $word == "bapp") {

            if ($tagihan->list_persetujuan_direktur_utama['created_at']) {
                $tanggalDirut = $tagihan->list_persetujuan_direktur_utama['created_at'];
                $tahun = Carbon::parse($tanggalDirut)->format('Y');
                $now = tanggal_indonesia_terbilang($tanggalDirut, true, false);

                $tanggalDirut = tanggal_indonesia(Carbon::parse($tanggalDirut), false, false);

                $wilayah = [];
            }
        }

        if ($tagihan->hasPelaksanaanPekerjaan) {
            foreach ($tagihan->hasPelaksanaanPekerjaan as $key => $value) {

                $wilayah[$key] = $value->wilayah;
            }
        }

        $rekanan = Rekanan::find($tagihan->rekanan_id);

        $singkatan = $rekanan->singkatan;



        $stafPengawas = $rekanan->hasKaryawan;

        $wilayah = array_unique($wilayah);

        $wilayah = sortByRoman($wilayah);

        if (count($wilayah) == 2) {
            $wilayah = implode(" dan ", $wilayah);
        } else {
            $last = array_slice($wilayah, -1);
            $wilayah = implode(', ', array_slice($wilayah, 0, -1)) . (count($wilayah) > 1 ? ', dan ' : '') . implode('', $last);
        }



        $filename = "Tagihan Rekenan " . $tagihan->rekanan . " Nomor " . $tagihan->nomor_tagihan_setujuh;
        $title = "Tagihan : " . $tagihan->nomor_tagihan_setujuh;

        $total = $tagihan->tagihan + $tagihan->galian;
        $total = pembulatan($total);
        $total = str_replace(".", "", $total);

        $ppn = 0;
        $pkp = "tidak";

        if ($tagihan->hasRekanan->pkp) {
            if ($tagihan->hasRekanan->pkp == 'ya') {
                $pkp = "ya";
                $ppn = ($total * 11) / 100;
            }
        }
        $total_tagihan = $total + $ppn;
        // $total_tagihan = pembulatan($total_tagihan);
        $total_lokasi = $tagihan->total_lokasi_pekerjaan;

        $listJabatan = Jabatan::where('slug', 'manajer-perencanaan')->orWhere('slug', 'direktur-teknik')->get()->pluck('id')->toArray();

        // list karyawan bedasarkan jabatan
        $direktur = Karyawan::where('jabatan_id', $listJabatan)->first();

        $preview = $tagihan->slug;

        if ($word === "rekanan") {
            return view(
                'tagihan.wordrekanan',
                compact(
                    "title",
                    "wilayah",
                    "tahunBap",
                    "singkatan",
                    "total_tagihan",
                    "total_lokasi",
                    "filename",
                    "bulan",
                    "tahun",
                    "preview",
                    "stafPengawas",
                    "nowRekanan",
                    "now",
                    "tanggal",
                    "tagihan"
                )
            );
        }
        if ($word === "bapp") {
            return view(
                'tagihan.bapp',
                compact(
                    "title",
                    "wilayah",
                    "singkatan",
                    "total_tagihan",
                    "direktur",
                    "total_lokasi",
                    "filename",
                    "pkp",
                    "tahunBap",
                    "bulan",
                    "tahun",
                    "preview",
                    "stafPengawas",
                    "now",
                    "tanggal",
                    "tanggalDirut",
                    "tagihan"
                )
            );
        }

        return view(
            'tagihan.word',
            compact(
                "title",
                "wilayah",
                "singkatan",
                "total_tagihan",
                "tahunBap",
                "direktur",
                "total_lokasi",
                "filename",
                "bulan",
                "tahun",
                "preview",
                "stafPengawas",
                "now",
                "tanggal",
                "tagihan"
            )
        );
    }

    public function preview($slug)
    {
        $now = capital_tanggal_indonesia(Carbon::now());

        $id = request()->get('id') ?: "";
        $tagihan = Tagihan::whereSlug($slug)->first();

        $wilayah = '';

        if ($tagihan->hasPelaksanaanPekerjaan) {
            foreach ($tagihan->hasPelaksanaanPekerjaan as $key => $value) {
                $wilayah .= $value->wilayah . ', ';
            }
        }

        $rekanan = Rekanan::find($tagihan->rekanan_id);

        $stafPengawas = $rekanan->hasKaryawan;

        $wilayah = rtrim($wilayah, ", ");

        $filename = "Tagihan Rekenan " . $tagihan->rekanan . " Nomor " . $tagihan->nomor_tagihan_setujuh;
        $title = "Tagihan : " . $tagihan->nomor_tagihan_setujuh;
        $bulan = bulan_indonesia(Carbon::parse($tagihan->tanggal_adjust));
        $tanggal = tanggal_indonesia(Carbon::parse($tagihan->tanggal_adjust), false);

        $total = $tagihan->tagihan + $tagihan->galian;
        $ppn = 0;

        if ($tagihan->hasRekanan->pkp) {
            if ($tagihan->hasRekanan->pkp == 'ya') {
                $ppn = ($total * 11) / 100;
            }
        }
        $total_tagihan = $total + $ppn;
        $total_lokasi = $tagihan->total_lokasi_pekerjaan;

        $preview = $tagihan->slug;

        return view(
            'tagihan.preview',
            compact(
                "title",
                "wilayah",
                "total_tagihan",
                "total_lokasi",
                "filename",
                "stafPengawas",
                "bulan",
                "preview",
                "now",
                "tanggal",
                "tagihan"
            )
        );
    }

    public function dokumen(Request $request, $id)
    {

        DB::beginTransaction();
        $route = $this->route;
        $data = $this->model()->find($id);
        $nomor_tagihan = $data->nomor_tagihan;
        $slug_nomor_tagihan = $data->slug;
        $rekanan = $data->rekanan;
        $rekanan_pkp = $data->pkp;
        $kirim_wa = $request->kirim_wa;

        $no_faktur_pajak = $request->no_faktur_pajak;
        $no_faktur_pajak_image = $request->no_faktur_pajak_image;
        $e_billing = $request->e_billing;
        $e_billing_image = $request->e_billing_image;
        $bukti_pembayaran = $request->bukti_pembayaran;
        $bukti_pembayaran_image = $request->bukti_pembayaran_image;
        $e_spt = $request->e_spt;
        $e_spt_image = $request->e_spt_image;
        $berita_acara = $request->berita_acara;
        $berita_acara_image = $request->berita_acara_image;
        $no_kwitansi = $request->no_kwitansi;
        $no_kwitansi_image = $request->no_kwitansi_image;

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
            'same' => 'Password dan konfirmasi password harus sama',
        ];
        if ($kirim_wa == 'kirim') {
            $erros = [];
            if ($data->no_kwitansi == null) {
                $data->no_kwitansi_check = 'tidak';
                $erros[] = 'no_kwitansi';
            } else {
                $data->no_kwitansi_check = $request->no_kwitansi_kirim != null ? $request->no_kwitansi_kirim : "tidak";
            }
            if ($data->pkp == 'ya') {

                if ($data->no_faktur_pajak == null) {
                    $data->no_faktur_pajak_check = 'tidak';
                    $erros[] = 'no_faktur_pajak_rekanan';
                } else {
                    $data->no_faktur_pajak_check = $request->no_faktur_pajak_kirim != null ? $request->no_faktur_pajak_kirim : "tidak";
                }

                if ($data->bukti_pembayaran == null) {
                    $data->bukti_pembayaran_check = 'tidak';
                    $erros[] = 'bukti_pembayaran';
                } else {
                    $data->bukti_pembayaran_check = $request->bukti_pembayaran_kirim != null ? $request->bukti_pembayaran_kirim : "tidak";
                }
                if ($data->e_billing == null) {
                    $data->e_billing_check = 'tidak';
                    $erros[] = 'e_billing';
                } else {
                    $data->e_billing_check = $request->e_billing_kirim != null ? $request->e_billing_kirim : "tidak";
                }
                if ($data->e_spt == null) {
                    $data->e_spt_check = 'tidak';
                    $erros[] = 'e_spt';
                } else {
                    $data->e_spt_check = $request->e_spt_kirim != null ? $request->e_spt_kirim : "tidak";
                }
                if ($data->berita_acara == null) {
                    $data->berita_acara_check = 'tidak';
                    $erros[] = 'berita_acara';
                } else {
                    $data->berita_acara_check = $request->berita_acara_kirim != null ? $request->berita_acara_kirim : "tidak";
                }
            }

            $data->save();

            DB::commit();

            if (count($erros) > 0) {
                return redirect()->route($this->route . '.show', $data->slug)->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Persyaratan tidak sesuai')->with('Class', 'success')->with('wa', 'kirim')->with('erros', $erros);
            }


            return redirect()->route($this->route . '.show', $data->slug)->with('wa', 'kirim')->with('Class', 'success');
        }
        if ($rekanan_pkp == 'ya') {
            $this->validate(request(), [
                'no_faktur_pajak' => 'required|unique:tagihan,no_faktur_pajak,' . $id,
                'no_faktur_pajak_image' => 'required|sometimes|mimes:pdf',
                'bukti_pembayaran' => 'required|unique:tagihan,bukti_pembayaran,' . $id,
                'bukti_pembayaran_image' => 'required|sometimes|mimes:pdf',
                'e_billing' => 'required|unique:tagihan,e_billing,' . $id,
                'e_billing_image' => 'required|sometimes|mimes:pdf',
                'e_spt' => 'required|unique:tagihan,e_spt,' . $id,
                'e_spt_image' => 'required|sometimes|mimes:pdf',
                'no_kwitansi' => 'required|unique:tagihan,no_kwitansi,' . $id,
                'no_kwitansi_image' => 'required|sometimes|mimes:pdf',
                'berita_acara' => 'nullable|sometimes|unique:tagihan,berita_acara,' . $id,
                'berita_acara_image' => 'sometimes|mimes:pdf',
            ], $messages);
        } else {
            $this->validate(request(), [
                'no_kwitansi' => 'required|unique:tagihan,no_kwitansi,' . $id,
                'no_kwitansi_image' => 'required|sometimes|mimes:pdf',
            ], $messages);
        }

        // return "stipo";


        try {
            $data->no_faktur_pajak = $no_faktur_pajak;
            // upload file no_faktur_pajak ke storage
            if ($request->hasFile('no_faktur_pajak_image')) {
                $file = $request->file('no_faktur_pajak_image');
                $filename = $slug_nomor_tagihan . '.' . $file->getClientOriginalName();
                // ganti nama file
                $file->storeAs('public/' . $route . '/', $filename);
                $data->no_faktur_pajak_image = $filename;
            }

            $data->bukti_pembayaran = $bukti_pembayaran;
            // upload file bukti_pembayaran ke storage
            if ($request->hasFile('bukti_pembayaran_image')) {
                $file = $request->file('bukti_pembayaran_image');
                $filename = $slug_nomor_tagihan . '.' . $file->getClientOriginalName();
                // ganti nama file
                $file->storeAs('public/' . $route . '/', $filename);
                $data->bukti_pembayaran_image = $filename;
            }

            $data->e_billing = $e_billing;
            // upload file e_billing ke storage
            if ($request->hasFile('e_billing_image')) {
                $file = $request->file('e_billing_image');
                $filename = $slug_nomor_tagihan . '.' . $file->getClientOriginalName();
                // ganti nama file
                $file->storeAs('public/' . $route . '/', $filename);
                $data->e_billing_image = $filename;
            }

            $data->e_spt = $e_spt;
            // upload file e_spt ke storage
            if ($request->hasFile('e_spt_image')) {
                $file = $request->file('e_spt_image');
                $filename = $slug_nomor_tagihan . '.' . $file->getClientOriginalName();
                // ganti nama file
                $file->storeAs('public/' . $route . '/', $filename);
                $data->e_spt_image = $filename;
            }

            $data->no_kwitansi = $no_kwitansi;
            // upload file no_kwitansi ke storage
            if ($request->hasFile('no_kwitansi_image')) {
                $file = $request->file('no_kwitansi_image');
                $filename = $slug_nomor_tagihan . '.' . $file->getClientOriginalName();
                // ganti nama file
                $file->storeAs('public/' . $route . '/', $filename);
                $data->no_kwitansi_image = $filename;
            }
            $data->berita_acara = $berita_acara;
            // upload file berita_acara ke storage
            if ($request->hasFile('berita_acara_image')) {
                $file = $request->file('berita_acara_image');
                $filename = $slug_nomor_tagihan . '.' . $file->getClientOriginalName();
                // ganti nama file
                $file->storeAs('public/' . $route . '/', $filename);
                $data->berita_acara_image = $filename;
            }

            $data->save();

            DB::commit();

            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . " " . $nomor_tagihan . ' Berhasil Ditambahkan')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' gagal Ditambahkan')->with('Class', 'success');
        }
    }

    public function model()
    {
        return new Tagihan();
    }
}
