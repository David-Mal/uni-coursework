<?php
require_once 'auth.php';
require_once 'db.php';
requireSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['current_session_reservations'])) {
    
    try {
        $conn->beginTransaction();

        // Parcurgem toate ID-urile rezervărilor făcute în sesiunea curentă
        foreach ($_SESSION['current_session_reservations'] as $resId) {
            
            // 1. Aflăm ce tip de rezervare este și ce resursă blochează
            $stmt = $conn->prepare("SELECT type, idReservedResource FROM Reservations WHERE id = :resId");
            $stmt->execute([':resId' => $resId]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($reservation) {
                // 2. Punem locurile/camerele la loc
                if ($reservation['type'] === 'Flight') {
                    $upd = $conn->prepare("UPDATE Flights SET availableSeats = availableSeats + 1 WHERE flightID = :id");
                } else {
                    $upd = $conn->prepare("UPDATE Hotels SET availableRooms = availableRooms + 1 WHERE hotelID = :id");
                }
                $upd->execute([':id' => $reservation['idReservedResource']]);

                // 3. Ștergem rezervarea
                $del = $conn->prepare("DELETE FROM Reservations WHERE id = :resId");
                $del->execute([':resId' => $resId]);
            }
        }

        // Golim istoricul sesiunii după anulare
        $_SESSION['current_session_reservations'] = [];

        $conn->commit();
        header('Location: flights.php?msg=cancelled');
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        die("Cancellation failed: " . $e->getMessage());
    }
} else {
    // Dacă array-ul e gol, doar dăm redirect (nu are ce anula)
    header('Location: flights.php');
}
?>