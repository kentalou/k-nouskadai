@if (empty($isDisplayMode))
    <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if (!empty($method) && $method === 'PUT')
        @method('PUT')
    @endif
@endif

<div class="layout-form">
    <div class="form-group-container">
        @if (!empty($isDisplayMode))
            <!-- ✅ show 画面では details-table を使う -->
            <x-product.details-table :fields="$fields"/>
        @else
            @foreach ($fields as $field)
                <div class="form-group row">
                    <label class="col-md-3">
                        {{ $field['label'] ?? '' }}
                        @if (!empty($field['required']))
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <div class="col-md-9 position-relative">
                        {!! $field['content'] ?? $field['value'] ?? '' !!}

                        <!-- 🔹 エラーメッセージ統一管理 -->
                        @if (!empty($field['name']) && $errors->has($field['name']))
                            <span class="invalid-feedback d-block">
                                <strong>{{ $errors->first($field['name']) }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- 🔹 ボタン部分 -->
    <div class="form-actions mt-3">
        <x-product.action-button 
            :type="$btnOrange['type'] ?? null" 
            :href="$btnOrange['href'] ?? null" 
            :label="$btnOrange['label'] ?? '確定'" 
            color="orange"
        />
        
        <x-product.action-button 
            :type="$btnSkyblue['type'] ?? null" 
            :href="$btnSkyblue['href'] ?? null" 
            :label="$btnSkyblue['label'] ?? '戻る'" 
            color="skyblue" 
        />
    </div>
</div>

@if (empty($isDisplayMode))
    </form>
@endif