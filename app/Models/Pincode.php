<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    use HasFactory;

    protected $fillable = [
        'pincode',
        'city',
        'area_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Check if a given pincode is serviceable based on delivery settings.
     */
    public static function isServiceable(string $pincode): bool
    {
        $cleanPincode = trim($pincode);
        if (empty($cleanPincode)) {
            return false;
        }

        $mode = Setting::get('delivery_mode', 'all');

        // All India mode allows any pincode
        if ($mode === 'all') {
            return true;
        }

        // Restricted mode: check active allowed pincodes
        return static::where('pincode', $cleanPincode)
            ->where('is_active', true)
            ->exists();
    }
}
