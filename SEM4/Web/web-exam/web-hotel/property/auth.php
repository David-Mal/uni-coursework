<?php

function requireLogin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}

function initSearchTracking(): void
{
    if (!isset($_SESSION['search_hits'])) {
        $_SESSION['search_hits'] = [];
    }
}

function recordSearchResults(array $properties): void
{
    initSearchTracking();

    foreach ($properties as $property) {
        $propertyId = (int) $property['id'];
        $_SESSION['search_hits'][$propertyId] = ($_SESSION['search_hits'][$propertyId] ?? 0) + 1;
    }
}
