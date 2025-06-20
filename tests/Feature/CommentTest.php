<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_authenticated_user_can_send_comment()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->post("/item/{$product->id}/comments", [
            'comment' => 'テストコメント'
        ]);

        $response->assertRedirect("/item/{$product->id}");
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'テストコメント',
        ]);
    }

    public function test_unauthenticated_user_cannot_send_comment()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $response = $this->post("/item/{$product->id}/comments", [
            'comment' => 'テストコメント'
        ]);

        $response->assertRedirect('/login');
    }

    public function test_comment_validation_when_comment_is_empty()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->post("/item/{$product->id}/comments", [
            'comment' => ''
        ]);

        $response->assertSessionHasErrors(['comment' => 'コメントを入力してください']);
    }

    public function test_comment_validation_when_comment_exceeds_255_characters()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $otherUser->id]);

        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)->post("/item/{$product->id}/comments", [
            'comment' => $longComment
        ]);

        $response->assertSessionHasErrors(['comment' => 'コメントは255文字以内で入力してください']);
    }
}
