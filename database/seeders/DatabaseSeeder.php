<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeedeer::class,
            SatuanSeeder::class,
            PerusahaanSeeder::class,
            KategoriJenisSeeder::class,
            // GalianSeeder::class,
            JenisAduanSeeder::class
        ]);
    }
}
