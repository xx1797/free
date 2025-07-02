<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_all_required_information_is_displayed()
    {
        $user = User::factory()->create();
        $category1 = Category::factory()->create(['name' => 'カテゴリ1']);
        $category2 = Category::factory()->create(['name' => 'カテゴリ2']);
        $condition = Condition::factory()->create(['name' => '良好']);
        
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト説明',
            'condition_id' => $condition->id
        ]);

        $product->categories()->attach([$category1->id, $category2->id]);

        // いいねとコメントを追加
        Like::factory()->create(['product_id' => $product->id]);
        Comment::factory()->create([
            'product_id' => $product->id,
            'comment' => 'テストコメント'
        ]);

        $response = $this->get("/item/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('1,000');
        $response->assertSee('テスト説明');
        $response->assertSee('カテゴリ1');
        $response->assertSee('カテゴリ2');
        $response->assertSee('良好');
        $response->assertSee('テストコメント');
    }

    public function test_multiple_categories_are_displayed()
    {
        $user = User::factory()->create();
        $category1 = Category::factory()->create(['name' => 'カテゴリ1']);
        $category2 = Category::factory()->create(['name' => 'カテゴリ2']);
        $category3 = Category::factory()->create(['name' => 'カテゴリ3']);
        
        $product = Product::factory()->create(['user_id' => $user->id]);
        $product->categories()->attach([$category1->id, $category2->id, $category3->id]);

        $response = $this->get("/item/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('カテゴリ1');
        $response->assertSee('カテゴリ2');
        $response->assertSee('カテゴリ3');
    }
}
