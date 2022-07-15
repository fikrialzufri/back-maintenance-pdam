<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     *
     * @return JsonResponse
     */
    public function sendResponse($result, $message, $code = 200, $total = 0)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'total' => $total,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param  array  $errorMessages
     * @param  int  $code
     *
     * @return JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'code' => $code
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function notification($modul_id, $slug, $title, $body, $modul, $from_user_id, $to_user_id)
    {
        $SERVER_API_KEY = env('FCM_KEY');

        $data = [
            "to" => "/topics/" . $to_user_id,
            "notification" => [
                "body" => $body,
                "title" => $title,
            ],
            "data" => [
                'slug' => $slug,
                'modul' => $modul,
            ],
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        $checknotifikasi = Notifikasi::where('modul_slug', $slug)->where('modul', $modul)->where('to_user_id', $to_user_id)->first();

        if ($checknotifikasi) {
            $checknotifikasi->delete();
        }

        $notification = new Notifikasi();
        $notification->modul_id = $modul_id;
        $notification->title = $title;
        $notification->body = $body;
        $notification->modul = $modul;
        $notification->modul_slug = $slug;
        $notification->status = 'belum';
        $notification->from_user_id = $from_user_id;
        $notification->to_user_id = $to_user_id;
        $notification->save();

        return response()->json(['send message successfully.' . $response]);
    }
}
