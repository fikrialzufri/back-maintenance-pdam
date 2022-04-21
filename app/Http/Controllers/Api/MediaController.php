<?php

namespace  App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use Storage;
use Str;

class MediaController extends Controller
{
    public function index()
    {
        try {
            $message = 'Data Media';
            $query = $this->model();
            $result = $query->get();
            if (count($result) == 0) {
                $message = 'Data Media Belum Ada';
            }
            return $this->sendResponse($result, $message, 200);
        } catch (\Throwable $th) {
            $message = 'Detail Media';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }

    public function store(Request $request)
    {
        $image = $request->image;
        $slug = $request->slug;
        $modul = $request->modul;
        $modul_id = $request->id;

        $user_id = auth()->user()->id;

        try {
            if (preg_match('/^data:image\/(\w+);base64,/', $image)) {

                $imagebase64 = substr($image, strpos($image, ',') + 1);
                $imagebase64 = base64_decode($imagebase64);
                $imageName = $slug . Str::random(5) . '.png';
                Storage::disk('public')->put('proses/' . $imageName, $imagebase64);

                $media = new Media();
                $media->nama = $imageName . '-' . $modul;
                $media->modul = $modul;
                $media->file = $imageName;
                $media->modul_id = $modul_id;
                $media->user_id = $user_id;
                $media->save();
                $message = 'Berhasil mengirim foto';
                return $this->sendResponse($media, $message, 200);
            } else {
                $message = 'Gagal Mengirim foto';
                $error = "type file salah";
                $response = [
                    'success' => false,
                    'message' => $message,
                    'code' => '400'
                ];
                return $this->sendError($response, $error, 400);
            }
        } catch (\Throwable $th) {
            $message = 'Gagal Mengirim foto';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        try {
            $data = Media::find($id);
            Storage::disk('public')->delete('proses/' . $data->file);
            $data->delete();
            $message = 'Data Media dihapus';
            return $this->sendResponse($data, $message, 200);
        } catch (\Throwable $th) {
            $message = 'Data Tidak bisa dihapus';
            $response = [
                'success' => false,
                'message' => $message,
                'code' => '404'
            ];
            return $this->sendError($response, $th, 404);
        }
        return redirect()->route('user.index')->with('message', 'Pengguna berhasil dihapus');
    }

    public function model()
    {
        return new Media();
    }
}
