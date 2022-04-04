<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-user', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-user', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-user', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function index()
    {
        $title =  "Pengguna";
        $dataUser = User::where('slug', '!=', 'superadmin')->paginate(15);
        return view('user.index', ["title" => $title, "dataUser" => $dataUser]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  "Tambah Pengguna";
        $action = route('user.store');
        $roles = Role::where('slug', '!=', 'superadmin')->get();
        return view('user.create', compact(
            "title",
            "roles",
            "action"
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
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
            'same' => 'Password dan konfirmasi password harus sama',
        ];

        $this->validate(request(), [
            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'passwordConfrim' => 'required|same:password|min:6',
        ], $messages);

        // return $request;
        $roles = $request->rule;

        $pass = bcrypt(request()->input('password'));
        $name = request()->input('name');

        $user = new User;
        $user->name = $name;
        $user->username = $request->username;
        $user->slug = Str::slug($request->username);
        $user->email = request()->input('email');
        $user->password = $pass;
        $user->save();

        $user->role()->attach($roles);
        return redirect()->route('user.index')->with('message', 'User berhasil ditambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $title =  "Ubah Pengguna " . $user->nama;
        $action = route('user.update', $user->id);
        $roles = Role::where('slug', '!=', 'superadmin')->get();
        return view('user.edit', compact(
            'action',
            'title',
            'roles',
            'user'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
            'same' => ':attribute password dan confrim password harus sama',
        ];

        $this->validate(request(), [
            'name' => 'required|unique:users,name,' . $id,
            'email' => 'required|unique:users,email,' . $id
        ], $messages);

        $user = User::find($id);
        $name = request()->input('name');
        if (request()->input('passwordNew')) {
            # code...
            $pass = bcrypt(request()->input('passwordNew'));
            $user->password = $pass;
            $this->validate(request(), [
                'name' => 'required|unique:users,name,' . $id,
                'email' => 'required|unique:users,email,' . $id,
                'passwordNew' => 'required|min:6',
                'passwordConfrim' => 'required|same:passwordNew|min:6',
            ], $messages);
        }

        $user->name = $name;
        $user->username = $request->username;
        $user->email = request()->input('email');

        $user->save();

        $roles = $request->rule;

        $permission = Role::find($roles)->permissions()->pluck('id');
        $user->role()->sync($roles);
        return redirect()->route('user.index')->with('message', 'User berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        // $karyawan = Karyawan::find($user->karyawan_id);
        // if ($karyawan) {
        //     $karyawan->delete();
        // }
        return redirect()->route('user.index')->with('message', 'Pengguna berhasil dihapus');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ubah()
    {
        $user = User::find(Auth::user()->id);
        $title =  "Edit Profile " . $user->name;
        $action = route('user.simpan');
        return view('user.ubah', compact('action', 'title', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function simpan(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
            'min' => ':attribute harus lebih dari :min ',
            'same' => ':attribute password dan confrim password harus sama',
        ];

        $this->validate(request(), [
            'name' => 'required|unique:users,name,' . $user->id,
            'email' => 'required|unique:users,email,' . $user->id,
            'passwordNew' => 'required|min:6',
            'passwordConfrim' => 'required|same:passwordNew|min:6',
        ], $messages);
        $name = request()->input('name');
        if (request()->input('passwordNew')) {
            # code...
            $pass = bcrypt(request()->input('passwordNew'));
            $user->password = $pass;
            $this->validate(request(), [
                'email' => 'required|unique:users,email,' . $user->id,
                'passwordNew' => 'required|min:6',
                'passwordConfrim' => 'required|same:passwordNew|min:6',
            ], $messages);
        }

        $user->name = $name;
        $user->email = request()->input('email');
        $user->update();

        return redirect()->route('home')->with('message', 'User berhasil diubah');
    }

    public function token(Request $request)
    {
        auth()->user()->update(['device_token' => $request->token]);
        return response()->json(['token saved successfully.']);
    }

    public function notification(Request $request)
    {
        // $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        // by id
        $user = User::whereNotNull('device_token')->first();

        $SERVER_API_KEY = 'AAAAJ-D2GlQ:APA91bFzJSD-dpuhbAu89iqZUm4x7b3e5PDZ6W5BX7zvEmEaPFQeY6YiiPgaT4DHEOAIjoTddvOmp1BVe-ZXUi9XGO4CFrY68IFgYxtnFD82QmqXQaw7Rzqu4spDRLAdT6CYjgGtPjGq';
        // to token
        // $data = [
        //     "registration_ids" => $firebaseToken,
        //     "notification" => [
        //         "title" => "TEST BARU LAGI",
        //         "body" => "MAHDI HABUK",
        //     ]
        // ];
        //         {
        //    "to":"/topics/all",
        //    "data":
        //    {
        //       "title":"Your title",
        //       "message":"Your message"
        //       "image-url":"your_image_url"
        //    }
        // }
        $data = [
            "to" => "/topics/" . $user->id,
            "data" => [
                "title" => "TEST BARU LAGI",
                "body" => "MAHDI HABUK",
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        // return $data;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        return response()->json(['send message successfully.' . $response]);
    }
}
