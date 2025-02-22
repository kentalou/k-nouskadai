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
     * 商品一覧表示
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $company_id = $request->input('company_id');

        // 商品情報を取得
        $query = Product::with('company')
            ->when($keyword, function ($query, $keyword) {
                $query->where('product_name', 'LIKE', "%{$keyword}%");
            })
            ->when($company_id, function ($query, $company_id) {
                $query->where('company_id', $company_id);
            });

        // ページネーションの設定
        $products = $query->paginate(7);

        // ページが1つしかない場合、ダミーデータを追加してページネーションを2ページに拡張
        if ($products->total() <= $products->perPage()) {
            $dummyItems = array_fill(0, $products->perPage(), null);
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                array_merge($products->items(), $dummyItems),
                $products->perPage() * 2, // 総アイテム数を2ページ分に見せる
                $products->perPage(),
                $products->currentPage(),
                ['path' => $request->url()]
            );
        }

        // 企業リストを取得
        $companies = Company::all();

        return view('products.index', compact('products', 'companies'));
    }

    /**
     * 新規登録フォーム表示
     */
    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    /**
     * 商品登録処理
     */
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $request->validated();

            // 🔹 画像を一時保存して、セッションに記録
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('product_images', 'public');
                $data['image'] = $imagePath;
                
                // 🛠 **バリデーションエラー時にも画像を保持**
                session()->put('temp_image', $imagePath);
                
            }

            Product::create($data);

            // 成功したらセッションの画像を削除
            session()->forget('temp_image');

            DB::commit();
            return redirect()->route('products.create')->with('success', '商品を登録しました。');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', '商品登録中にエラーが発生しました。');
        }
    }

    /**
     * 商品詳細表示
     */
    public function show($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * 商品編集フォーム表示
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();

        return view('products.edit', compact('product', 'companies'));
    }

    /**
     * 商品更新処理
     */
    public function update(ProductRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $data = $request->validated();

            // ✅ 画像の削除処理（クリアボタンが押された場合）
            if ($request->has('delete_image') && $request->delete_image == 1) {
                if ($product->image) {
                    Storage::delete('public/' . $product->image); // 画像削除
                    $data['image'] = null; // DBも `null` に更新
                }
            }

            // ✅ 新しい画像のアップロード処理（アップロードがある場合のみ実行）
            if ($request->hasFile('image')) {
                // もし既存の画像があるなら削除
                if ($product->image) {
                    Storage::delete('public/' . $product->image);
                }
                // 新しい画像を保存
                $imagePath = $request->file('image')->store('product_images', 'public');
                $data['image'] = $imagePath;
            }

            // ✅ 商品情報を更新
            $product->update($data);

            DB::commit();
            return redirect()->route('products.edit', $product->id)->with('success', '商品情報を更新しました。');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '商品更新中にエラーが発生しました。']);
        }
    }

    /**
     * 商品削除処理
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Product::where('id', $id)->delete();

            // IDを詰める処理
            $products = Product::orderBy('id')->get(); // ID順に並び替え
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
            return back()->withErrors(['error' => '商品削除中にエラーが発生しました。']);
        }

          // 🔹 トランザクション外で AUTO_INCREMENT リセットを実行
        try {
            DB::statement('ALTER TABLE products AUTO_INCREMENT = ' . ($newId));
        } catch (\Exception $e) {
            // もし失敗してもID前詰めは完了しているので、
            // ここはログ出力などにとどめ、処理を続行してもよい
            // return back()->withErrors(['error' => 'AUTO_INCREMENT リセットに失敗しました。']);
        }

        // 最後にリダイレクト＋フラッシュメッセージ
        return redirect()->back()->with('success', '商品を削除しました。');
    }
}
