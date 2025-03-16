<!-- 商品一覧画面検索機能用 -->

@php
    $actualCount = $products->filter(fn($p) => !is_null($p))->count();
    $currentPage = $products->currentPage();
@endphp

@if ($overallRealCount == 0)
    <tr>
        <td colspan="7" class="text-center">該当する商品はありません。</td>
    </tr>
@else
    @foreach ($products as $index => $product)
        @if (!is_null($product))
            <x-product.row :product="$product" :index="$index" />
        @endif
    @endforeach

    {{-- そのページの実データ件数が7件未満なら黒点を表示 --}}
    @if ($actualCount < $products->perPage())
        <tr class="dots">
            <td colspan="7" class="text-center">
                <span>●</span><span>●</span><span>●</span>
            </td>
        </tr>
    @endif
@endif
