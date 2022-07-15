<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index()
    {
        // $user_id =  array();
        $query  = Notifikasi::where('to_user_id', auth()->user()->id);
        $notifikasi =  $query->limit(15)->orderBy('created_at', 'desc')->get();

        $count =  $query->count();

        // $data = [
        //     'modul_id' => $notifikasi->modul_id,
        //     'title' => $notifikasi->title,
        //     'body' => $notifikasi->body,
        //     'countNotif' => $notifikasi->count()
        // ];
        $result = [
            'data' => $notifikasi,
            'total' => $count,
        ];
        $message = 'success';
        // return response()->json($notifikasi);
        return $this->sendResponse($result, $message, 200, $count);
    }
    public function all()
    {
        $query  = Notifikasi::where('to_user_id', auth()->user()->id)->limit(15)->orderBy('created_at', 'desc');
        $notifikasi =  $query->paginate(15);
        $data = NotificationResource::collection($notifikasi)->response()->getData(true);
        $count =  $query->count();

        $result =  $data['data'];
        $message = 'success';
        return $this->sendResponse($result, $message, 200, $count);
    }

    public function detail($id)
    {
        try {
            $notifikasi  = Notifikasi::find($id);
            $result = $notifikasi;
            $notifikasi->delete();
            $message = 'success';
            return $this->sendResponse($result, $message, 200);
        } catch (\Throwable $th) {
            $message = 'success';
            return $this->sendResponse($result, $message, 200);
        }
    }
}
