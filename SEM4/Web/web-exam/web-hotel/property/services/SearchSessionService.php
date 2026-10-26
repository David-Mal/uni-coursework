<?php

require_once __DIR__ . '/PropertyService.php';

class SearchSessionService
{
    private PropertyService $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function getMostPopularProperty(): ?array
    {
        if (empty($_SESSION['search_hits'])) {
            return null;
        }

        $maxHits = max($_SESSION['search_hits']);
        $topIds = array_keys(array_filter(
            $_SESSION['search_hits'],
            fn(int $hits): bool => $hits === $maxHits
        ));

        sort($topIds, SORT_NUMERIC);
        $propertyId = (int) $topIds[0];
        $property = $this->propertyService->getPropertyById($propertyId);

        if ($property === null) {
            return null;
        }

        $property['searchHits'] = $maxHits;
        return $property;
    }
}
