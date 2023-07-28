<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addCategory(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $name = $request->input('name');
        $type = $request->input('type');

        $category = new Category();
        $category->category_name = $name;
        $category->type = $type;

        $user->categories()->save($category);

        return response()->json(['message' => 'Category added successfully'], 200);
    }

    public function getAllCategory($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $categories = $user->categories;

        return response()->json($categories, 200);
    }

    public function getCategoryById($userId, $categoryId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $category = $user->categories()->find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category, 200);
    }

    public function updateCategory(Request $request, $categoryId)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->type = $request->input('type');
        $category->category_name = $request->input('categoryName');
        $category->save();

        return response()->json(['message' => 'Category updated successfully'], 200);
    }

}
