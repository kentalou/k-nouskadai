<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['company_name', 'street_address', 'representative_name'];

    /**
     * 企業リスト取得
     */
    public static function getAllCompanies()
    {
        return self::all();
    }
}
