<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_required_user_information_is_displayed()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー'
        ]);

        // 出品した商品
        $listedProduct = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品'
        ]);

        // 購入した商品
        $seller = User::factory()->create();
        $purchasedProduct = Product::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入商品'
        ]);
        
        Order::create([
            'user_id' => $user->id,
            'product_id' => $purchasedProduct->id,
            'total_amount' => 1000,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('出品商品');
        
        // 購入した商品一覧を確認
        $response = $this->actingAs($user)->get('/mypage?page=buy');
        $response->assertSee('購入商品');
    }

    public function test_profile_edit_shows_initial_values()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('value="テストユーザー"', false);
        $response->assertSee('value="123-4567"', false);
        $response->assertSee('テスト住所');
    }
}
