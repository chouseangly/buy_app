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
        'img'       => 'nullable|file|mimes:jpg,jpeg,png,svg|max:2048', // Validates as a single file
        'is_active' => 'nullable|boolean'
    ]);

        $category = $service->addCategory($request);

        return response()->json([
            'data' => $category,
            'message' => 'category create successfully'
        ],201);

    }

    public function getAllCategory(CategoryService $service){

        $category = $service->getAll();

        return response()->json([
            'data' => $category,
            'message' => 'get all category successfully'
        ],200);
    }

    public function updateCategory(Request $request, $id,CategoryService $service){

     $request->validate([
        'name'      => 'sometimes|required|string|max:255',
        'slug'      => 'sometimes|required|string|max:255|unique:categories,slug,' . $id,
        'img'       => 'nullable|file|mimes:jpg,jpeg,png,svg|max:2048',
        'is_active' => 'nullable|boolean',
    ]);


        $category = $service->update($id,$request);

        if(!$category){
            return response()->json(
                [
                    'message' => 'Category not found'
                ],404
            );
        }

        return response()->json([
            'data' => $category,
            'message' => 'update category successfully'
        ],201);

    }

    public function deleteCategory($id,CategoryService $service){


        $category = $service->delete($id);
          if(!$category){
            return response()->json(
                [
                    'message' => 'Category not found'
                ],404
            );
        }
        return response()->json([
            'message' => 'category delete successfully'
        ]);
    }
}
