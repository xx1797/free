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

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::factory()->create(['name' => 'テストカテゴリ']);
        Condition::factory()->create(['name' => '良好']);
    }

    // RegisterRequest.php テスト
    public function test_register_email_validation()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_register_password_min_length_validation()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_register_password_confirmation_validation()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
    }

    // LoginRequest.php テスト
    public function test_login_email_format_validation()
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_password_min_length_validation()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    // CommentRequest.php テスト
    public function test_comment_required_validation()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post("/item/{$product->id}/comments", [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment' => 'コメントを入力してください']);
    }

    public function test_comment_max_length_validation()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)->post("/item/{$product->id}/comments", [
            'comment' => $longComment,
        ]);

        $response->assertSessionHasErrors(['comment' => 'コメントは255文字以内で入力してください']);
    }

    // PurchaseRequest.php テスト
    public function test_purchase_payment_method_required_validation()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post("/purchase/{$product->id}", [
            'payment_method' => '',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
        ]);

        $response->assertSessionHasErrors(['payment_method' => '支払い方法を選択してください']);
    }

    // AddressRequest.php テスト
    public function test_address_postal_code_format_validation()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post("/purchase/address/{$product->id}", [
            'name' => 'テストユーザー',
            'postal_code' => '1234567', // ハイフンなし
            'address' => 'テスト住所',
            'building' => 'テストビル',
        ]);

        $response->assertSessionHasErrors(['postal_code' => '郵便番号はハイフンありの8文字で入力してください（例：123-4567）']);
    }

    public function test_address_building_required_validation()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post("/purchase/address/{$product->id}", [
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => '',
        ]);

        $response->assertSessionHasErrors(['building' => '建物名を入力してください']);
    }

    // ProfileRequest.php テスト
    public function test_profile_avatar_format_validation()
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/mypage/profile', [
            'name' => 'テストユーザー',
            'avatar' => UploadedFile::fake()->create('test.txt', 100),
        ]);

        $response->assertSessionHasErrors(['avatar']);
    }

    // ExhibitionRequest.php テスト
    public function test_exhibition_name_required_validation()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::first();
        $condition = Condition::first();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => '',
            'description' => 'テスト説明',
            'categories' => [$category->id],
            'condition_id' => $condition->id,
            'price' => 1000,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertSessionHasErrors(['name' => '商品名を入力してください']);
    }

    public function test_exhibition_description_max_length_validation()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::first();
        $condition = Condition::first();
        $longDescription = str_repeat('あ', 256);

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => $longDescription,
            'categories' => [$category->id],
            'condition_id' => $condition->id,
            'price' => 1000,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertSessionHasErrors(['description' => '商品説明は255文字以内で入力してください']);
    }

    public function test_exhibition_image_required_validation()
    {
        $user = User::factory()->create();
        $category = Category::first();
        $condition = Condition::first();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'categories' => [$category->id],
            'condition_id' => $condition->id,
            'price' => 1000,
        ]);

        $response->assertSessionHasErrors(['image' => '商品画像をアップロードしてください']);
    }

    public function test_exhibition_image_format_validation()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::first();
        $condition = Condition::first();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'categories' => [$category->id],
            'condition_id' => $condition->id,
            'price' => 1000,
            'image' => UploadedFile::fake()->create('test.txt', 100),
        ]);

        $response->assertSessionHasErrors(['image' => '商品画像は.jpegまたは.png形式で選択してください']);
    }

    public function test_exhibition_categories_required_validation()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $condition = Condition::first();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'categories' => [],
            'condition_id' => $condition->id,
            'price' => 1000,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertSessionHasErrors(['categories' => '商品のカテゴリーを選択してください']);
    }

    public function test_exhibition_price_numeric_validation()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::first();
        $condition = Condition::first();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'categories' => [$category->id],
            'condition_id' => $condition->id,
            'price' => 'invalid',
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertSessionHasErrors(['price' => '商品価格は数値で入力してください']);
    }

    public function test_exhibition_price_min_validation()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::first();
        $condition = Condition::first();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'categories' => [$category->id],
            'condition_id' => $condition->id,
            'price' => -1,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertSessionHasErrors(['price' => '商品価格は0円以上で入力してください']);
    }
}
