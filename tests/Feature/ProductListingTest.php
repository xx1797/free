<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductListingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'カテゴリ1']);
        Category::factory()->create(['name' => 'カテゴリ2']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_product_listing_saves_required_information()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category1 = Category::where('name', 'カテゴリ1')->first();
        $category2 = Category::where('name', 'カテゴリ2')->first();
        $condition = Condition::first();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト説明',
            'categories' => [$category1->id, $category2->id],
            'condition_id' => $condition->id,
            'price' => 1000,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertRedirect('/');
        
        $this->assertDatabaseHas('products', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト説明',
            'condition_id' => $condition->id,
            'price' => 1000,
        ]);

        // カテゴリの関連付けを確認
        $product = \App\Models\Product::where('name', 'テスト商品')->first();
        $this->assertTrue($product->categories->contains($category1));
        $this->assertTrue($product->categories->contains($category2));
    }
}
