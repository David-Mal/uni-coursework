<?php
require_once __DIR__ . '/auth.php';
startSession();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🏨 Hotel App</a>
        <?php if (isLoggedIn()): ?>
        <div class="d-flex gap-3 align-items-center ms-auto">
            <a class="nav-link text-white" href="rooms.php">Available Rooms</a>
            <a class="nav-link text-white" href="reservations.php">My Reservations</a>
            <a class="nav-link text-white" href="guests.php">Guest Count</a>
            <span class="text-secondary">|</span>
            <span class="text-white-50">👤 <?= htmlspecialchars(getCurrentUsername()) ?></span>
            <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
        </div>
        <?php endif; ?>
    </div>
</nav>
<div class="container pb-5">
