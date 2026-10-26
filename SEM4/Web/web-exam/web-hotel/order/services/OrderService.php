<?php

require_once __DIR__ . '/ProductService.php';
require_once __DIR__ . '/DiscountService.php';

class OrderService
{
    private PDO $conn;
    private DiscountService $discountService;

    public function __construct(PDO $conn, DiscountService $discountService)
    {
        $this->conn = $conn;
        $this->discountService = $discountService;
    }

    public function getLastOrders(int $userId, int $limit = 3): array
    {
        $stmt = $this->conn->prepare('
            SELECT id, userID, totalPrice
            FROM `Order`
            WHERE userID = :userId
            ORDER BY id DESC
            LIMIT :limit
        ');
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoriesForOrder(int $orderId): array
    {
        $stmt = $this->conn->prepare('
            SELECT p.name
            FROM OrderItem oi
            JOIN Product p ON oi.productID = p.id
            WHERE oi.orderID = :orderId
        ');
        $stmt->execute([':orderId' => $orderId]);

        $categories = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $categories[] = ProductService::extractCategory($row['name']);
        }

        return array_values(array_unique($categories));
    }

    public function shouldWarnDiversification(int $userId, string $category): bool
    {
        $lastOrders = $this->getLastOrders($userId, 3);

        if (count($lastOrders) < 3) {
            return false;
        }

        foreach ($lastOrders as $order) {
            $categories = $this->getCategoriesForOrder((int) $order['id']);
            if (!in_array($category, $categories, true)) {
                return false;
            }
        }

        return true;
    }

    public function confirmOrder(int $userId, array $cartItems): array
    {
        if (count($cartItems) === 0) {
            return ['success' => false, 'message' => 'Your order is empty.'];
        }

        $pricing = $this->discountService->calculateTotals($cartItems);

        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare('
                INSERT INTO `Order` (userID, totalPrice)
                VALUES (:userId, :totalPrice)
            ');
            $stmt->execute([
                ':userId' => $userId,
                ':totalPrice' => $pricing['total'],
            ]);
            $orderId = (int) $this->conn->lastInsertId();

            $itemStmt = $this->conn->prepare('
                INSERT INTO OrderItem (orderID, productID)
                VALUES (:orderId, :productId)
            ');

            foreach ($cartItems as $item) {
                $itemStmt->execute([
                    ':orderId' => $orderId,
                    ':productId' => (int) $item['id'],
                ]);
            }

            $this->conn->commit();

            return [
                'success' => true,
                'message' => 'Order confirmed. Final total: ' . number_format($pricing['total'], 2),
                'orderId' => $orderId,
                'pricing' => $pricing,
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => 'Could not save the order.'];
        }
    }
}
