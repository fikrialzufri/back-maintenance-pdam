<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $this->validate($request, [
            'username'    => 'required',
            'password' => 'required',
        ]);

        $login_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $request->merge([
            $login_type => $request->input('username')
        ]);

        $dataRole = [];
        $dataPermission = [];


        if (Auth::attempt($request->only($login_type, 'password'))) {
            $user =  Auth::user();
            $token = $user->createToken("access_token")
                ->plainTextToken;
            $role = $user->role;
            foreach ($role as $key => $value) {
                $dataRole[$key] = $value->slug;
                foreach ($value->permissions as $index => $item) {
                    $dataPermission[$index] = $item->slug;
                }
            }
            $data = [
                "id" => $user->id,
                "username" => $user->username,
                "name" => $user->name,
                "email" => $user->email,
            ];

            $result = [
                'user' => $data,
                'role' => $dataRole,
                'token' => $token
            ];

            $message = 'user dan password betul';
            return $this->sendResponse($result, $message, 200);
        } else {
            $error = 'user dan password salah';
            $errorMessages = "";
            return $this->sendError($error, $errorMessages, 401);
        }
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        $result = '';

        $message = 'anda berhasil keluar';

        return $this->sendResponse($result, $message, 200);
    }

    public function me()
    {
        $user = Auth::user()->id;
        $role = Auth::user()->role;

        $dataRole = [];
        $dataPermission = [];

        foreach ($role as $key => $value) {
            $dataRole[$key] = $value->slug;
            foreach ($value->permissions as $index => $item) {
                $dataPermission[$index] = $item->slug;
            }
        }

        $result = [
            'user' => $user,
            'role' => $dataRole,
            'permissions' => $dataPermission,
        ];

        $message = 'profile data';
        return $this->sendResponse($result, $message, 200);
    }

    public function refresh()
    {
        $user =  Auth::user();
        $user->tokens()->delete();

        $dataRole = [];
        $dataPermission = [];

        foreach ($user->role as $key => $value) {
            $dataRole[$key] = $value->slug;
            foreach ($value->permissions as $index => $item) {
                $dataPermission[$index] = $item->slug;
            }
        }
        $token = $user->createToken("access_token")
            ->plainTextToken;
        $result = [
            'user' => $user,
            'role' => $dataRole,
            'token' => $token
        ];

        $message = 'resfresh token';
        return $this->sendResponse($result, $message, 200);
    }

    public function user()
    {
        return Auth::user()->with('role')->with('permissions');
    }
}
