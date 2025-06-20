<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_only_liked_products_are_displayed()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $likedProduct = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'いいねした商品'
        ]);
        
        $notLikedProduct = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'いいねしていない商品'
        ]);

        Like::create([
            'user_id' => $user->id,
            'product_id' => $likedProduct->id
        ]);

        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    public function test_sold_products_in_mylist_display_sold_label()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $soldProduct = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '売り切れ商品',
            'is_sold' => true
        ]);

        Like::create([
            'user_id' => $user->id,
            'product_id' => $soldProduct->id
        ]);

        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    public function test_own_products_are_not_displayed_in_mylist()
    {
        $user = User::factory()->create();
        
        $ownProduct = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品'
        ]);

        Like::create([
            'user_id' => $user->id,
            'product_id' => $ownProduct->id
        ]);

        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
    }

    public function test_unauthenticated_user_sees_nothing_in_mylist()
    {
        $response = $this->get('/?page=mylist');

        $response->assertStatus(200);
        // マイリストページでは何も表示されない
    }
}
