<?php

namespace App\Services\Category;

use Illuminate\Support\Facades\DB;
use App\Repositories\Category\CategoryRepo;

class CategoryService{
    public function __construct(private CategoryRepo $repo ) { }

   public function addCategory($request)
{
    return DB::transaction(function () use ($request) {
        $category = $this->repo->createCategory([
            'name' => $request->name,
            'slug' => $request->slug,
            'is_active' => $request->is_active ?? true,
        ]);

        // Ensure you are passing the file object, not an array or string
        if ($request->hasFile('img')) {
            $this->repo->addImage($category, $request->file('img'));
        }

        return $category->fresh();
    });
}
}
