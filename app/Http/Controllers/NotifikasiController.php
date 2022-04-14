<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index()
    {
        // $user_id = array();
        $notifikasi = Notifikasi::where('to_user_id', auth()->user()->id)->where('status', 'belum')->get();

        // $data = [
        //     'modul_id' => $notifikasi->modul_id,
        //     'title' => $notifikasi->title,
        //     'body' => $notifikasi->body,
        //     'countNotif' => $notifikasi->count()
        // ];
        // $result = [
        //     'data' => $data
        // ];
        $message = 'success';
        // return response()->json($notifikasi);
        return $this->sendResponse($notifikasi, $message, 200);
    }
}
