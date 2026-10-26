<?php

class OrderRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return array<int, array<int, array{name: string, price: string}>>
     */
    public function getLastOrderProducts(int $userId, int $limit = 3): array
    {
        $stmt = $this->db->prepare(
            'SELECT o.id
             FROM `Order` o
             WHERE o.userID = ?
             ORDER BY o.id DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();

        $orders = $stmt->fetchAll();
        $result = [];

        foreach ($orders as $order) {
            $itemStmt = $this->db->prepare(
                'SELECT p.name, p.price
                 FROM OrderItem oi
                 JOIN Product p ON p.id = oi.productID
                 WHERE oi.orderID = ?'
            );
            $itemStmt->execute([$order['id']]);
            $result[] = $itemStmt->fetchAll();
        }

        return $result;
    }

    /**
     * @param int[] $productIds
     */
    public function create(int $userId, float $totalPrice, array $productIds): int
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare('INSERT INTO `Order` (userID, totalPrice) VALUES (?, ?)');
            $stmt->execute([$userId, $totalPrice]);
            $orderId = (int) $this->db->lastInsertId();

            $itemStmt = $this->db->prepare('INSERT INTO OrderItem (orderID, productID) VALUES (?, ?)');

            foreach ($productIds as $productId) {
                $itemStmt->execute([$orderId, $productId]);
            }

            $this->db->commit();

            return $orderId;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
