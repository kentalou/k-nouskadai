<div class="details-table">
    @foreach ($fields as $field)
        <div class="form-group details-row"> <!-- ✅ `create` 側と同じクラスを適用 -->
            <label class="col-md-3 details-label {{ $field['label'] === 'ID' ? 'id-header' : '' }}">
                {{ $field['label'] }}
            </label>
            <div class="col-md-9 details-value {{ in_array($field['label'], ['商品画像', 'コメント']) ? 'bordered-box' : '' }}">
                @if ($field['label'] === 'ID')
                    {{ $field['content'] }}.
                @else
                    {!! $field['content'] ?? 'ー' !!}
                @endif
            </div>
        </div>
    @endforeach
</div>
