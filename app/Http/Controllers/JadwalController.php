<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Traits\CrudTrait;
use App\Imports\JadwalImport;
use Excel;
use Session;

class JadwalController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'jadwal';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name' => 'karyawan',
                'alias' => 'Nama Karyawan',
            ],
            [
                'name' => 'tanggal',
                'alias' => 'Tanggal',
                'input' => 'date',
            ],
            [
                'name' => 'shift',
                'alias' => 'Shift',
            ],
            [
                'name' => 'jam_masuk',
                'alias' => 'Jam Masuk',
            ],
            [
                'name' => 'jam_pulang',
                'alias' => 'Jam Pulang',
            ],
        ];
    }

    public function configSearch()
    {
        return [
            [
                'name' => 'name',
                'input' => 'text',
                'alias' => 'Nama',
                'value' => null
            ],
        ];
    }

    public function configForm()
    {
        return [
            [
                'name' => 'karyawan_id',
                'input' => 'combo',
                'alias' => 'Nama Karyawan',
                'value' => $this->combobox('Karyawan'),
            ],
            [
                'name'  => 'roster_id',
                'input' => 'combo',
                'alias' => 'Roster',
                'value' => $this->combobox(
                    'Roster',
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    ['jadwal', 'shift', 'jam_masuk', 'jam_pulang'],
                ),
            ],
        ];
    }

    public function model()
    {
        return new Jadwal;
    }
}
