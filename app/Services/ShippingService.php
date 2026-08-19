<?php

namespace App\Services;

class ShippingService
{
    /**
     * Calculate shipping charge based on order subtotal.
     * Free shipping for subtotal >= 1000, otherwise 100.
     *
     * @param float $subtotal
     * @return float
     */
    public static function calculateCharge(float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0.00;
        }

        return $subtotal >= 1000.00 ? 0.00 : 100.00;
    }
}
