<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AreaSeeder extends Seeder
{

    public function run()
    {
        $prefecture_path = 'database/sql/prefectures.sql';

        DB::unprepared(file_get_contents($prefecture_path));
        $this->command->info('Prefecture table seeded!');

    }
}
