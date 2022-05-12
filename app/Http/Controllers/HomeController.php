<?php

namespace App\Http\Controllers;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title =  "Dashboard";

        $anggota = 0;
        $pegawai = 0;
        $rapat = 0;
        $jenisRapat = 0;

        return view('home.index', compact(
            'title',
            'pegawai',
            'anggota',
            'rapat',
            'jenisRapat'
        ));
    }
}
