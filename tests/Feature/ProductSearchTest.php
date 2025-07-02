<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_partial_match_search_by_product_name()
    {
        $user = User::factory()->create();
        
        Product::factory()->create([
            'user_id' => $user->id,
            'name' => 'テスト商品A'
        ]);
        
        Product::factory()->create([
            'user_id' => $user->id,
            'name' => '別の商品B'
        ]);

        $response = $this->get('/?search=テスト');

        $response->assertStatus(200);
        $response->assertSee('テスト商品A');
        $response->assertDontSee('別の商品B');
    }

    public function test_search_state_is_maintained_in_mylist()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $product = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'テスト商品'
        ]);

        Like::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        $response = $this->actingAs($user)->get('/?page=mylist&search=テスト');

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }
}
