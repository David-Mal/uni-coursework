<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'services/ProductService.php';
require_once 'services/DiscountService.php';
require_once 'services/OrderService.php';

requireLogin();
initCart();

$action = $_POST['action'] ?? '';
$userId = (int) $_SESSION['user_id'];

$productService = new ProductService($conn);
$orderService = new OrderService($conn, new DiscountService());

$message = '';
$success = '0';
$warning = '';

if ($action === 'add') {
    $productId = (int) ($_POST['productId'] ?? 0);
    $product = $productService->getProductById($productId);

    if ($product === null) {
        $message = 'Product not found.';
    } else {
        $category = ProductService::extractCategory($product['name']);

        if ($orderService->shouldWarnDiversification($userId, $category)) {
            $warning = 'Warning: category ' . $category
                . ' already appears in all of your last 3 orders. Consider diversifying your products.';
        }

        $_SESSION['cart'][] = [
            'id' => (int) $product['id'],
            'name' => $product['name'],
            'price' => (float) $product['price'],
        ];

        $message = 'Added ' . $product['name'] . ' to your order.';
        $success = '1';
    }
} elseif ($action === 'remove') {
    $index = (int) ($_POST['index'] ?? -1);

    if (isset($_SESSION['cart'][$index])) {
        $removed = $_SESSION['cart'][$index]['name'];
        array_splice($_SESSION['cart'], $index, 1);
        $message = 'Removed ' . $removed . ' from your order.';
        $success = '1';
    } else {
        $message = 'Could not remove that item.';
    }
} elseif ($action === 'confirm') {
    $result = $orderService->confirmOrder($userId, $_SESSION['cart']);
    $message = $result['message'];
    $success = $result['success'] ? '1' : '0';

    if ($result['success']) {
        $_SESSION['cart'] = [];
    }
} else {
    header('Location: index.php');
    exit();
}

$query = http_build_query([
    'message' => $message,
    'success' => $success,
    'warning' => $warning,
]);

header('Location: index.php?' . $query);
exit();
