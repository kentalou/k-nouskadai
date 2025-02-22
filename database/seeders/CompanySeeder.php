<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        // テストデータの挿入
        Company::firstOrCreate([
            'company_name' => 'テスト企業A',
            'street_address' => '東京都新宿区',
            'representative_name' => '田中太郎',
        ]);

        Company::firstOrCreate([
            'company_name' => 'テスト企業B',
            'street_address' => '大阪府大阪市',
            'representative_name' => '佐藤花子',
        ]);

        Company::firstOrCreate([
            'company_name' => 'テスト企業C',
            'street_address' => '福岡県福岡市',
            'representative_name' => '鈴木一郎',
        ]);

        Company::firstOrCreate([
            'company_name' => 'Coca-Cola',
            'street_address' => '東京都港区',
            'representative_name' => 'カリン・ドラガン',
        ]);

        Company::firstOrCreate([
            'company_name' => 'サントリー',
            'street_address' => '大阪府大阪市',
            'representative_name' => '新浪剛史',
        ]);

        Company::firstOrCreate([
            'company_name' => 'キリン',
            'street_address' => '東京都中野区',
            'representative_name' => '南方健志',
        ]);
    }
}
