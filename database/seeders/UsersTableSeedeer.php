<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class UsersTableSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = new Role();
        $superadmin->name = 'Superadmin';
        $superadmin->save();

        $adminRole = new Role();
        $adminRole->name = 'Admin';
        $adminRole->save();


        $superadmin = Role::where('slug', 'superadmin')->first();

        $superadminUser = new User();
        $superadminUser->name = 'Superadmin';
        $superadminUser->username = 'Superadmin';
        $superadminUser->email = 'Superadmin@admin.com';
        $superadminUser->password = bcrypt('secret');
        // $superadminUser->icon = 'default-icon.png';
        $superadminUser->save();

        $superadminUser->role()->attach($superadmin);

        $admin = new User();
        $admin->name = 'admin';
        $admin->username = 'admin';
        $admin->email = 'admin@admin.com';
        $admin->password = bcrypt('secret');
        // $admin->icon = 'default-icon.png';
        $admin->save();
        $admin->role()->attach($adminRole);

        $taskUser = new Task();
        $taskUser->name = 'User';
        $taskUser->description = 'Manajemen User';
        $taskUser->save();

        $taskRole = new Task();
        $taskRole->name = 'Roles';
        $taskRole->description = 'Manajemen Hak Akses ';
        $taskRole->save();

        $taskSatuan = new Task();
        $taskSatuan->name = 'Satuan';
        $taskSatuan->description = 'Manajemen Satuan';
        $taskSatuan->save();

        $taskJenis = new Task();
        $taskJenis->name = 'Jenis';
        $taskJenis->description = 'Manajemen Jenis';
        $taskJenis->save();

        $taskKategori = new Task();
        $taskKategori->name = 'Kategori';
        $taskKategori->description = 'Manajemen Kategori';
        $taskKategori->save();

        $taskItem = new Task();
        $taskItem->name = 'Item';
        $taskItem->description = 'Manajemen Item';
        $taskItem->save();

        $taskDepartemen = new Task();
        $taskDepartemen->name = 'Departemen';
        $taskDepartemen->description = 'Manajemen Departemen';
        $taskDepartemen->save();

        $taskDivisi = new Task();
        $taskDivisi->name = 'Divisi';
        $taskDivisi->description = 'Manajemen Divisi';
        $taskDivisi->save();

        $taskJabatan = new Task();
        $taskJabatan->name = 'Jabatan';
        $taskJabatan->description = 'Manajemen Jabatan';
        $taskJabatan->save();

        $taskKaryawan = new Task();
        $taskKaryawan->name = 'Karyawan';
        $taskKaryawan->description = 'Manajemen Karyawan';
        $taskKaryawan->save();

        $tasks = Task::all();

        foreach ($tasks as $task) {
            $name = $task->name;
            $data = array(

                [
                    'name'    => 'View ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Create ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Edit ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Delete ' . $name,
                    'task_id' => $task->id
                ],
            );

            foreach ($data as $induk) {
                $Permission = Permission::Create($induk);
                $adminRole->permissions()->attach($Permission->id);
            }
        }
    }
}
