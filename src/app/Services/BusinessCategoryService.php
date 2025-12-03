<?php

namespace App\Services;

class BusinessCategoryService
{
    /**
     * 業種大分類
     */
    public static function getIndustryTypes(): array
    {
        return [
            1 => '美容',
            2 => '医療',
            3 => '歯科',
        ];
    }

    /**
     * 業種中分類（全体）
     */
    public static function getBusinessCategories(): array
    {
        return [
            // 美容系（10番台）
            11 => '美容室・ヘアサロン',
            12 => 'ネイルサロン',
            13 => 'まつ毛エクステ・アイラッシュ',
            14 => 'エステサロン',
            15 => 'リラクゼーション・マッサージ',
            16 => '脱毛サロン',
            17 => '美容クリニック（美容外科・美容皮膚科）',
            18 => 'アイブロウサロン',
            19 => 'その他美容',

            // 医療系（20番台）
            21 => '総合病院',
            22 => 'クリニック（内科・外科等）',
            23 => '整形外科',
            24 => '皮膚科',
            25 => 'その他医療',

            // 歯科系（30番台）
            31 => '歯科医院',
            32 => '矯正歯科',
            33 => '小児歯科',
            34 => 'その他歯科',
        ];
    }

    /**
     * 大分類に紐づく中分類を取得
     */
    public static function getCategoriesByIndustry(int $industryType): array
    {
        $all = self::getBusinessCategories();
        $filtered = [];

        foreach ($all as $code => $name) {
            $firstDigit = (int)floor($code / 10);
            
            if ($industryType === 1 && $firstDigit === 1) {
                $filtered[$code] = $name;
            } elseif ($industryType === 2 && $firstDigit === 2) {
                $filtered[$code] = $name;
            } elseif ($industryType === 3 && $firstDigit === 3) {
                $filtered[$code] = $name;
            }
        }

        return $filtered;
    }

    /**
     * 業種コードから名称を取得
     */
    public static function getCategoryName(int $code): ?string
    {
        return self::getBusinessCategories()[$code] ?? null;
    }

    /**
     * 業種コードから大分類を取得
     */
    public static function getIndustryTypeByCategory(int $categoryCode): ?int
    {
        $range = floor($categoryCode / 10);
        if ($range === 1) return 1; // 美容
        if ($range === 2) return 2; // 医療
        if ($range === 3) return 3; // 歯科
        return null;
    }
}

