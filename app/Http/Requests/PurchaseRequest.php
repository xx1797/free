<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_method' => 'required|in:card,convenience,bank',
            'postal_code' => 'required|string',
            'address' => 'required|string',
            'building' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'payment_method.in' => '有効な支払い方法を選択してください',
            'postal_code.required' => '郵便番号を入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
