<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * Display a listing of the authenticated user's categories.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $categories = $request->user()->categories;

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = $request->user()->categories()->create([
            'name' => $request->name,
        ]);

        return new CategoryResource($category);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Request $request, Category $category): JsonResponse
    {
        if ($category->user_id !== $request->user()->id) 
            {
                return response()->json(['message' => 'Forbidden'], 403);
            }

        $category->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
