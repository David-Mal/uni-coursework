<?php

class CategoryExtractor
{
    public function extract(string $productName): string
    {
        $parts = explode('-', $productName, 2);

        return strtoupper(trim($parts[0]));
    }
}
