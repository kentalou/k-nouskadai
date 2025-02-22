@extends('layouts.productJr') <!-- 子テンプレートを利用 -->

@section('title', '商品情報編集画面')

@section('additional-content')
    <div class="container">
        <!-- ✅ ID のみ details-table を適用 -->
        <x-product.layout-form
            :isDisplayMode="true"
            :fields="[
                ['label' => 'ID', 'content' => $product->id],
            ]"
        />
        
        <x-product.layout-form
            :formAction="route('products.update', $product->id)"
            method="PUT"
            :fields="[
                ['label' => '商品名', 'name' => 'product_name', 'required' => true, 'content' => view('components.product.input-field', ['name' => 'product_name', 'value' => $product->product_name])->render()],
                ['label' => 'メーカー名', 'name' => 'company_id', 'required' => true, 'content' => view('components.product.input-field', ['type' => 'select', 'name' => 'company_id', 'options' => $companies->pluck('company_name', 'id'), 'value' => $product->company_id])->render()],
                ['label' => '価格', 'name' => 'price', 'required' => true, 'content' => view('components.product.input-field', ['type' => 'number', 'name' => 'price', 'value' => $product->price])->render()],
                ['label' => '在庫数', 'name' => 'stock', 'required' => true, 'content' => view('components.product.input-field', ['type' => 'number', 'name' => 'stock', 'value' => $product->stock])->render()],
                ['label' => 'コメント', 'name' => 'comment', 'content' => view('components.product.input-field', ['type' => 'textarea', 'name' => 'comment', 'value' => $product->comment])->render()],
                ['label' => '商品画像', 'name' => 'image', 'content' => view('components.product.input-field', ['type' => 'file', 'name' => 'image', 'preview' => $product->image ? asset('storage/' . $product->image) : null])->render()],
            ]"
            :btnOrange="[
                'type' => 'submit', 
                'label' => '更新'
            ]"
            :btnSkyblue="[
                'href' => route('products.show', $product->id),
                'label' => '戻る'
            ]"
        />
    </div>
@endsection
