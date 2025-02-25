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
     * ✅ **商品一覧取得（検索機能付き + 2ページ目まで強制表示）**
     */
    public static function getProducts($keyword = null, $company_id = null)
    {
        $query = self::with('company')
            ->when($keyword, function ($query, $keyword) {
                $query->where('product_name', 'LIKE', "%{$keyword}%");
            })
            ->when($company_id, function ($query, $company_id) {
                $query->where('company_id', $company_id);
            });

        // ✅ **ページネーション処理**
        $products = $query->paginate(7);

        // ✅ **ページが1つしかない場合、ダミーデータを追加**
        if ($products->total() <= $products->perPage()) {
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
