<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminBannerController extends Controller
{
    /**
     * Display listing of all home page banners.
     */
    public function index()
    {
        $banners = DB::table('banners')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Store newly uploaded banner image(s) and metadata.
     */
    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required_without:image|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'badge_text' => 'nullable|string|max:100',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $uploadedCount = 0;
        $title = $request->input('title');
        $subtitle = $request->input('subtitle');
        $badgeText = $request->input('badge_text');
        $buttonText = $request->input('button_text');
        $buttonUrl = $request->input('button_url');
        $sortOrder = (int) $request->input('sort_order', 0);

        // Process multiple image upload array
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('banners', 'public');
                    DB::table('banners')->insert([
                        'title' => $title,
                        'subtitle' => $subtitle,
                        'badge_text' => $badgeText,
                        'button_text' => $buttonText,
                        'button_url' => $buttonUrl,
                        'image' => $path,
                        'sort_order' => $sortOrder,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $uploadedCount++;
                }
            }
        }

        // Process single image upload input if provided
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $path = $file->store('banners', 'public');
                DB::table('banners')->insert([
                    'title' => $title,
                    'subtitle' => $subtitle,
                    'badge_text' => $badgeText,
                    'button_text' => $buttonText,
                    'button_url' => $buttonUrl,
                    'image' => $path,
                    'sort_order' => $sortOrder,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $uploadedCount++;
            }
        }

        if ($uploadedCount > 0) {
            return redirect()->route('admin.banners.index')->with('success', "{$uploadedCount} banner image(s) uploaded successfully!");
        }

        return redirect()->back()->with('error', 'No valid banner images were uploaded.');
    }

    /**
     * Update an existing banner's content or image.
     */
    public function update(Request $request, $id)
    {
        $bannerId = (int) $id;
        $banner = DB::table('banners')->where('id', $bannerId)->first();

        if (!$banner) {
            return redirect()->back()->with('error', 'Banner not found.');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'badge_text' => 'nullable|string|max:100',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
        ]);

        $updateData = [
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
            'badge_text' => $request->input('badge_text'),
            'button_text' => $request->input('button_text'),
            'button_url' => $request->input('button_url'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            // Delete old file if exists
            if (!empty($banner->image) && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $updateData['image'] = $request->file('image')->store('banners', 'public');
        }

        DB::table('banners')->where('id', $bannerId)->update($updateData);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully!');
    }

    /**
     * Toggle active/inactive status of a banner.
     */
    public function toggleStatus($id)
    {
        $bannerId = (int) $id;
        $banner = DB::table('banners')->where('id', $bannerId)->first();

        if (!$banner) {
            return redirect()->back()->with('error', 'Banner not found.');
        }

        $newStatus = $banner->status == 1 ? 0 : 1;

        DB::table('banners')->where('id', $bannerId)->update([
            'status' => $newStatus,
            'updated_at' => now(),
        ]);

        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Banner #{$bannerId} has been {$statusText}.");
    }

    /**
     * Delete a banner.
     */
    public function destroy($id)
    {
        $bannerId = (int) $id;
        $banner = DB::table('banners')->where('id', $bannerId)->first();

        if (!$banner) {
            return redirect()->back()->with('error', 'Banner not found.');
        }

        if (!empty($banner->image) && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        DB::table('banners')->where('id', $bannerId)->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
