<?php
/**
 * Single Responsibility: calculates room pricing based on hotel occupancy.
 *
 * Pricing tiers (applied to basePrice × nights):
 *   occupancy ≤ 50%  → ×1.00 (base price)
 *   occupancy ≤ 80%  → ×1.20 (+20%)
 *   occupancy  > 80%  → ×1.50 (+50%)
 */
class PricingService {

    /**
     * Returns the price multiplier given booked/total room counts.
     */
    public function getMultiplier(int $bookedRooms, int $totalRooms): float {
        if ($totalRooms === 0) return 1.0;
        $rate = $bookedRooms / $totalRooms;
        if ($rate > 0.80) return 1.50;
        if ($rate > 0.50) return 1.20;
        return 1.00;
    }

    /**
     * Returns the total price for the entire stay.
     */
    public function calculateTotalPrice(int $basePrice, float $multiplier, string $checkIn, string $checkOut): int {
        $nights = (new DateTime($checkOut))->diff(new DateTime($checkIn))->days;
        $nights = max($nights, 1);
        return (int) round($basePrice * $multiplier * $nights);
    }

    /**
     * Returns a human-readable label for the current pricing tier.
     */
    public function getTierLabel(float $multiplier): string {
        if ($multiplier >= 1.50) return 'High demand (+50%)';
        if ($multiplier >= 1.20) return 'Moderate demand (+20%)';
        return 'Low demand (base price)';
    }
}
