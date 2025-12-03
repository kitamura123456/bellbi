<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BusinessCategoryService;
use Illuminate\Http\JsonResponse;

class BusinessCategoryController extends Controller
{
    public function getCategories(int $industryType): JsonResponse
    {
        $categories = BusinessCategoryService::getCategoriesByIndustry($industryType);
        return response()->json(['categories' => $categories]);
    }
}

