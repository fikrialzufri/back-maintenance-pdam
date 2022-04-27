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

        $RekananRole = new Role();
        $RekananRole->name = 'Rekanan';
        $RekananRole->save();

        $DirekturRole = new Role();
        $DirekturRole->name = 'Direktur Teknik';
        $DirekturRole->save();

        $ManagerDistribusiRole = new Role();
        $ManagerDistribusiRole->name = 'Manager Distribusi';
        $ManagerDistribusiRole->save();

        $ManagerPengawasRole = new Role();
        $ManagerPengawasRole->name = 'Manager Pengawas';
        $ManagerPengawasRole->save();

        $AdminDistribusiRole = new Role();
        $AdminDistribusiRole->name = 'Admin Distribusi';
        $AdminDistribusiRole->save();

        $AdminAsistenRole = new Role();
        $AdminAsistenRole->name = 'Admin Asisten Manager';
        $AdminAsistenRole->save();

        $AsistenRole = new Role();
        $AsistenRole->name = ' Asisten Manager Distribusi';
        $AsistenRole->save();

        $HumasRole = new Role();
        $HumasRole->name = ' Humas';
        $HumasRole->save();

        $KeuanganRole = new Role();
        $KeuanganRole->name = ' Keuangan';
        $KeuanganRole->save();

        $StaffPengawasRole = new Role();
        $StaffPengawasRole->name = 'Staf Pengawas';
        $StaffPengawasRole->save();

        $superadmin = Role::where('slug', 'superadmin')->first();

        $superadminUser = new User();
        $superadminUser->name = 'Superadmin';
        $superadminUser->username = 'Superadmin';
        $superadminUser->email = 'Superadmin@admin.com';
        $superadminUser->password = bcrypt('secret');
        // $superadminUser->icon = 'default-icon.png';
        $superadminUser->save();

        $superadminUser->role()->attach($superadmin);

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

        $taskWilayah = new Task();
        $taskWilayah->name = 'Wilayah';
        $taskWilayah->description = 'Manajemen Wilayah';
        $taskWilayah->save();

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

        $taskRekanan = new Task();
        $taskRekanan->name = 'Rekanan';
        $taskRekanan->description = 'Manajemen Rekanan';
        $taskRekanan->save();

        $taskAduan = new Task();
        $taskAduan->name = 'Aduan';
        $taskAduan->description = 'Manajemen Aduan';
        $taskAduan->save();

        $taskSetting = new Task();
        $taskSetting->name = 'Setting';
        $taskSetting->description = 'Manajemen Setting';
        $taskSetting->save();

        $taskPenunjukanPekerjaan = new Task();
        $taskPenunjukanPekerjaan->name = 'Penunjukan Pekerjaan';
        $taskPenunjukanPekerjaan->description = 'Manajemen Penunjukan Pekerjaan';
        $taskPenunjukanPekerjaan->save();

        $taskPelaksanaanPekerjaan = new Task();
        $taskPelaksanaanPekerjaan->name = 'Pelaksanaan Pekerjaan';
        $taskPelaksanaanPekerjaan->description = 'Manajemen Pelaksanaan Pekerjaan';
        $taskPelaksanaanPekerjaan->save();

        $tagihan = new Task();
        $tagihan->name = 'Tagihan';
        $tagihan->description = 'Manajemen Tagihan';
        $tagihan->save();

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
            }
        }
    }
}
