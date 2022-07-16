<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Task;
use Illuminate\Http\Request;
use Str;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-roles', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-roles', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-roles', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-roles', ['only' => ['delete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title =  "Hak Akses";

        $query =  Role::query();
        $search = request()->search;

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%" . $search . "%");
            });
        }
        $dataRole =  $query->where('slug', '!=', 'superadmin')->orderBy('slug')->paginate(15);
        $route = 'role';
        return view('role.index', compact(
            "title",
            "search",
            "route",
            "dataRole"
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  "Tambah Hak Akses";
        $action = route('role.store');
        $tasks = Task::orderBy('slug')->get();
        return view('role.create', compact(
            "title",
            "tasks",
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
            'name' => 'required|unique:roles',
        ], $messages);

        $role = new Role();
        $role->slug = Str::slug($request->name);
        $role->name = $request->name;
        $role->save();
        $role->permissions()->attach($request->izin_akses);
        return redirect()->route('role.index')->with('message', 'Hak Akses berhasil ditambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $title =  "Ubah Hak Akses " . $role->nama;
        $action = route('role.update', $role->id);
        $tasks = Task::orderBy('slug')->get();
        $izin = $role->permissions->pluck('name')->toArray();
        return view('role.edit', compact(
            'action',
            'tasks',
            'title',
            'izin',
            'role'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
            'same' => 'Password dan konfirmasi password harus sama',
        ];

        $this->validate(request(), [
            'name' => 'required|unique:roles,name,' . $role->id,
        ], $messages);


        $role->slug = Str::slug($request->name);
        $role->name = $request->name;
        $role->save();
        $role->permissions()->sync($request->izin_akses);
        return redirect()->route('role.index')->with('message', 'Hak Akses berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        $role->permissions()->detach();
        return redirect()->route('role.index')->with('message', 'Hak Akses berhasil dihapus');
    }
}
