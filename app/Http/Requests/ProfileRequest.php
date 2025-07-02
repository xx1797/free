<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'postal_code' => 'nullable|string|regex:/^\d{3}-\d{4}$/',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'postal_code.regex' => '郵便番号はハイフンありの8文字で入力してください（例：123-4567）',
            'avatar.image' => 'プロフィール画像は画像ファイルを選択してください',
            'avatar.mimes' => 'プロフィール画像は.jpegまたは.png形式で選択してください',
            'avatar.max' => 'プロフィール画像のサイズは2MB以下にしてください',
        ];
    }
}
