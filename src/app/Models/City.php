<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'todofuken_code',
        'todofuken_name',
        'city_code',
        'city_name',
        'city_kana',
    ];

    /**
     * 都道府県コードで市区町村を取得
     */
    public static function getByTodofukenCode(int $todofukenCode)
    {
        return static::where('todofuken_code', $todofukenCode)->orderBy('city_name')->get();
    }

    /**
     * 市区町村名で検索
     */
    public static function searchByName(string $name)
    {
        return static::where('city_name', 'like', "%{$name}%")
            ->orWhere('city_kana', 'like', "%{$name}%")
            ->orderBy('todofuken_code')
            ->orderBy('city_name')
            ->get();
    }
}
