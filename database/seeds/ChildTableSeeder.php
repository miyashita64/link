<?php

use Illuminate\Database\Seeder;

class ChildTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '一子',
            'parent_id' => 5,
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('children')->insert($param);

        $param = [
            'name' => '信二',
            'parent_id' => 5,
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('children')->insert($param);
    }
}
