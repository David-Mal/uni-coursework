<?php

require_once __DIR__ . '/bootstrap.php';

$user = requireLogin();

if (($_SESSION['cart'] ?? []) === []) {
    header('Location: order.php');
    exit;
}

$db = Database::getConnection();
$productRepository = new ProductRepository($db);
$orderRepository = new OrderRepository($db);
$categoryExtractor = new CategoryExtractor();
$discountCalculator = new DiscountCalculator($categoryExtractor);

$cartProducts = $productRepository->findByIds($_SESSION['cart']);
$pricing = $discountCalculator->calculate($cartProducts);

$orderId = $orderRepository->create(
    (int) $user['id'],
    $pricing['total'],
    $_SESSION['cart']
);

$_SESSION['cart'] = [];

renderHeader('Order Confirmed');
?>

<h1>Order Confirmed</h1>
<p>Thank you, <?= htmlspecialchars($user['username']) ?>!</p>

<div class="success">
    <p><strong>Order #<?= $orderId ?></strong> has been saved.</p>
    <p><strong>Subtotal:</strong> $<?= number_format($pricing['subtotal'], 2) ?></p>
    <?php if ($pricing['discountPercent'] > 0): ?>
        <p><strong>Discount applied:</strong> <?= number_format($pricing['discountPercent'], 0) ?>%</p>
    <?php endif; ?>
    <p><strong>Final total:</strong> $<?= number_format($pricing['total'], 2) ?></p>
</div>

<h2>Items</h2>
<table>
    <tr><th>Product</th><th>Price</th></tr>
    <?php foreach ($cartProducts as $product): ?>
        <tr>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td>$<?= number_format((float) $product['price'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p><a href="order.php">Start a new order</a></p>

<?php renderFooter(); ?>
