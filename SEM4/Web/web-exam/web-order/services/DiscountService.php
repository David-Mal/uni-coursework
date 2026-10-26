<?php
require_once __DIR__.'/ProductService.php';

class DiscountService {
    public function calculateTotals(array $cartItems): array {
        $subtotal = 0.0;
        $categoryCounts = [];

        foreach ($cartItems as $item) {
            $subtotal += (float) $item['price'];
            $category = ProductService::extractCategory($item['name']);
            $categoryCounts[$category] = ($categoryCounts[$category] ?? 0) + 1;
        }

        $discountPercent = 0;
        $appliedDiscount = [];

        if (count($cartItems) >= 3) {
            $discountPercent += 10;
            $appliedDiscount[] = '10% off for 3 or more products';
        }

        $hasCategoryPair = false;
        foreach ($categoryCounts as $count) {
            if ($count >= 2) {
                $hasCategoryPair = true;
                break;
            }
        }

        if ($hasCategoryPair) {
            $discountPercent += 5;
            $appliedDiscount[] = '5% off for 2+ products in the same category.';
        }
        $discountAmount = round($subtotal * ($discountPercent / 100), 2);
        $total = round($subtotal - $discountAmount, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'discountPercent' => $discountPercent,
            'discountAmount' => $discountAmount,
            'total' => $total,
            'appliedDiscount' => $appliedDiscount
        ];
    }
}
?>