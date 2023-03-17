<?php

use Illuminate\Database\Seeder;

class FacilitieTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '施設１ 元気',
            'office_number' => '1',
            'admin_id' => 2,
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('facilities')->insert($param);

        $param = [
            'name' => '施設２ 睡眠',
            'office_number' => '2',
            'admin_id' => 3,
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('facilities')->insert($param);
    }
}
