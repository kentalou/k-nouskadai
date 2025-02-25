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
        $products = Product::getProducts($request->input('keyword'), $request->input('company_id'));
        $companies = Company::getAllCompanies();
        return view('products.index', compact('products', 'companies'));
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

    /**
     * 商品削除処理
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
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
            return back()->withErrors(['error' => config('message.delete_error')]);
        }

        try {
            DB::statement('ALTER TABLE products AUTO_INCREMENT = ' . ($newId));
        } catch (\Exception $e) {}

        return redirect()->back()->with('success', config('message.delete_success'));
    }
}
