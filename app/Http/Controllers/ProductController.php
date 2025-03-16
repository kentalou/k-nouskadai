<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * 商品一覧表示（初期表示＆検索結果の両方を担当）
     */
    public function index(Request $request)
    {
        \DB::listen(function ($query) {});
        
        // 検索パラメータの取得（条件がなければ初期表示と同じ）
        $keyword = $request->input('keyword');
        $companyId = $request->input('company_id');
        $priceMin   = $request->input('price_min');
        $priceMax   = $request->input('price_max');
        $stockMin   = $request->input('stock_min');
        $stockMax   = $request->input('stock_max');
        $sortBy     = $request->input('sort_by', 'id');
        $sortOrder  = $request->input('sort_order', 'asc');

        // 全体の実データ件数（ダミーデータを除く）を取得
        $overallRealCount = \App\Models\Product::with('company')
            ->when($keyword, function ($query, $keyword) {
                $query->where('product_name', 'LIKE', "%{$keyword}%");
            })
            ->when($companyId, function ($query, $companyId) {
                $query->where('company_id', $companyId);
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
            })
            ->count();

        // ページネーション付きのデータ取得
        // 初期表示時は forceDummy は true（デフォルト）、検索時も同じ仕様（初期表示と一致）にする
        $products = Product::getProducts($keyword, $companyId, $priceMin, $priceMax, $stockMin, $stockMax, $sortBy, $sortOrder);

        // Ajaxリクエストならパーシャルビューを返す
        if ($request->ajax()) {
            // パーシャルビューに全体実データ件数も渡す
            $tableHtml = view('partials.product_rows', compact('products', 'overallRealCount'))->render();
            // ページネーション部分のレンダリング
            $paginationHtml = (string) $products->links('pagination::bootstrap-5');
            return response()->json([
                'html' => $tableHtml,
                'pagination' => $paginationHtml
            ]);
        } else {
            // 通常リクエストの場合は companies も渡してフルビューを返す
            $companies = Company::getAllCompanies();
            return view('products.index', compact('products', 'companies'));
        }
    }

    /**
     * 商品削除処理
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // 商品の削除処理
            Product::deleteProduct($id);

            // IDを詰める処理
            $products = Product::orderBy('id')->get();
            $newId = 1;
            foreach ($products as $product) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['id' => $newId]);
                $newId++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => config('message.delete_error')
            ], 500);
        }

        try {
            DB::statement('ALTER TABLE products AUTO_INCREMENT = ' . ($newId));
        } catch (\Exception $e) {
            // エラーが発生しても無視
        }

        return response()->json([
            'message' => config('message.delete_success')
        ], 200);
    }

    /**
     * 新規登録フォーム表示
     */
    public function create()
    {
        return view('products.create', ['companies' => Company::getAllCompanies()]);
    }

    /**
     * 商品登録処理
     */
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('product_images', 'public');
            }

            Product::createProduct($data);

            DB::commit();
            return redirect()->route('products.create')->with('success', config('message.create_success'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', config('message.create_error'));
        }
    }

    /**
     * 商品詳細表示
     */
    public function show($id)
    {
        return view('products.show', ['product' => Product::getProductById($id)]);
    }

    /**
     * 商品編集フォーム表示
     */
    public function edit($id)
    {
        return view('products.edit', [
            'product' => Product::getProductById($id),
            'companies' => Company::getAllCompanies()
        ]);
    }

    /**
     * 商品更新処理
     */
    public function update(ProductRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::getProductById($id);
            $data = $request->validated();

            if ($request->has('delete_image') && $request->delete_image == 1) {
                if ($product->image) {
                    Storage::delete('public/' . $product->image);
                    $data['image'] = null;
                }
            }

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::delete('public/' . $product->image);
                }
                $data['image'] = $request->file('image')->store('product_images', 'public');
            }

            Product::updateProduct($id, $data);

            DB::commit();
            return redirect()->route('products.edit', $product->id)->with('success', config('message.update_success'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => config('message.update_error')]);
        }
    }

}
