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
        <button type="button" class="btn btn-light search-btn w-100">検索</button>
    </div>
    <div class="col-md-6">
        <input type="number" name="price_min" class="form-control search-input" placeholder="価格（下限）" value="{{ request('price_min') }}">
    </div>
    <div class="col-md-6">
        <input type="number" name="price_max" class="form-control search-input" placeholder="価格（上限）" value="{{ request('price_max') }}">
    </div>
    <div class="col-md-6">
        <input type="number" name="stock_min" class="form-control search-input" placeholder="在庫数（下限）" value="{{ request('stock_min') }}">
    </div>
    <div class="col-md-6">
        <input type="number" name="stock_max" class="form-control search-input" placeholder="在庫数（上限）" value="{{ request('stock_max') }}">
    </div>
</div>
