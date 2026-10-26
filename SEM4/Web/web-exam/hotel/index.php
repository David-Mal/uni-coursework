<?php
require_once 'auth.php';
startSession();
header('Location: ' . (isLoggedIn() ? 'rooms.php' : 'login.php'));
exit;
