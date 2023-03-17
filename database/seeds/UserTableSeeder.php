<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'システム管理者',
            'email' => 'root@mail',
            'password' => Hash::make('rootadmin09'),
            'tel' => '000-0000-0000',
            'role' => config('const.Roles.ADMIN'),
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => '狩野 管理者',
            'email' => 'karino@email.com',
            'password' => Hash::make('karino'),
            'tel' => '666-7777-8888',
            'role' => config('const.Roles.WORKER'),
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => '狩野次 管理者',
            'email' => 'karino2@email.com',
            'password' => Hash::make('karino'),
            'tel' => '666-7777-8888',
            'role' => config('const.Roles.WORKER'),
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => '田飯 職員',
            'email' => 'tameshi@email.com',
            'password' => Hash::make('tameshi'),
            'tel' => '333-4444-5555',
            'role' => config('const.Roles.WORKER'),
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => '山降 親一',
            'email' => 'sample@email.com',
            'password' => Hash::make('sample'),
            'tel' => '000-1111-2222',
            'role' => config('const.Roles.PARENT'),
            'created_at' => new DateTime('now'),
            'updated_at' => new DateTime('now')
        ];
        DB::table('users')->insert($param);
    }
}
