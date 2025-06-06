<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::paginate(10));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category->load('products'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Cannot delete category because it contains products'
                ], 422);
            }
            return response()->json([
                'message' => 'Failed to delete category'
            ], 500);
        }
    }
}
