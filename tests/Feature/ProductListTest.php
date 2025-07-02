<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_all_products_are_displayed()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'name' => 'テスト商品'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }

    public function test_sold_products_display_sold_label()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '売り切れ商品',
            'is_sold' => true
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    public function test_own_products_are_not_displayed()
    {
        $user = User::factory()->create();
        $ownProduct = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品'
        ]);
        
        $otherUser = User::factory()->create();
        $otherProduct = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品'
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }
}
