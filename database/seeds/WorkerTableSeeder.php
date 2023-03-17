<?php

use Illuminate\Database\Seeder;

class WorkerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'facilitie_id' => 1,
            'user_id' => 2,
            'name' => '狩野 管理者',
            'permit' => config('const.WorkerPermit.FACILITIE_ADMIN'),
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('workers')->insert($param);

        $param = [
            'facilitie_id' => 1,
            'user_id' => 4,
            'name' => '山降 職員',
            'permit' => config('const.WorkerPermit.WORKER'),
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('workers')->insert($param);

        $param = [
            'facilitie_id' => 2,
            'user_id' => 3,
            'name' => '狩野次 管理者',
            'permit' => config('const.WorkerPermit.FACILITIE_ADMIN'),
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('workers')->insert($param);

        $param = [
            'facilitie_id' => 2,
            'user_id' => 4,
            'name' => '山降 職員',
            'permit' => config('const.WorkerPermit.WORKER'),
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('workers')->insert($param);
    }
}
