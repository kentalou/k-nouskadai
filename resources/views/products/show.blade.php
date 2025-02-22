@extends('layouts.productJr')

@section('title', '商品情報詳細画面')

@section('additional-content')
@php
    $imageContent = $product->image 
        ? '<img src="' . asset('storage/' . $product->image) . '" alt="商品画像" class="img-thumbnail" style="max-width: 80px;" style="max-heigh: 80px;">'
        : '<p class="no-image">画像</p>';
@endphp

<div class="container">
    <x-product.layout-form
        :isDisplayMode="true"
        :fields="[
            ['label' => 'ID', 'content' => $product->id],
            ['label' => '商品画像', 'content' => $imageContent],
            ['label' => '商品名', 'content' => $product->product_name],
            ['label' => 'メーカー', 'content' => $product->company->company_name],
            ['label' => '価格', 'content' => '¥' . number_format($product->price)],
            ['label' => '在庫数', 'content' => $product->stock],
            ['label' => 'コメント', 'content' => $product->comment ?? 'なし'],
        ]"
        :btnOrange="[
            'href' => route('products.edit', $product->id),
            'label' => '編集'
        ]"
        :btnSkyblue="[
            'href' => route('products.index'),
            'label' => '戻る'
        ]"
    />
</div>
@endsection
