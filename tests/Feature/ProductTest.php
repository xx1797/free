<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_guest_can_view_product_list()
    {
        $user = User::factory()->create();
        Product::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('products.index');
    }

    public function test_guest_can_view_product_detail()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $response = $this->get("/item/{$product->id}");

        $response->assertStatus(200);
        $response->assertViewIs('products.show');
    }

    public function test_user_can_search_products()
    {
        $user = User::factory()->create();
        Product::factory()->create([
            'user_id' => $user->id,
            'name' => 'テスト商品'
        ]);

        $response = $this->get('/?search=テスト');

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }

    public function test_authenticated_user_can_create_product()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::first();
        $condition = Condition::first();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト説明',
            'categories' => [$category->id],
            'condition_id' => $condition->id,
            'price' => 1000,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('products', [
            'name' => 'テスト商品',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_like_product()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->post("/api/products/{$product->id}/like");

        $response->assertStatus(200);
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_user_can_comment_on_product()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->post("/item/{$product->id}/comments", [
            'comment' => 'テストコメント',
        ]);

        $response->assertRedirect("/item/{$product->id}");
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'テストコメント',
        ]);
    }
}
