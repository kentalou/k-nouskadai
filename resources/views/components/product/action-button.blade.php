@if (!empty($href) && $type !== 'submit')
    <!-- 🔹 リンクボタン (戻るボタン、編集ボタン など) -->
    <a href="{{ $href }}" class="btn btn-{{ $color ?? 'primary' }} {{ $class ?? '' }}" {{ $attributes }}>
        {{ $label }}
    </a>
@else
    <!-- 🔹 フォーム送信ボタン (新規登録、更新 など) -->
    <button type="submit" class="btn btn-{{ $color ?? 'primary' }} {{ $class ?? '' }}" {{ $attributes }}>
        {{ $label }}
    </button>
@endif
