<?php

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
        $this->call(UserTableSeeder::class);
        $this->call(FacilitieTableSeeder::class);
        $this->call(WorkerTableSeeder::class);
        $this->call(ChildTableSeeder::class);
        $this->call(ClientTableSeeder::class);
        /*
        $this->call(DiarieTableSeeder::class);
        $this->call(DiarieItemsTableSeeder::class);
        */
        //$this->call(miraiSeeder::class);
    }
}
