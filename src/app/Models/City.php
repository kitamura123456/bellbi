<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'prefecture_code',
        'city_code',
        'name',
        'name_kana',
    ];

    /**
     * 都道府県コードで市区町村を取得
     */
    public static function getByTodofukenCode(int $todofukenCode)
    {
        return static::where('prefecture_code', $todofukenCode)->orderBy('name')->get();
    }

    /**
     * 市区町村名で検索
     */
    public static function searchByName(string $name)
    {
        return static::where('name', 'like', "%{$name}%")
            ->orWhere('name_kana', 'like', "%{$name}%")
            ->orderBy('prefecture_code')
            ->orderBy('name')
            ->get();
    }
}
