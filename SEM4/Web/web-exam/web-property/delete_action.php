<?php
require_once 'auth.php';
require_once 'db.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['property_id'])) {
    $propertyId = (int)$_POST['property_id'];
    $userId = $_SESSION['user_id'];

    try {
        $conn->beginTransaction();

        $deleteLinkStmt = $conn->prepare('DELETE FROM UserToProperties WHERE idUser = :userId AND idProperty = :propId');
        $deleteLinkStmt->execute([
            ':userId' => $userId,
            ':propId' => $propertyId
        ]);

        $checkStmt = $conn->prepare('SELECT COUNT(*) FROM UserToProperties WHERE idProperty = :propId');
        $checkStmt->execute([':propId' => $propertyId]);
        $remainingOwners = (int)$checkStmt->fetchColumn();

        if ($remainingOwners === 0) {
            $deletePropStmt = $conn->prepare('DELETE FROM Property WHERE id = :propId');
            $deletePropStmt->execute([':propId' => $propertyId]);
        }

        $conn->commit();
        header('Location: index.php?success=deleted');
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        header('Location: index.php?error=delete_failed');
        exit();
    }
}

header('Location: index.php');
exit();
?>