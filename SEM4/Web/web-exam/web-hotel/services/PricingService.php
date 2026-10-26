<?php

class PricingService
{
    public function calculateTotalPrice(int $basePrice, int $bookedRooms, int $totalRooms, int $nights): int
    {
        if ($totalRooms === 0 || $nights <= 0) {
            return 0;
        }

        $ratio = $bookedRooms / $totalRooms;
        $multiplier = 1.0;

        if ($ratio > 0.8) {
            $multiplier = 1.5;
        } elseif ($ratio > 0.5) {
            $multiplier = 1.2;
        }

        return (int) round($basePrice * $multiplier * $nights);
    }
}
