<?php
class ProductService {
    private PDO $conn;
    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function getAllProducts(): array {
        $stmt = $this->conn->query('SELECT id, name, price FROM Product ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById(int $productId): ?array {
        $stmt = $this->conn->prepare('SELECT id, name, price FROM Product WHERE id = :id');
        $stmt->execute([':id' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        return $product ?: null;
    }

    public static function extractCategory(string $productName): string {
        $parts = explode('-', $productName, 2);
        return strtoupper(trim($parts[0]));
    }
}
?>