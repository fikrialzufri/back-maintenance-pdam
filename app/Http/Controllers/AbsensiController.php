<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Gudang;
use App\Models\Toko;
use App\Models\Karyawan;
use App\Models\Jadwal;
use App\Traits\CrudTrait;
use Session;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'absensi';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        // $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'nama_karyawan',
                'alias'    => 'Nama Karyawan',
            ],
            [
                'name'    => 'status',
                'alias'    => 'Status',
            ],
            [
                'name'    => 'keterangan',
                'alias'    => 'Keterangan',
            ],
            [
                'name'    => 'waktu',
                'alias'    => 'Waktu',
            ],
            [
                'name'    => 'menit_tampil',
                'alias'    => 'Jumlah Terlambat',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Status',
                'value'    => null
            ],
        ];
    }
    public function configForm()
    {

        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama',
                'validasi'    => ['required', 'unique', 'min:1'],
            ]
        ];
    }

    public function model()
    {
        return new Absensi();
    }

    public function create()
    {
        return view('absensi.create');
    }

    public function store()
    {
        $title = 'Absensi';
        $karyawanId = auth()->user()->karyawan->id;
        $gudang = Karyawan::find($karyawanId)->hasGudang;
        $gudangId = [];
        $gudangIp = '';
        $toko = Karyawan::find($karyawanId)->hasToko;
        $tokoId = [];
        $tokoIp = '';

        $jadwal = Jadwal::where('karyawan_id', $karyawanId)->whereHas('hasRoster', function ($roster) {
            $roster->where('tanggal', now()->format('Y-m-d'));
        })->first();

        if (isset($toko) || isset($gudang)) {
            // Cek Toko
            foreach ($toko as $index => $value) {
                $tokoId[$index] = $value->id;
            }
            $tokos = Toko::whereIn('id', $tokoId)->first();

            if (isset($tokos)) {
                $tokoIp = $tokos->ip_public;
            }

            // Cek Gudang
            foreach ($gudang as $index => $value) {
                $gudangId[$index] = $value->id;
            }
            $gudangs = Gudang::whereIn('id', $gudangId)->first();

            if (isset($gudangs)) {
                $gudangIp = $gudangs->ip_public;
            }
        }

        // Execute Absensi
        if (request()->ip() == $gudangIp || request()->ip() == $tokoIp) {
            $absensi = new Absensi;
            $absensi->karyawan_id = $karyawanId;
            $absensi->status = "Masuk";
            $absensi->jadwal_id = $jadwal->id;
            $denda = 0;
            if (now()->format('H:i') <= Carbon::parse($jadwal->hasRoster->jam_masuk)->addMinutes(15)->format('H:i')) {
                $absensi->keterangan = "Tepat Waktu";
            } else {
                $absensi->keterangan = "Terlambat";
                $absensi->menit = Carbon::parse($jadwal->hasRoster->jam_masuk)->diffInMinutes(Carbon::parse(now()));
                $tes = auth()->user()->karyawan->hasJabatan->hasShift;
                foreach ($tes as $value) {
                    $denda = $value->pivot->denda;
                }
                $absensi->denda = $denda;
            }
            $absensi->save();
            Session::flash('success', 'Berhasil melakukan absensi pada jam ' . $absensi->created_at->format('h:i A'));
            return redirect(route('absensi.scan'));
        } else {
            Session::flash('error', 'Gagal melakukan absensi, silahkan coba lagi !');
            return redirect(route('absensi.scan'));
        }
    }

    public function scan()
    {
        // return request()->ip();
        $title = "Absensi";
        return view('absensi.scan', compact('title'));
    }
}
