<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->foreignId('company_id')->constrained(); // companiesテーブルの外部キー
            $table->string('product_name'); // 商品名
            $table->integer('price'); // 商品価格
            $table->integer('stock'); // 在庫数
            $table->text('comment')->nullable(); // 商品説明（任意）
            $table->string('image')->nullable(); // 商品画像（任意）
            $table->timestamps(); // 作成日時と更新日時
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products'); // テーブル削除
    }
}
