<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'services/PricingService.php';
require_once 'services/RoomService.php';
require_once 'services/ReservationService.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: rooms.php');
    exit();
}

$roomId = (int) ($_POST['roomId'] ?? 0);
$checkIn = trim($_POST['checkIn'] ?? '');
$checkOut = trim($_POST['checkOut'] ?? '');
$numberOfGuests = (int) ($_POST['numberOfGuests'] ?? 0);

$reservationService = new ReservationService($conn, new PricingService(), new RoomService($conn));
$result = $reservationService->createReservation(
    (int) $_SESSION['user_id'],
    $roomId,
    $checkIn,
    $checkOut,
    $numberOfGuests
);

$query = http_build_query([
    'checkIn' => $checkIn,
    'checkOut' => $checkOut,
    'message' => $result['message'],
    'success' => $result['success'] ? '1' : '0',
]);

header('Location: rooms.php?' . $query);
exit();
