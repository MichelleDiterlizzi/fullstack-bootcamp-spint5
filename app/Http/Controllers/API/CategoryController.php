<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Event;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Categories retrieved successfully',
            'data' => $categories,
        ], 200);
    }

    public function show(Category $category): JsonResponse
    {
        $events = Event::where('category_id', $category->id)->get();

        return response()->json([
            'category' => $category,
            'events' => $events,
        ]);
    }
}
