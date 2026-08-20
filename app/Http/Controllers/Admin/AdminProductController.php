<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    /**
     * Display product catalog list.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $categoryId = $request->input('category', '');

        $query = DB::table('products')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->select('products.*', 'categories.name as category_name');

        if (!empty($search)) {
            $query->where('products.name', 'like', '%' . $search . '%');
        }

        if (!empty($categoryId)) {
            $query->where('products.category_id', $categoryId);
        }

        $products = $query->orderBy('products.id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $categories = DB::table('categories')->orderBy('name', 'asc')->get();

        return view('admin.products.index', compact('products', 'categories', 'search', 'categoryId'));
    }

    /**
     * Show create product form.
     */
    public function create()
    {
        $categories = DB::table('categories')->orderBy('name', 'asc')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store newly created product in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->input('name')) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $filename, 'public');
            $imagePath = 'products/' . $filename;
        }

        $slug = Str::slug($request->input('name')) . '-' . rand(100, 999);

        $productId = DB::table('products')->insertGetId([
            'category_id' => $request->input('category_id'),
            'name' => $request->input('name'),
            'slug' => $slug,
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'image' => $imagePath,
            'price' => $request->input('price'),
            'discount_price' => $request->input('discount_price'),
            'stock' => $request->input('stock'),
            'status' => $request->input('status'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Process multiple gallery images upload
        if ($request->hasFile('images')) {
            $sortOrder = 1;
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . Str::slug($request->input('name')) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('products', $filename, 'public');
                    $galleryPath = 'products/' . $filename;

                    DB::table('product_images')->insert([
                        'product_id' => $productId,
                        'image_path' => $galleryPath,
                        'sort_order' => $sortOrder++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Set primary image if not uploaded separately
                    if (empty($imagePath)) {
                        $imagePath = $galleryPath;
                        DB::table('products')->where('id', $productId)->update(['image' => $imagePath]);
                    }
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully with image gallery!');
    }

    /**
     * Show edit product form.
     */
    public function edit($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        if (!$product) {
            abort(404, 'Product not found');
        }

        $categories = DB::table('categories')->orderBy('name', 'asc')->get();
        $galleryImages = DB::table('product_images')
            ->where('product_id', $id)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('admin.products.edit', compact('product', 'categories', 'galleryImages'));
    }

    /**
     * Update existing product.
     */
    public function update(Request $request, $id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $updateData = [
            'category_id' => $request->input('category_id'),
            'name' => $request->input('name'),
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'discount_price' => $request->input('discount_price'),
            'stock' => $request->input('stock'),
            'status' => $request->input('status'),
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->input('name')) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $filename, 'public');
            $updateData['image'] = 'products/' . $filename;
        }

        DB::table('products')->where('id', $id)->update($updateData);

        // Process additional gallery images upload
        if ($request->hasFile('images')) {
            $lastOrder = DB::table('product_images')->where('product_id', $id)->max('sort_order') ?? 0;
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . Str::slug($request->input('name')) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('products', $filename, 'public');
                    $galleryPath = 'products/' . $filename;

                    DB::table('product_images')->insert([
                        'product_id' => $id,
                        'image_path' => $galleryPath,
                        'sort_order' => ++$lastOrder,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if (empty($product->image) && empty($updateData['image'])) {
                        DB::table('products')->where('id', $id)->update(['image' => $galleryPath]);
                    }
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Delete gallery image.
     */
    public function destroyImage($productId, $imageId)
    {
        $image = DB::table('product_images')->where('id', $imageId)->where('product_id', $productId)->first();
        if ($image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            DB::table('product_images')->where('id', $imageId)->delete();
            return redirect()->back()->with('success', 'Gallery image deleted successfully!');
        }

        return redirect()->back()->with('error', 'Image not found.');
    }

    /**
     * Delete product.
     */
    public function destroy($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        if ($product) {
            // Delete product gallery images from disk
            $galleryImages = DB::table('product_images')->where('product_id', $id)->get();
            foreach ($galleryImages as $img) {
                if (Storage::disk('public')->exists($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }
            DB::table('products')->where('id', $id)->delete();
            return redirect()->route('admin.products.index')->with('success', 'Product and images deleted successfully!');
        }

        return redirect()->route('admin.products.index')->with('error', 'Product not found.');
    }
}
