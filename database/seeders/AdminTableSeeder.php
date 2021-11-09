<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            [
                'id' => 1,
                'name' => 'Shark管理者',
                'login_id' => 'admin@shark.com',
                'password' =>Hash::make('12345678'),
            ]
        ]);
    }
}
