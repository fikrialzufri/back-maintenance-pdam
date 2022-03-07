<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Task;
use Illuminate\Http\Request;
use Str;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadmin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Task";
        $nama = request()->get('nama') ?: '';
        $route = 'task';
        $tasks = Task::where('name', 'like', '%' . $nama . '%')->paginate(20);
        return view('task.index', compact(
            'title',
            'tasks',
            'nama',
            'tasks',
            'route'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah Task";
        $action = route('task.store');
        $route = 'task';
        return view('task.create', compact(
            'title',
            'route',
            'action'
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
        ];

        $this->validate($request, [
            'name' => 'required|unique:tasks',
            'description' => 'required',
        ], $messages);

        $name = $request->input('name');
        $description = $request->input('description');
        $slug = Str::slug($name);

        $task = new Task;
        $task->name = $name;
        $task->description = $description;
        $task->slug = $slug;
        $task->save();

        $data = array(

            [
                'name'    => 'view ' . $name,
                'slug'    => 'view-' . $slug,
                'task_id' => $task->id
            ],
            [
                'name'    => 'Create ' . $name,
                'slug'    => 'create-' . $slug,
                'task_id' => $task->id
            ],
            [
                'name'    => 'Edit ' . $name,
                'slug'    => 'edit-' . $slug,
                'task_id' => $task->id
            ],
            [
                'name'    => 'Delete ' . $name,
                'slug'    => 'delete-' . $slug,
                'task_id' => $task->id
            ],
        );

        foreach ($data as $induk) {
            Permission::Create($induk);
        }
        return redirect()->route('task.index')->with('message', 'Task Berhasil Ditambahkan')->with('Class', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $title =  "Ubah Task " . $task->nama;
        $action = route('task.update', $task->id);
        $route = "task";
        return view('task.edit', compact(
            'action',
            'title',
            'route',
            'task'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
        ];

        $this->validate($request, [
            'name' => 'required|unique:tasks,name,' . $task->id,
            'description' => 'required',
        ], $messages);

        $name = $request->input('name');
        $description = $request->input('description');
        $slug = Str::slug($name);

        $task->name = $name;
        $task->description = $description;
        $task->slug = $slug;
        $task->save();
        $permission = Permission::where('task_id', $task->id)->get();

        foreach ($permission as $key => $v) {
            $p = Permission::find($v->id);
            $p->name = \explode(' ', $p->name)[0] . " " . $name;
            $p->slug = \explode('-', $p->slug)[0] . "-" . $slug;
            $p->save();
        }
        return redirect()->route('task.index')->with('message', 'Task Berhasil Ditambahkan')->with('Class', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->permissions()->delete();
        $task->delete();

        return redirect()->route('task.index')->with('message', 'Task Berhasil Dihapus')->with('Class', 'danger');
    }
}
