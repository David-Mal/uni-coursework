<?php

session_start();

require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/CategoryExtractor.php';
require_once __DIR__ . '/src/DiscountCalculator.php';
require_once __DIR__ . '/src/DiversityChecker.php';
require_once __DIR__ . '/src/UserRepository.php';
require_once __DIR__ . '/src/ProductRepository.php';
require_once __DIR__ . '/src/OrderRepository.php';

function requireLogin(): array
{
    if (!isset($_SESSION['user'])) {
        header('Location: index.php');
        exit;
    }

    return $_SESSION['user'];
}

function renderHeader(string $title): void
{
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>' . htmlspecialchars($title) . '</title>';
    echo '<style>
        body { font-family: sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
        th, td { border: 1px solid #ccc; padding: 0.5rem; text-align: left; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 0.75rem; margin: 1rem 0; }
        .success { background: #d4edda; border: 1px solid #28a745; padding: 0.75rem; margin: 1rem 0; }
        .error { background: #f8d7da; border: 1px solid #dc3545; padding: 0.75rem; margin: 1rem 0; }
        button, input[type=submit] { padding: 0.4rem 0.8rem; cursor: pointer; }
    </style></head><body>';
}

function renderFooter(): void
{
    echo '</body></html>';
}
