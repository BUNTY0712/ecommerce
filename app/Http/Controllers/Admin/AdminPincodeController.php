<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pincode;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminPincodeController extends Controller
{
    public function index(Request $request)
    {
        $deliveryMode = Setting::get('delivery_mode', 'all');
        $restrictedMessage = Setting::get('delivery_restricted_message', 'Sorry, delivery is not available at this pincode.');

        $query = Pincode::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('pincode', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('area_name', 'like', "%{$search}%");
        }

        $pincodes = $query->latest()->paginate(20);
        $totalPincodes = Pincode::count();
        $activePincodes = Pincode::where('is_active', true)->count();

        return view('admin.pincodes.index', compact(
            'deliveryMode',
            'restrictedMessage',
            'pincodes',
            'totalPincodes',
            'activePincodes'
        ));
    }

    public function updateMode(Request $request)
    {
        $request->validate([
            'delivery_mode' => 'required|in:all,restricted',
            'delivery_restricted_message' => 'nullable|string|max:255',
        ]);

        Setting::set('delivery_mode', $request->input('delivery_mode'));
        if ($request->has('delivery_restricted_message')) {
            Setting::set('delivery_restricted_message', $request->input('delivery_restricted_message'));
        }

        $modeLabel = $request->input('delivery_mode') === 'all' 
            ? 'All India Delivery (No restriction)' 
            : 'Restricted Local Pincodes Only';

        return redirect()->route('admin.pincodes.index')->with('success', "Delivery mode updated to: {$modeLabel}");
    }

    public function store(Request $request)
    {
        $request->validate([
            'pincode' => 'required|string|max:20|unique:pincodes,pincode',
            'city' => 'nullable|string|max:100',
            'area_name' => 'nullable|string|max:150',
        ]);

        Pincode::create([
            'pincode' => trim($request->input('pincode')),
            'city' => $request->input('city'),
            'area_name' => $request->input('area_name'),
            'is_active' => true,
        ]);

        return redirect()->route('admin.pincodes.index')->with('success', 'Pincode added successfully.');
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'bulk_pincodes' => 'required|string',
            'bulk_city' => 'nullable|string|max:100',
        ]);

        $rawInput = $request->input('bulk_pincodes');
        $city = $request->input('bulk_city');

        // Split by comma, newline, space or tab
        $tokens = preg_split('/[\s,\n\r]+/', $rawInput, -1, PREG_SPLIT_NO_EMPTY);
        $addedCount = 0;
        $skippedCount = 0;

        foreach ($tokens as $token) {
            $code = trim($token);
            if (empty($code)) continue;

            // Check if exists
            $exists = Pincode::where('pincode', $code)->exists();
            if (!$exists) {
                Pincode::create([
                    'pincode' => $code,
                    'city' => $city,
                    'is_active' => true,
                ]);
                $addedCount++;
            } else {
                $skippedCount++;
            }
        }

        return redirect()->route('admin.pincodes.index')->with('success', "Bulk import completed! Added {$addedCount} new pincodes. ({$skippedCount} duplicates skipped)");
    }

    public function toggle(Pincode $pincode)
    {
        $pincode->update(['is_active' => !$pincode->is_active]);

        $status = $pincode->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.pincodes.index')->with('success', "Pincode {$pincode->pincode} has been {$status}.");
    }

    public function destroy(Pincode $pincode)
    {
        $code = $pincode->pincode;
        $pincode->delete();

        return redirect()->route('admin.pincodes.index')->with('success', "Pincode {$code} deleted successfully.");
    }

    public function destroyAll()
    {
        Pincode::truncate();

        return redirect()->route('admin.pincodes.index')->with('success', 'All pincodes have been removed.');
    }
}
