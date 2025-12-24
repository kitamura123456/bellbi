<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Enums\Todofuken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CityController extends Controller
{
    /**
     * 都道府県コードから市区町村を取得
     */
    public function getCitiesByPrefecture(Request $request)
    {
        $request->validate([
            'prefecture_code' => 'required|integer|min:1|max:47',
        ]);

        $prefectureCode = $request->input('prefecture_code');

        $cities = City::where('prefecture_code', $prefectureCode)
            ->orderBy('name')
            ->get();

        // 各市区町村の件数を取得
        $cityCounts = \App\Models\JobPost::where('status', 1)
            ->where('delete_flg', 0)
            ->where('prefecture_code', $prefectureCode)
            ->whereNotNull('city_code')
            ->where(function($q) {
                $now = \Carbon\Carbon::now();
                $q->whereNull('publish_start_at')
                  ->orWhere('publish_start_at', '<=', $now);
            })
            ->where(function($q) {
                $now = \Carbon\Carbon::now();
                $q->whereNull('publish_end_at')
                  ->orWhere('publish_end_at', '>', $now);
            })
            ->selectRaw('city_code, count(*) as count')
            ->groupBy('city_code')
            ->pluck('count', 'city_code')
            ->toArray();

        return response()->json([
            'success' => true,
            'prefecture_code' => $prefectureCode,
            'prefecture_name' => Todofuken::from($prefectureCode)->label(),
            'cities' => $cities->map(function($city) use ($cityCounts) {
                return [
                    'id' => $city->id,
                    'city_code' => $city->city_code,
                    'name' => $city->name,
                    'name_kana' => $city->name_kana,
                    'count' => $cityCounts[$city->city_code] ?? 0,
                ];
            })->values(),
        ]);
    }

    /**
     * 現在地から市区町村を取得
     */
    public function getCitiesByLocation(Request $request)
    {
        $request->validate([
            'prefecture' => 'nullable|string',
            'city' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $prefectureName = $request->input('prefecture');
        $cityName = $request->input('city');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        
        // 緯度経度が提供されているが都道府県名がない場合、逆ジオコーディングを実行
        if (!$prefectureName && $latitude && $longitude) {
            $geocodeResult = $this->reverseGeocode($latitude, $longitude);
            if ($geocodeResult && isset($geocodeResult['prefecture'])) {
                $prefectureName = $geocodeResult['prefecture'];
                $cityName = $geocodeResult['city'] ?? $cityName;
            } else {
                // 逆ジオコーディングに失敗した場合のエラーメッセージ
                return response()->json([
                    'success' => false,
                    'message' => '住所情報を取得できませんでした。位置情報が正しく取得できていない可能性があります。',
                ]);
            }
        }

        $prefectureCode = null;
        
        // 都道府県名から都道府県コードを取得
        if ($prefectureName) {
            // 都道府県名の正規化（「都」「府」「県」を除去）
            $normalizedPref = preg_replace('/[都府県]$/', '', trim($prefectureName));
            
            // 都道府県名に「都」「府」「県」が含まれていない場合も考慮
            $prefWithoutSuffix = $normalizedPref;
            
            foreach (Todofuken::cases() as $pref) {
                $prefLabel = $pref->label();
                $normalizedLabel = preg_replace('/[都府県]$/', '', $prefLabel);
                
                // 完全一致
                if ($prefLabel === $prefectureName || $normalizedLabel === $normalizedPref) {
                    $prefectureCode = $pref->value;
                    break;
                }
                
                // 部分一致（都道府県名が含まれている場合）
                if (strpos($prefectureName, $prefLabel) !== false || 
                    strpos($prefectureName, $normalizedLabel) !== false ||
                    strpos($prefLabel, $prefWithoutSuffix) !== false ||
                    strpos($normalizedLabel, $prefWithoutSuffix) !== false) {
                    $prefectureCode = $pref->value;
                    break;
                }
            }
            
            // まだ見つからない場合、英語名やローマ字名で検索
            if (!$prefectureCode) {
                $prefMap = [
                    'Tokyo' => 13, 'Osaka' => 27, 'Kyoto' => 26, 'Hokkaido' => 1,
                    'Aomori' => 2, 'Iwate' => 3, 'Miyagi' => 4, 'Akita' => 5,
                    'Yamagata' => 6, 'Fukushima' => 7, 'Ibaraki' => 8, 'Tochigi' => 9,
                    'Gunma' => 10, 'Saitama' => 11, 'Chiba' => 12, 'Kanagawa' => 14,
                    'Niigata' => 15, 'Toyama' => 16, 'Ishikawa' => 17, 'Fukui' => 18,
                    'Yamanashi' => 19, 'Nagano' => 20, 'Gifu' => 21, 'Shizuoka' => 22,
                    'Aichi' => 23, 'Mie' => 24, 'Shiga' => 25, 'Hyogo' => 28,
                    'Nara' => 29, 'Wakayama' => 30, 'Tottori' => 31, 'Shimane' => 32,
                    'Okayama' => 33, 'Hiroshima' => 34, 'Yamaguchi' => 35, 'Tokushima' => 36,
                    'Kagawa' => 37, 'Ehime' => 38, 'Kochi' => 39, 'Fukuoka' => 40,
                    'Saga' => 41, 'Nagasaki' => 42, 'Kumamoto' => 43, 'Oita' => 44,
                    'Miyazaki' => 45, 'Kagoshima' => 46, 'Okinawa' => 47,
                ];
                
                foreach ($prefMap as $enName => $code) {
                    if (stripos($prefectureName, $enName) !== false) {
                        $prefectureCode = $code;
                        break;
                    }
                }
            }
        }

        if (!$prefectureCode) {
            return response()->json([
                'success' => false,
                'message' => '都道府県が見つかりませんでした。',
            ]);
        }

        // 市区町村を取得
        $cities = City::where('prefecture_code', $prefectureCode)
            ->orderBy('name')
            ->get();

        // 市区町村名で絞り込み（部分一致）
        if ($cityName && $cities->isNotEmpty()) {
            $filteredCities = $cities->filter(function($city) use ($cityName) {
                return strpos($city->name, $cityName) !== false || 
                       strpos($city->name_kana ?? '', $cityName) !== false;
            });
            
            if ($filteredCities->isNotEmpty()) {
                $cities = $filteredCities;
            }
        }

        return response()->json([
            'success' => true,
            'prefecture_code' => $prefectureCode,
            'prefecture_name' => Todofuken::from($prefectureCode)->label(),
            'city_name' => $cityName,
            'cities' => $cities->map(function($city) {
                return [
                    'id' => $city->id,
                    'city_code' => $city->city_code,
                    'name' => $city->name,
                    'name_kana' => $city->name_kana,
                ];
            })->values(),
        ]);
    }
    
    /**
     * 逆ジオコーディング（サーバー側で実行）
     */
    private function reverseGeocode($latitude, $longitude)
    {
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=18&addressdetails=1&accept-language=ja";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'BellbiJobSearch/1.0');
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode !== 200 || !$response) {
                return null;
            }
            
            $data = json_decode($response, true);
            
            if (!$data || !isset($data['address'])) {
                return null;
            }
            
            $address = $data['address'];
            
            // 日本の住所構造に合わせて都道府県名を取得
            $prefectureName = $address['state'] ?? 
                             $address['prefecture'] ?? 
                             $address['province'] ?? 
                             $address['region'] ?? 
                             $address['state_district'] ?? null;
            
            // 都道府県名が取得できない場合、display_nameから抽出を試みる
            if (!$prefectureName && isset($data['display_name'])) {
                $displayName = $data['display_name'];
                // 都道府県名のパターンを検索
                if (preg_match('/([^,]+[都府県])/', $displayName, $matches)) {
                    $prefectureName = trim($matches[1]);
                }
            }
            
            $cityName = $address['city'] ?? 
                       $address['town'] ?? 
                       $address['village'] ?? 
                       $address['municipality'] ?? 
                       $address['city_district'] ?? null;
            
            return [
                'prefecture' => $prefectureName,
                'city' => $cityName,
                'full_address' => $data['display_name'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('逆ジオコーディングエラー: ' . $e->getMessage());
            return null;
        }
    }
}

