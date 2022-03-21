<?php

namespace App\Http\Controllers;

use App\Models\Roster;
use Illuminate\Http\Request;
use App\Traits\CrudTrait;
use App\Imports\RosterImport;
use Excel;
use Session;

class RosterController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'roster';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name' => 'tanggal',
                'alias' => 'Tanggal',
                'input' => 'date',
            ],
            [
                'name' => 'name_shift',
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
                'name' => 'tanggal',
                'input' => 'text',
                'alias' => 'Tanggal',
                'value' => null
            ],
        ];
    }

    public function configForm()
    {
        return [
            [
                'name'  => 'tanggal',
                'input' => 'date',
                'alias' => 'Tanggal',
                'validasi'  => ['required', 'date'],
            ],
            [
                'name'    => 'shift_id',
                'input'    => 'combo',
                'alias'    => 'Shift',
                'value' => $this->combobox('shift'),
                'validasi'    => ['required'],
            ],
            [
                'name' => 'jam_masuk',
                'input' => 'time',
                'alias' => 'Jam Masuk',
                'validasi' => ['required'],
            ],
            [
                'name' => 'jam_pulang',
                'input' => 'time',
                'alias' => 'Jam Pulang',
                'validasi' => ['required'],
            ],
        ];
    }

    public function model()
    {
        return new Roster;
    }

    public function import()
    {
        $title = "Roster Import";
        $action = "roster.import_process";
        return view('roster.import', compact('title', 'action'));
    }

    public function import_process(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Excel::import(new RosterImport, $file);

            Session::flash('message', 'Berhasil Import Roster');
            return redirect(route('roster.index'));
        }

        Session::flash('message', 'Gagal Import Roster');
        return redirect(route('roster.index'));
    }
}
