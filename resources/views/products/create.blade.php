@extends('layouts.productJr')

@section('title', '商品新規登録画面')

@section('additional-content')

<div class="container">
    <x-product.layout-form
        :formAction="route('products.store')"
        :fields="[
            ['label' => '商品名', 'name' => 'product_name', 'required' => true, 'content' => view('components.product.input-field', ['name' => 'product_name'])->render()],
            ['label' => 'メーカー名', 'name' => 'company_id', 'required' => true, 'content' => view('components.product.input-field', ['type' => 'select', 'name' => 'company_id', 'options' => $companies->pluck('company_name', 'id')])->render()],
            ['label' => '価格', 'name' => 'price', 'required' => true, 'content' => view('components.product.input-field', ['type' => 'number', 'name' => 'price'])->render()],
            ['label' => '在庫数', 'name' => 'stock', 'required' => true, 'content' => view('components.product.input-field', ['type' => 'number', 'name' => 'stock'])->render()],
            ['label' => 'コメント', 'name' => 'comment', 'content' => view('components.product.input-field', ['type' => 'textarea', 'name' => 'comment'])->render()],
            ['label' => '商品画像', 'name' => 'image', 'content' => view('components.product.input-field', ['type' => 'file', 'name' => 'image', 'preview' => session('temp_image') ? asset('storage/' . session('temp_image')) : null])->render()],
        ]"
        :btnOrange="[
            'type' => 'submit', 
            'label' => '新規登録'
        ]"
        :btnSkyblue="[
            'href' => route('products.index'), 
            'label' => '戻る'
        ]"
    />
</div>
@endsection
