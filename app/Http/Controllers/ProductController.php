<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display product listing page with search, category filtering, and pagination.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $categoryId = $request->input('category');

        // Fetch categories using Query Builder
        $categories = DB::table('categories')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        // Query products strictly using Query Builder
        $query = DB::table('products')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->select(
                'products.*',
                'categories.name as category_name',
                'categories.slug as category_slug'
            )
            ->where('products.status', 1);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'like', '%' . $search . '%')
                  ->orWhere('products.short_description', 'like', '%' . $search . '%')
                  ->orWhere('products.description', 'like', '%' . $search . '%');
            });
        }

        if (!empty($categoryId)) {
            $query->where('products.category_id', $categoryId);
        }

        $products = $query->orderBy('products.id', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('products', 'categories', 'search', 'categoryId'));
    }

    /**
     * Display single product details page.
     */
    public function show($id)
    {
        // Query single product strictly using Query Builder
        $product = DB::table('products')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->select(
                'products.*',
                'categories.name as category_name',
                'categories.slug as category_slug'
            )
            ->where('products.id', $id)
            ->where('products.status', 1)
            ->first();

        if (!$product) {
            abort(404, 'Product not found');
        }

        // Fetch gallery images for multi-image display
        $galleryImages = DB::table('product_images')
            ->where('product_id', $id)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Fetch related products strictly using Query Builder
        $relatedProducts = DB::table('products')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'galleryImages', 'relatedProducts'));
    }
}
