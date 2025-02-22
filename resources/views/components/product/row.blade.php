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
            <form method="POST" action="{{ route('products.destroy', optional($product)->id) }}" class="delete-form" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm delete-button">削除</button>
            </form>
        @else
            <span class="text-muted">---</span>
        @endif
    </td>
</tr>
