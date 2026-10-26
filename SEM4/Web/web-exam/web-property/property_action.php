<?php
require_once 'auth.php';
require_once 'db.php';

requireLogin();

$address = trim($_POST['address'] ?? '');
$description = trim($_POST['description'] ?? '');


try {
    $conn->beginTransaction();
    $stmt = $conn->prepare('SELECT id FROM Property WHERE address = :address');
    $stmt->execute([':address' => $address]);
    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($property)
        $propertyId = (int)$property['id'];
    else {
        $insertStmt = conn -> prepare('INSERT INTO Property(address, description) VALUES(:address, :description)');
        $insertStmt->execute([
            ':address' => $address,
            ':description' => $description
        ]);
        $propertyId = (int)conn->lastInsertId();
    }

    $checkStmt = $conn->prepare('SELECT id FROM UserToProperties WHERE idUser = :idUser AND idProperty = :idProperty');
    $checkStmt->execute([
        ':idUser' => $_SESSION['user_id'],
        ':idProperty' => $propertyId
    ]); 

    if ($checkStmt -> fetch()) {
        $conn -> rollBack();
        header('Location: index.php?error=already_owned');
        exit();
    }

    $linkStmt = $conn->prepare('INSERT INTO UserToProperties(idUser, idProperty) VALUES (:userId, :propertyId)');
    $linkStmt->execute([
        ':userId' => $_SESSION['user_id'],
        ':propertyId' => $propertyId
    ]);
    $conn->commit();
    header('Location: index.php?success=1');
    exit();

} catch(Exception $e) {
    $conn->rollBack();
    die("Eroare la salvare: ".$e->getMessage());
}

?>