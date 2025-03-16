<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name', 'price', 'stock', 'company_id', 'image'
    ];

    /**
     * ✅ **会社とのリレーションを定義**
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * 商品一覧取得（検索機能付き + 2ページ目まで強制表示）
     *
     * @param string|null $keyword 検索キーワード
     * @param int|null    $company_id 会社ID
     * @param bool        $forceDummy 初期表示時にダミーデータを強制追加するかどうか（初期表示はtrue、検索時はfalse）
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function getProducts(
        $keyword = null,
        $company_id = null,
        $priceMin = null,
        $priceMax = null,
        $stockMin = null,
        $stockMax = null,
        $sortBy = 'id',
        $sortOrder = 'asc',
        $forceDummy = true
    ) {
        $query = self::with('company')
            ->when($keyword, function ($query, $keyword) {
                $query->where('product_name', 'LIKE', "%{$keyword}%");
            })
            ->when($company_id, function ($query, $company_id) {
                $query->where('company_id', $company_id);
            })
            ->when($priceMin, function ($query, $priceMin) {
                $query->where('price', '>=', $priceMin);
            })
            ->when($priceMax, function ($query, $priceMax) {
                $query->where('price', '<=', $priceMax);
            })
            ->when($stockMin, function ($query, $stockMin) {
                $query->where('stock', '>=', $stockMin);
            })
            ->when($stockMax, function ($query, $stockMax) {
                $query->where('stock', '<=', $stockMax);
            });

        // -----------------------------
        // メーカー名はproductsテーブル上だとcompany_idで対応しているため、ソート時はcompaniesテーブルからcompany_nameを呼びだす
        // -----------------------------
        if ($sortBy === 'company_name') {
            // companies テーブルを JOIN して、orderBy('companies.company_name', $sortOrder)
            $query->join('companies', 'products.company_id', '=', 'companies.id')
                  ->select('products.*', 'companies.company_name')
                  ->orderBy('companies.company_name', $sortOrder);
        } else {
            // それ以外（id, product_name, price, stock, image など）は普通に orderBy
            $query->orderBy($sortBy, $sortOrder);
        }

        // 1ページあたり7件表示のページネーションを適用
        $products = $query->paginate(7);

        // 初期表示（または forceDummy が true の場合）のみ、データが1ページ分（7件以下）の場合にダミーデータを追加して常に2ページ目を表示する
        if ($forceDummy && $products->total() <= $products->perPage()) {
            $dummyItems = array_fill(0, $products->perPage(), null);
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                array_merge($products->items(), $dummyItems),
                $products->perPage() * 2, // 総アイテム数を2ページ分に見せる
                $products->perPage(),
                $products->currentPage(),
                ['path' => request()->url()]
            );
        }

        return $products;
    }

    /**
     * ✅ **商品IDで取得**
     */
    public static function getProductById($id)
    {
        return self::with('company')->findOrFail($id);
    }

    /**
     * ✅ **商品作成**
     */
    public static function createProduct($data)
    {
        return self::create($data);
    }

    /**
     * ✅ **商品更新**
     */
    public static function updateProduct($id, $data)
    {
        return self::where('id', $id)->update($data);
    }

    /**
     * ✅ **商品削除**
     */
    public static function deleteProduct($id)
    {
        return self::where('id', $id)->delete();
    }
}
