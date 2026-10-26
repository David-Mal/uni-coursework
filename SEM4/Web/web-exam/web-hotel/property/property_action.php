<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'services/PropertyService.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$action = $_POST['action'] ?? '';
$userId = (int) $_SESSION['user_id'];
$propertyService = new PropertyService($conn);

if ($action === 'add') {
    $propertyId = trim($_POST['propertyId'] ?? '');
    $selectedId = $propertyId !== '' ? (int) $propertyId : null;

    $result = $propertyService->addPropertyToUser(
        $userId,
        $selectedId,
        trim($_POST['address'] ?? ''),
        trim($_POST['description'] ?? '')
    );
} elseif ($action === 'delete') {
    $propertyId = (int) ($_POST['propertyId'] ?? 0);
    $result = $propertyService->deletePropertyForUser($userId, $propertyId);
} else {
    header('Location: index.php');
    exit();
}

$query = http_build_query([
    'message' => $result['message'],
    'success' => $result['success'] ? '1' : '0',
]);

header('Location: index.php?' . $query);
exit();
