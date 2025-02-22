@php
    $type = $type ?? 'text';
    $value = $value ?? '';
    $name = $name ?? '未設定';
    $required = $required ?? false;
@endphp

@switch($type)
    @case('select')
        <select id="{{ $id ?? $name }}" name="{{ $name }}" class="form-control" {{ $required ? 'required' : '' }}>
            <option value="" {{ old($name) == '' ? 'selected' : '' }} disabled></option>
            @foreach($options as $key => $option)
                <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>{{ $option }}</option>
            @endforeach
        </select>
        @break

    @case('textarea')
        <textarea id="{{ $id ?? $name }}" name="{{ $name }}" class="form-control" {{ $required ? 'required' : '' }}>{{ old($name, $value) }}</textarea>
        @break

    @case('file')
        <div class="d-flex align-items-center">
            <input type="file" id="{{ $id ?? $name }}" name="{{ $name }}" class="custom-file-input d-none">
            <label for="{{ $id ?? $name }}" class="custom-file-label" tabindex="0">ファイルを選択</label>
            <button type="button" class="btn btn-secondary btn-sm ml-2" id="clearImage" style="{{ empty($preview) ? 'display: none;' : '' }}">クリア</button> <!-- ✅ 右横に配置 -->
        </div>
        <div id="imagePreview" class="mt-3" style="{{ empty($preview) ? 'display: none;' : '' }}">
            @if (!empty($preview))
                <img src="{{ $preview }}" style="max-width: 100px;">
            @endif
        </div>
        <input type="hidden" name="delete_image" value="0"> <!-- 🔥 削除フラグ -->
        @break

    @default
        <input type="{{ $type }}" id="{{ $id ?? $name }}" name="{{ $name }}" class="form-control" value="{{ old($name, $value) }}" {{ $required ? 'required' : '' }}>
@endswitch