<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Sale;

class SalesController extends Controller
{
    /**
     * 購入処理API
     *
     * リクエスト例:
     * {
     *     "product_id": 123,
     *     "quantity": 2
     * }
     */
    public function purchase(Request $request)
    {
        // 入力のバリデーション
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $productId = $request->input('product_id');
        $quantity  = $request->input('quantity');

        // トランザクション開始
        DB::beginTransaction();

        try {
            // 対象商品の取得
            $product = Product::findOrFail($productId);

            // 在庫チェック：在庫が0または購入数量が在庫を超えている場合はエラー
            if ($product->stock <= 0 || $product->stock < $quantity) {
                // 在庫不足エラーを返す
                return response()->json([
                    'error' => '在庫が不足しているため、購入できません。'
                ], 400);
            }

            // 在庫数を減算
            $product->stock -= $quantity;
            $product->save();

            // sales テーブルに購入レコードを追加
            // 必要に応じて、他の情報（購入日時、合計金額など）を追加してください
            Sale::create([
                'product_id' => $productId,
                'quantity'   => $quantity,
                // 例: 'total_price' => $product->price * $quantity,
            ]);

            // トランザクションのコミット
            DB::commit();

            return response()->json([
                'message' => '購入処理が完了しました。',
            ], 200);
        } catch (\Exception $e) {
            // エラー発生時はトランザクションをロールバック
            DB::rollBack();
            return response()->json([
                'error' => '購入処理中にエラーが発生しました。',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
