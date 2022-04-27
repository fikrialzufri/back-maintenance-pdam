<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function __construct()
    {
        $this->route = 'tagihan';
        $this->tambah = 'false';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function index()
    {
        $title = 'Setting';
        $route = 'setting';

        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
            $setting->galian = 0;
            $setting->save();
        }
        $action = route('setting.store');
        return view('setting.show', compact(
            'title',
            'route',
            'action',
            'setting',
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $galian = str_replace(".", "", $request->galian);
        $setting = Setting::first();
        $setting->galian = $galian;
        $setting->save();

        return redirect()->route('setting.index')->with('message', 'Setting Berhasil Diubah')->with('Class', 'success');
    }
}
