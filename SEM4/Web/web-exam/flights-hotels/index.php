<?php
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['person'] = trim($_POST['person'] ?? '');
    $_SESSION['res_date'] = trim($_POST['res_date'] ?? '');
    $_SESSION['res_city'] = trim($_POST['res_city'] ?? '');
    
    // Inițializăm un array gol în care vom salva ID-urile rezervărilor doar din această sesiune
    $_SESSION['current_session_reservations'] = [];
    
    if ($_SESSION['person'] !== '' && $_SESSION['res_date'] !== '' && $_SESSION['res_city'] !== '') {
        header('Location: flights.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Begin Reservation</title></head>
<body>
    <h2>Start Concert Reservation</h2>
    <form method="POST">
        <label>Your Name:</label><br>
        <input type="text" name="person" required><br><br>
        
        <label>Date (YYYY-MM-DD):</label><br>
        <input type="date" name="res_date" required><br><br>
        
        <label>Destination City:</label><br>
        <input type="text" name="res_city" required><br><br>
        
        <button type="submit">Begin Reservation</button>
    </form>
</body>
</html>