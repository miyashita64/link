<?php

use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '山降 一子',
            'birthday' => new DateTime('2010/5/1'),
            'benefic_num' => '2',
            'school_name' => 'A小学校',
            'child_id' => 1,
            'facilitie_id' => 1,
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('clients')->insert($param);

        $param = [
            'name' => '山降 信二',
            'birthday' => new DateTime('2013/5/1'),
            'benefic_num' => '6',
            'school_name' => 'A小学校',
            'child_id' => 2,
            'facilitie_id' => 1,
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('clients')->insert($param);

        $param = [
            'name' => '山降 一子',
            'birthday' => new DateTime('2010/5/1'),
            'benefic_num' => '2',
            'school_name' => 'A小学校',
            'child_id' => 1,
            'facilitie_id' => 2,
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('clients')->insert($param);
    }
}
