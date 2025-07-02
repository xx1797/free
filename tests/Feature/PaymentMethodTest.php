<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    public function test_payment_method_selection_is_reflected_immediately()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        $response = $this->actingAs($user)->get("/purchase/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('コンビニ支払い');
        $response->assertSee('カード支払い');
    }
}
