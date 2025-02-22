<div class="row g-3 mb-4">
    <div class="col-md-6">
        <input type="text" name="keyword" class="form-control search-input" placeholder="検索キーワード" value="{{ request('keyword') }}">
    </div>
    <div class="col-md-4">
        <select name="company_id" class="form-select search-input">
            <option value="">メーカー名</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                    {{ $company->company_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-light search-btn w-100">検索</button>
    </div>
</div>
