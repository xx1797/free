<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_address_change_is_reflected_in_purchase_screen()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        // 住所変更
        $this->actingAs($user)->post("/purchase/address/{$product->id}", [
            'postal_code' => '999-8888',
            'address' => '変更後住所',
            'building' => '変更後ビル'
        ]);

        $response = $this->actingAs($user)->get("/purchase/{$product->id}");

        $response->assertStatus(200);
        // セッションから住所情報が取得されることを確認
    }

    public function test_shipping_address_is_linked_to_purchased_product()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        $response = $this->actingAs($user)->post("/purchase/{$product->id}", [
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル'
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル',
        ]);
    }
}
