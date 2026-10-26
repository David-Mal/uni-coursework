<?php

require_once __DIR__ . '/bootstrap.php';

$user = requireLogin();
$db = Database::getConnection();

$productRepository = new ProductRepository($db);
$orderRepository = new OrderRepository($db);
$categoryExtractor = new CategoryExtractor();
$discountCalculator = new DiscountCalculator($categoryExtractor);
$diversityChecker = new DiversityChecker($categoryExtractor);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['warnings'])) {
    $_SESSION['warnings'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $products = $productRepository->findByIds([$productId]);

        if ($products !== []) {
            $product = $products[0];
            $category = $categoryExtractor->extract($product['name']);
            $lastOrders = $orderRepository->getLastOrderProducts((int) $user['id'], 3);

            if ($diversityChecker->isCategoryInAllLastOrders($category, $lastOrders)) {
                $_SESSION['warnings'][] = sprintf(
                    'Warning: Category "%s" appears in all of your last 3 orders. Consider diversifying your products.',
                    $category
                );
            }

            $_SESSION['cart'][] = $productId;
        }
    }

    if ($action === 'remove') {
        $index = (int) ($_POST['index'] ?? -1);
        if (isset($_SESSION['cart'][$index])) {
            array_splice($_SESSION['cart'], $index, 1);
        }
    }

    if ($action === 'confirm') {
        if ($_SESSION['cart'] === []) {
            $_SESSION['confirm_error'] = 'Your order is empty.';
        } else {
            header('Location: confirm.php');
            exit;
        }
    }

    header('Location: order.php');
    exit;
}

$products = $productRepository->findAll();
$cartProducts = $productRepository->findByIds($_SESSION['cart']);
$pricing = $discountCalculator->calculate($cartProducts);
$warnings = $_SESSION['warnings'];
$_SESSION['warnings'] = [];
$confirmError = $_SESSION['confirm_error'] ?? '';
unset($_SESSION['confirm_error']);

renderHeader('Build Order');
?>

<h1>Build Order</h1>
<p>Logged in as: <strong><?= htmlspecialchars($user['username']) ?></strong>
   (<a href="logout.php">logout</a>)</p>

<?php if ($confirmError !== ''): ?>
    <div class="error"><?= htmlspecialchars($confirmError) ?></div>
<?php endif; ?>

<?php foreach ($warnings as $warning): ?>
    <div class="warning"><?= htmlspecialchars($warning) ?></div>
<?php endforeach; ?>

<h2>Available Products</h2>
<table>
    <tr><th>Name</th><th>Category</th><th>Price</th><th></th></tr>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= htmlspecialchars($categoryExtractor->extract($product['name'])) ?></td>
            <td>$<?= number_format((float) $product['price'], 2) ?></td>
            <td>
                <form method="post" style="display:inline">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                    <button type="submit">Add to order</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Current Order</h2>
<?php if ($cartProducts === []): ?>
    <p>No products selected yet.</p>
<?php else: ?>
    <table>
        <tr><th>Product</th><th>Price</th><th></th></tr>
        <?php foreach ($cartProducts as $index => $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>$<?= number_format((float) $product['price'], 2) ?></td>
                <td>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <button type="submit">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><strong>Subtotal:</strong> $<?= number_format($pricing['subtotal'], 2) ?></p>
    <?php if ($pricing['discountPercent'] > 0): ?>
        <p><strong>Discount:</strong> <?= number_format($pricing['discountPercent'], 0) ?>%</p>
    <?php endif; ?>
    <p><strong>Estimated total:</strong> $<?= number_format($pricing['total'], 2) ?></p>

    <form method="post">
        <input type="hidden" name="action" value="confirm">
        <button type="submit">Confirm Order</button>
    </form>
<?php endif; ?>

<?php renderFooter(); ?>
