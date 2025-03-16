<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // users テーブルの name カラムを NOT NULL に変更する（MySQL用）
        DB::statement("ALTER TABLE users MODIFY name VARCHAR(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ロールバック時は name カラムを NULL 許容に戻す（MySQL用）
        DB::statement("ALTER TABLE users MODIFY name VARCHAR(255) NULL");
    }
};
