<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_purchase_completes_when_purchase_button_is_clicked()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'price' => 1000
        ]);

        $response = $this->actingAs($user)->post("/purchase/{$product->id}", [
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル'
        ]);

        $response->assertRedirect('/mypage');
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'total_amount' => 1000,
        ]);
    }

    public function test_purchased_product_shows_sold_in_product_list()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false
        ]);

        // 購入処理
        Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'total_amount' => $product->price,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
        ]);

        // 商品を売り切れ状態に更新
        $product->update(['is_sold' => true]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    public function test_purchased_product_appears_in_profile_purchase_list()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入した商品'
        ]);

        Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'total_amount' => $product->price,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('購入した商品');
    }
}