<?php
require_once 'auth.php';
require_once 'db.php';
requireSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type']; // 'Flight' or 'Hotel'
    $resourceId = (int)$_POST['resource_id'];
    $person = $_SESSION['person'];

    try {
        $conn->beginTransaction();

        if ($type === 'Flight') {
            $updateStmt = $conn->prepare("UPDATE Flights SET availableSeats = availableSeats - 1 WHERE flightID = :id AND availableSeats > 0");
        } else {
            $updateStmt = $conn->prepare("UPDATE Hotels SET availableRooms = availableRooms - 1 WHERE hotelID = :id AND availableRooms > 0");
        }
        
        $updateStmt->execute([':id' => $resourceId]);

        // Verificăm dacă s-a modificat un rând (să nu reserveze dacă s-au terminat locurile între timp)
        if ($updateStmt->rowCount() > 0) {
            $insertStmt = $conn->prepare("INSERT INTO Reservations (person, type, idReservedResource) VALUES (:person, :type, :resId)");
            $insertStmt->execute([
                ':person' => $person,
                ':type' => $type,
                ':resId' => $resourceId
            ]);

            // Salvează ID-ul rezervării pentru a-l putea anula mai târziu
            $_SESSION['current_session_reservations'][] = $conn->lastInsertId();
        }

        $conn->commit();
        
        // Redirect înapoi de unde a venit
        $redirectUrl = ($type === 'Flight') ? 'flights.php?msg=booked' : 'hotels.php?msg=booked';
        header("Location: $redirectUrl");
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        die("Booking failed: " . $e->getMessage());
    }
}
header('Location: index.php');