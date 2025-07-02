<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // テスト用ユーザー
        User::create([
            'name' => 'テストユーザー1',
            'email' => 'test1@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        User::create([
            'name' => 'テストユーザー2',
            'email' => 'test2@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市2-2-2',
            'building' => 'サンプルマンション202',
        ]);

        // 追加のダミーユーザー
        User::factory(8)->create();
    }
}
