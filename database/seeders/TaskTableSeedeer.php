<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskTableSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taskPenunjukanPekerjaan = Task::where('name', "Penunjukan Pekerjaan")->first();

        $Permission = Permission::updateOrCreate(
            [
                'name'    => 'Pilih Rekanan',
                'task_id' => $taskPenunjukanPekerjaan->id
            ],
        );
    }
}
