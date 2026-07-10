<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = $request->user()->categories;

        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request) 
    {
        $category = $request->user()->categories()->create([
            'name' => $request->name,
        ]);

        return new CategoryResource($category);
    }

    public function destroy(Request $request, Category $category) 
    {
        if ($category->user_id !== $request->user()->id) 
            {
                return response()->json(['message' => 'Forbidden'], 403);
            }

        $category->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
