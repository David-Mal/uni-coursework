<?php

class DiversityChecker
{
    private CategoryExtractor $categoryExtractor;

    public function __construct(CategoryExtractor $categoryExtractor)
    {
        $this->categoryExtractor = $categoryExtractor;
    }

    /**
     * @param array<int, array{name: string}> $lastOrderProducts Products from each of the last orders
     */
    public function isCategoryInAllLastOrders(string $category, array $lastOrderProducts): bool
    {
        if (count($lastOrderProducts) < 3) {
            return false;
        }

        foreach ($lastOrderProducts as $orderProducts) {
            $orderCategories = [];

            foreach ($orderProducts as $product) {
                $orderCategories[] = $this->categoryExtractor->extract($product['name']);
            }

            if (!in_array($category, $orderCategories, true)) {
                return false;
            }
        }

        return true;
    }
}
