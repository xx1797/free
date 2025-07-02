<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'condition_id' => 'required|exists:conditions,id',
            'price' => 'required|numeric|min:0',
            'brand' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'image.required' => '商品画像をアップロードしてください',
            'image.image' => '商品画像は画像ファイルを選択してください',
            'image.mimes' => '商品画像は.jpegまたは.png形式で選択してください',
            'image.max' => '商品画像のサイズは2MB以下にしてください',
            'categories.required' => '商品のカゴリーを選択してください',
            'categories.min' => '商品のカテゴリーを少なくとも1つ選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.numeric' => '商品価格は数値で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',
        ];
    }
}
