<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'ファッション',
            'レディース',
            'メンズ',
            'コスメ・香水・美容',
            '本・音楽・ゲーム',
            'おもちゃ・ホビー・グッズ',
            '家電・スマホ・カメラ',
            'スポーツ・レジャー',
            'ハンドメイド',
            'チケット',
            '自動車・オートバイ',
            'その他'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
