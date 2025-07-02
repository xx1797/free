<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_user_can_like_product()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->post("/api/products/{$product->id}/like");

        $response->assertStatus(200);
        $response->assertJson(['isLiked' => true]);
        
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_like_count_increases_when_liked()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->post("/api/products/{$product->id}/like");

        $response->assertStatus(200);
        $response->assertJson(['likeCount' => 1]);
    }

    public function test_user_can_unlike_product()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $otherUser->id]);
        
        // 最初にいいねする
        Like::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        $response = $this->actingAs($user)->post("/api/products/{$product->id}/like");

        $response->assertStatus(200);
        $response->assertJson(['isLiked' => false]);
        
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_like_count_decreases_when_unliked()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $otherUser->id]);
        
        // 最初にいいねする
        Like::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        $response = $this->actingAs($user)->post("/api/products/{$product->id}/like");

        $response->assertStatus(200);
        $response->assertJson(['likeCount' => 0]);
    }
}