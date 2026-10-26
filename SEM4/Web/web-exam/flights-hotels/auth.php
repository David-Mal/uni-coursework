<?php
session_start();

function requireSession() {
    if (!isset($_SESSION['person']) || !isset($_SESSION['res_date']) || !isset($_SESSION['res_city'])) {
        header('Location: index.php');
        exit();
    }
}
?>