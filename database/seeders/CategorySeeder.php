<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('categories')->truncate();
    	
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => '食品',
                'parent_id' => 0,
                'sequence' => 1,
                'layer' => 0
            ],
            [
                'id' => 2,
                'name' => 'ケーキ',
                'parent_id' => 1,
                'sequence' => 1,
                'layer' => 1
            ],
            [
                'id' => 3,
                'name' => '惣菜・お弁当',
                'parent_id' => 1,
                'sequence' => 2,
                'layer' => 1
            ],
            [
                'id' => 4,
                'name' => 'パン',
                'parent_id' => 1,
                'sequence' => 3,
                'layer' => 1
            ],
            [
                'id' => 5,
                'name' => '飲食店',
                'parent_id' => 0,
                'sequence' => 2,
                'layer' => 0
            ],
            [
                'id' => 6,
                'name' => '居酒屋',
                'parent_id' => 5,
                'sequence' => 1,
                'layer' => 1
            ],
            [
                'id' => 7,
                'name' => 'レストラン',
                'parent_id' => 5,
                'sequence' => 2,
                'layer' => 1
            ],
            [
                'id' => 8,
                'name' => '和食',
                'parent_id' => 7,
                'sequence' => 1,
                'layer' => 2
            ],
            [
                'id' => 9,
                'name' => '中華',
                'parent_id' => 7,
                'sequence' => 2,
                'layer' => 2
            ],
            [
                'id' => 10,
                'name' => '洋食',
                'parent_id' => 7,
                'sequence' => 3,
                'layer' => 2
            ],
            [
                'id' => 11,
                'name' => 'その他',
                'parent_id' => 7,
                'sequence' => 4,
                'layer' => 2
            ],
            [
                'id' => 12,
                'name' => 'カフェ',
                'parent_id' => 5,
                'sequence' => 3,
                'layer' => 1
            ],
            [
                'id' => 13,
                'name' => 'バー',
                'parent_id' => 5,
                'sequence' => 4,
                'layer' => 1
            ],
            [
                'id' => 14,
                'name' => 'その他',
                'parent_id' => 5,
                'sequence' => 5,
                'layer' => 1
            ],
            [
                'id' => 15,
                'name' => 'ビューティ&リラクゼーション',
                'parent_id' => 0,
                'sequence' => 3,
                'layer' => 0
            ],
            [
                'id' => 16,
                'name' => 'ヘア',
                'parent_id' => 15,
                'sequence' => 1,
                'layer' => 1
            ],
            [
                'id' => 17,
                'name' => 'ネイル',
                'parent_id' => 15,
                'sequence' => 2,
                'layer' => 1
            ],
            [
                'id' => 18,
                'name' => 'まつげ',
                'parent_id' => 15,
                'sequence' => 3,
                'layer' => 1
            ],
            [
                'id' => 19,
                'name' => 'リラクゼーション',
                'parent_id' => 15,
                'sequence' => 4,
                'layer' => 1
            ],
            [
                'id' => 20,
                'name' => 'エステ',
                'parent_id' => 15,
                'sequence' => 5,
                'layer' => 1
            ],
            [
                'id' => 21,
                'name' => '美容クリニック',
                'parent_id' => 15,
                'sequence' => 6,
                'layer' => 1
            ],
            [
                'id' => 22,
                'name' => '整体',
                'parent_id' => 15,
                'sequence' => 7,
                'layer' => 1
            ],
            [
                'id' => 23,
                'name' => '宿泊',
                'parent_id' => 0,
                'sequence' => 4,
                'layer' => 0
            ],
            [
                'id' => 24,
                'name' => 'アパレル・ファッション',
                'parent_id' => 0,
                'sequence' => 5,
                'layer' => 0
            ],
            [
                'id' => 25,
                'name' => 'メンズ',
                'parent_id' => 24,
                'sequence' => 1,
                'layer' => 1
            ],
            [
                'id' => 26,
                'name' => 'カジュアル',
                'parent_id' => 25,
                'sequence' => 1,
                'layer' => 2
            ],
            [
                'id' => 27,
                'name' => 'ビジネス',
                'parent_id' => 25,
                'sequence' => 2,
                'layer' => 2
            ],
            [
                'id' => 28,
                'name' => 'アウトドア',
                'parent_id' => 25,
                'sequence' => 3,
                'layer' => 2
            ],
            [
                'id' => 29,
                'name' => 'シューズ',
                'parent_id' => 25,
                'sequence' => 4,
                'layer' => 2
            ],
            [
                'id' => 30,
                'name' => 'インナーファッション',
                'parent_id' => 25,
                'sequence' => 5,
                'layer' => 2
            ],
            [
                'id' => 31,
                'name' => '雑貨・アクセサリー',
                'parent_id' => 25,
                'sequence' => 6,
                'layer' => 2
            ],
            [
                'id' => 32,
                'name' => 'レディス',
                'parent_id' => 24,
                'sequence' => 2,
                'layer' => 1
            ],
            [
                'id' => 33,
                'name' => 'カジュアル',
                'parent_id' => 32,
                'sequence' => 1,
                'layer' => 2
            ],
            [
                'id' => 34,
                'name' => 'ビジネス',
                'parent_id' => 32,
                'sequence' => 2,
                'layer' => 2
            ],
            [
                'id' => 35,
                'name' => 'アウトドア',
                'parent_id' => 32,
                'sequence' => 3,
                'layer' => 2
            ],
            [
                'id' => 36,
                'name' => 'シューズ',
                'parent_id' => 32,
                'sequence' => 4,
                'layer' => 2
            ],
            [
                'id' => 37,
                'name' => 'インナーファッション',
                'parent_id' => 32,
                'sequence' => 5,
                'layer' => 2
            ],
            [
                'id' => 38,
                'name' => '雑貨・アクセサリー',
                'parent_id' => 32,
                'sequence' => 6,
                'layer' => 2
            ],
            [
                'id' => 39,
                'name' => 'メンズ&レディス',
                'parent_id' => 24,
                'sequence' => 3,
                'layer' => 1
            ],
            [
                'id' => 40,
                'name' => 'カジュアル',
                'parent_id' => 39,
                'sequence' => 1,
                'layer' => 2
            ],
            [
                'id' => 41,
                'name' => 'ビジネス',
                'parent_id' => 39,
                'sequence' => 2,
                'layer' => 2
            ],
            [
                'id' => 42,
                'name' => 'アウトドア',
                'parent_id' => 39,
                'sequence' => 3,
                'layer' => 2
            ],
            [
                'id' => 43,
                'name' => 'シューズ',
                'parent_id' => 39,
                'sequence' => 4,
                'layer' => 2
            ],
            [
                'id' => 44,
                'name' => 'インナーファッション',
                'parent_id' => 39,
                'sequence' => 5,
                'layer' => 2
            ],
            [
                'id' => 45,
                'name' => '雑貨・アクセサリー',
                'parent_id' => 39,
                'sequence' => 6,
                'layer' => 2
            ],
            [
                'id' => 46,
                'name' => 'キッズ',
                'parent_id' => 24,
                'sequence' => 4,
                'layer' => 1
            ],
            [
                'id' => 47,
                'name' => 'ジム・フィットネス',
                'parent_id' => 0,
                'sequence' => 6,
                'layer' => 0
            ],
 
        ]);
    }
}
