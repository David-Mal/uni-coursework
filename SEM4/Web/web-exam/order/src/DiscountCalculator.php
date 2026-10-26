<?php

class DiscountCalculator
{
    private CategoryExtractor $categoryExtractor;

    public function __construct(CategoryExtractor $categoryExtractor)
    {
        $this->categoryExtractor = $categoryExtractor;
    }

    /**
     * @param array<int, array{name: string, price: float|int|string}> $products
     * @return array{subtotal: float, discountPercent: float, total: float}
     */
    public function calculate(array $products): array
    {
        $subtotal = 0.0;
        $categories = [];

        foreach ($products as $product) {
            $subtotal += (float) $product['price'];
            $category = $this->categoryExtractor->extract($product['name']);
            $categories[$category] = ($categories[$category] ?? 0) + 1;
        }

        $discountPercent = 0.0;

        if (count($products) >= 3) {
            $discountPercent += 10.0;
        }

        foreach ($categories as $count) {
            if ($count >= 2) {
                $discountPercent += 5.0;
                break;
            }
        }

        $total = $subtotal * (1 - $discountPercent / 100);

        return [
            'subtotal' => round($subtotal, 2),
            'discountPercent' => $discountPercent,
            'total' => round($total, 2),
        ];
    }
}
