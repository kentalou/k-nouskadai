<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 全てのユーザーに許可
    }

    public function rules()
    {
        return [
            'product_name' => 'required|max:255',
            'price' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'company_id' => 'required|exists:companies,id',
            'comment' => 'nullable|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => '商品名は必須です。',
            'product_name.max' => '商品名は255文字以内で入力してください。',
            'price.required' => '価格は必須です。',
            'price.integer' => '価格は数値で入力してください。',
            'price.min' => '価格は1以上の値で入力してください。',
            'stock.required' => '在庫数は必須です。',
            'stock.integer' => '在庫数は整数で入力してください。',
            'stock.min' => '在庫数は0以上の値で入力してください。',
            'company_id.required' => 'メーカー名を選択してください。',
            'company_id.exists' => '選択されたメーカーが無効です。',
            'comment.max' => 'コメントは1000文字以内で入力してください。',
            'image.image' => '有効な画像ファイルを選択してください。',
            'image.mimes' => '画像はJPEGまたはPNG形式でアップロードしてください。',
            'image.max' => '画像ファイルは2MB以下である必要があります。',
        ];
    }
}
