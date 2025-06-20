<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
    }

    public function test_user_can_edit_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
    }

    public function test_user_can_update_profile()
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/mypage/profile', [
            'name' => '更新されたユーザー名',
            'postal_code' => '123-4567',
            'address' => '更新された住所',
            'building' => '更新されたビル名',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertRedirect('/mypage');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '更新されたユーザー名',
            'postal_code' => '123-4567',
            'address' => '更新された住所',
            'building' => '更新されたビル名',
        ]);
    }
}
