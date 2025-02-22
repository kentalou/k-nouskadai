<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'image',
    ];

    /**
     * 商品の一覧を取得
     */
    public static function getAllProducts()
    {
        return self::with('company')->get(); // リレーションで企業情報も取得
    }

    /**
     * 商品の詳細を取得
     */
    public static function getProductById($id)
    {
        return self::with('company')->findOrFail($id);
    }

    /**
     * 商品を登録
     */
    public static function createProduct($data)
    {
        return self::create($data);
    }

    /**
     * 商品を更新
     */
    public static function updateProduct($id, $data)
    {
        $product = self::findOrFail($id);
        $product->update($data);
        return $product;
    }

    /**
     * 商品を削除
     */
    public static function deleteProduct($id)
    {
        $product = self::findOrFail($id);
        $product->delete();
    }

    /**
     * リレーション: companies テーブル
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
