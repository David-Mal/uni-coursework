<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'services/ProductService.php';
require_once 'services/DiscountService.php';
require_once 'services/OrderService.php';

requireLogin();
initCart();

$productService = new ProductService($conn);
$discountService = new DiscountService();
$orderService = new OrderService($conn, $discountService);

$products = $productService->getAllProducts();
$cart = $_SESSION['cart'];
$pricing = $discountService->calculateTotals($cart);
$lastOrders = $orderService->getLastOrders((int) $_SESSION['user_id'], 3);

$message = $_GET['message'] ?? '';
$success = ($_GET['success'] ?? '') === '1';
$warning = $_GET['warning'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order App</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 24px auto; padding: 0 16px; }
        nav { margin-bottom: 24px; }
        .message { padding: 10px; border-radius: 4px; margin-bottom: 16px; }
        .message.ok { background: #e8f5e9; color: #1b5e20; }
        .message.error { background: #ffebee; color: #b71c1c; }
        .warning { background: #fff8e1; color: #e65100; padding: 10px; border-radius: 4px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
        .summary { background: #fafafa; padding: 16px; border: 1px solid #ddd; }
        .discount { color: #2e7d32; }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <nav>
        <a href="logout.php">Logout</a>
    </nav>

    <?php if ($message): ?>
        <div class="message <?php echo $success ? 'ok' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($warning): ?>
        <div class="warning"><?php echo htmlspecialchars($warning); ?></div>
    <?php endif; ?>

    <h3>Available Products</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars(ProductService::extractCategory($product['name'])); ?></td>
                    <td><?php echo number_format((float) $product['price'], 2); ?></td>
                    <td>
                        <form method="POST" action="cart_action.php" style="margin:0;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="productId" value="<?php echo (int) $product['id']; ?>">
                            <button type="submit">Add to order</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Current Order (not saved yet)</h3>
    <?php if (count($cart) === 0): ?>
        <p>No products selected yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $index => $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars(ProductService::extractCategory($item['name'])); ?></td>
                        <td><?php echo number_format((float) $item['price'], 2); ?></td>
                        <td>
                            <form method="POST" action="cart_action.php" style="margin:0;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <button type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="summary">
            <p><strong>Subtotal:</strong> <?php echo number_format($pricing['subtotal'], 2); ?></p>

            <?php if ($pricing['discountPercent'] > 0): ?>
                <p class="discount">
                    <strong>Discount (<?php echo $pricing['discountPercent']; ?>%):</strong>
                    -<?php echo number_format($pricing['discountAmount'], 2); ?>
                </p>
                <ul>
                    <?php foreach ($pricing['appliedDiscounts'] as $rule): ?>
                        <li><?php echo htmlspecialchars($rule); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No discounts applied yet.</p>
            <?php endif; ?>

            <p><strong>Total after discounts:</strong> <?php echo number_format($pricing['total'], 2); ?></p>

            <form method="POST" action="cart_action.php">
                <input type="hidden" name="action" value="confirm">
                <button type="submit">Confirm order</button>
            </form>
        </div>
    <?php endif; ?>

    <h3>Your Last 3 Orders</h3>
    <?php if (count($lastOrders) === 0): ?>
        <p>No confirmed orders yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Price</th>
                    <th>Categories</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lastOrders as $order): ?>
                    <tr>
                        <td><?php echo (int) $order['id']; ?></td>
                        <td><?php echo number_format((float) $order['totalPrice'], 2); ?></td>
                        <td><?php echo htmlspecialchars(implode(', ', $orderService->getCategoriesForOrder((int) $order['id']))); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
