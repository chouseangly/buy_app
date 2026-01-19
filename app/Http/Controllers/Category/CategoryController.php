<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Category\CategoryService;

class CategoryController extends Controller
{
    public function addCategory(Request $request, CategoryService $service){
       $request->validate([
        'name'      => 'required|string|max:255',
        'slug'      => 'required|string|unique:categories,slug|max:255',
        'img'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validates as a single file
        'is_active' => 'nullable|boolean'
    ]);

        $category = $service->addCategory($request);

        return response()->json([
            'data' => $category,
            'message' => 'category create successfully'
        ]);

    }
}
