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
                    <th class="sort-header" data-column="id" tabindex="0">ID</th>
                    <th class="sort-header" data-column="image" tabindex="0">商品画像</th>
                    <th class="sort-header" data-column="product_name" tabindex="0">商品名</th>
                    <th class="sort-header" data-column="price" tabindex="0">価格</th>
                    <th class="sort-header" data-column="stock" tabindex="0">在庫数</th>
                    <th class="sort-header" data-column="company_name" tabindex="0">メーカー名</th>
                    <th>
                        <a href="{{ route('products.create') }}" class="btn-register">新規登録</a>
                    </th>
                </tr>
            </thead>
            <tbody id="product-table">
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
    <div id="pagination-container" class="d-flex justify-content-center mt-3">
        {!! $products->links('pagination::bootstrap-5') !!}
    </div>

    @push('scripts')
        <!-- ✅ 追加：jQuery・tablesorter CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            const configMessage = {
                delete_success: "{{ config('message.delete_success') }}",
                delete_error:   "{{ config('message.delete_error') }}"
            };
            var searchUrl = "{{ route('products.index') }}";
        </script>
        <script src="{{ asset('js/product-index.js') }}"></script>
    @endpush
</div>
@endsection
