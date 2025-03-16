<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // 利用可能なカラムを設定（必要なカラムを追加）
    protected $fillable = [
        'product_id',
        'quantity',
        // 'total_price',  // 必要なら合計金額など
    ];

    // 商品とのリレーション（任意）
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
