<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Hash;

class ConfigurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configurations')->insert([
            [
                'id' => 1,
                'tax_rate' => 10,
                'fee_fix' => 5500,
                'fee_percent' => 20,
            ]
        ]);
    }
}
