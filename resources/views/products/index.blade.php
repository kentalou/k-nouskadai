@extends('layouts.product')

@section('title', '商品一覧画面')

@section('content')
<!-- ✅ 一覧画面専用の ID を追加 -->
<div id="product-list-container" class="container mt-5">

    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('products.index') }}">
        <x-product.search :companies="$companies" />
    </form>

    @php
        $realProductCount = $products->filter(function ($product) {
            return !is_null($product);
        })->count();
    @endphp

    <!-- 商品一覧テーブル -->
    <div class="table-responsive border">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th class="id-header">ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th>
                        <a href="{{ route('products.create') }}" class="btn-register">新規登録</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                    @if (!is_null($product))
                        <x-product.row :product="$product" :index="$index" />
                    @endif
                @endforeach

                <!-- 黒点の行を表示 -->
                @if ($realProductCount < 7)
                    <tr class="dots">
                        <td colspan="7" class="text-center">
                            <span>●</span><span>●</span><span>●</span>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- ページネーション -->
    <div class="d-flex justify-content-center mt-3">
        {!! $products->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection
