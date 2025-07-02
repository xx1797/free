<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $categories = Category::all();
        $conditions = Condition::all();

        $products = [
            [
                'name' => '腕時計',
                'brand' => 'CASIO',
                'description' => 'スタイリッシュなデザインのメンズ腕時計です。日常使いにもビジネスシーンにも活躍します。',
                'price' => 15000,
            ],
            [
                'name' => 'HDD',
                'brand' => 'Western Digital',
                'description' => '高速で信頼性の高い外付けハードディスクです。大容量データの保存に最適です。',
                'price' => 8000,
            ],
            [
                'name' => '玉ねぎ3束',
                'brand' => null,
                'description' => '新鮮な玉ねぎを3束セットでお届けします。料理の基本食材として重宝します。',
                'price' => 500,
            ],
            [
                'name' => '革靴',
                'brand' => 'REGAL',
                'description' => '本革を使用した高品質なビジネスシューズです。履き心地も抜群です。',
                'price' => 25000,
            ],
            [
                'name' => 'ノートPC',
                'brand' => 'DELL',
                'description' => '軽量で持ち運びやすいノートパソコンです。テレワークや学習に最適です。',
                'price' => 45000,
            ],
            [
                'name' => 'マイク',
                'brand' => 'SHURE',
                'description' => 'プロ仕様の高音質マイクロフォンです。配信や録音に最適です。',
                'price' => 12000,
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => 'PORTER',
                'description' => '機能性とデザイン性を兼ね備えたショルダーバッグです。普段使いに便利です。',
                'price' => 18000,
            ],
            [
                'name' => 'タンブラー',
                'brand' => 'THERMOS',
                'description' => '保温・保冷効果の高いステンレス製タンブラーです。オフィスでの使用に最適です。',
                'price' => 3000,
            ],
            [
                'name' => 'コーヒーミル',
                'brand' => 'Kalita',
                'description' => '手動式のコーヒーミルです。挽きたての香り高いコーヒーをお楽しみいただけます。',
                'price' => 7500,
            ],
            [
                'name' => 'メイクセット',
                'brand' => 'CHANEL',
                'description' => '人気ブランドのメイクアップセットです。プレゼントにも最適です。',
                'price' => 35000,
            ],
        ];

        foreach ($products as $index => $productData) {
            $product = Product::create([
                'user_id' => $users->random()->id,
                'name' => $productData['name'],
                'brand' => $productData['brand'],
                'description' => $productData['description'],
                'condition_id' => $conditions->random()->id,
                'price' => $productData['price'],
                'is_sold' => $index % 4 === 0, // 4つに1つを売り切れにする
            ]);

            // ランダムにカテゴリを1-3個割り当て
            $randomCategories = $categories->random(rand(1, 3));
            $product->categories()->attach($randomCategories->pluck('id'));
        }
    }
}
