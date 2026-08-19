<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    /**
     * Display list of categories.
     */
    public function index()
    {
        $categories = DB::table('categories')
            ->orderBy('name', 'asc')
            ->get();

        $productCounts = DB::table('products')
            ->select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        return view('admin.categories.index', compact('categories', 'productCounts'));
    }

    /**
     * Store new category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        $slug = Str::slug($request->input('name'));

        DB::table('categories')->insert([
            'name' => $request->input('name'),
            'slug' => $slug,
            'description' => $request->input('description'),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Delete category.
     */
    public function destroy($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        if ($category) {
            DB::table('categories')->where('id', $id)->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
        }

        return redirect()->route('admin.categories.index')->with('error', 'Category not found.');
    }
}
