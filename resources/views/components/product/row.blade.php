<tr class="{{ $index % 2 == 0 ? 'table-secondary' : '' }}">
    <td>{{ optional($product)->id }}.</td> <!-- ドットを追加 -->
    <td>
        @if (optional($product)->image)
            <img src="{{ asset('storage/' . optional($product)->image) }}" alt="商品画像" class="product-image">
        @else
            商品画像
        @endif
    </td>
    <td>{{ optional($product)->product_name ?: '---' }}</td>
    <td>￥{{ number_format(optional($product)->price ?: 0) }}</td>
    <td>{{ optional($product)->stock ?: 0 }}</td>
    <td>{{ optional(optional($product)->company)->company_name ?: '---' }}</td>
    <td>
        @if (optional($product)->id)
            <a href="{{ route('products.show', optional($product)->id) }}" class="btn btn-info btn-sm">詳細</a>
            <button type="button"
                class="btn btn-danger btn-sm delete-button"
                data-delete-url="{{ route('products.destroy', $product->id) }}"
                data-token="{{ csrf_token() }}">
                削除
            </button>
        @else
            <span class="text-muted">---</span>
        @endif
    </td>
</tr>
