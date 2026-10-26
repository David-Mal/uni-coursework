<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'examdb');

define('T_USER', 'User');
define('TBL_CLASS', 'Class');
define('T_BOOKING', 'Booking');
define('T_WAITLIST', 'WaitList');
define('C_USER_ID', 'id');
define('C_USER_USERNAME', 'username');
define('C_USER_MEMBERSHIP', 'membershipType');
define('C_CLASS_ID', 'id');
define('C_CLASS_NAME', 'className');
define('C_CLASS_INSTRNAME', 'instructorName');
define('C_CLASS_DATE', 'classDate');
define('C_CLASS_MAXCAP', 'maxCapacity');
define('C_BOOKING_ID', 'id');
define('C_BOOKING_USERID', 'userId');
define('C_BOOKING_CLASSID', 'classId');
define('C_BOOKING_BOOKEDAT', 'bookedAt');
define('C_BOOKING_CANCELLED', 'cancelled');
define('C_WAITLIST_ID', 'id');
define('C_WAITLIST_USERID', 'userId');
define('C_WAITLIST_CLASSID', 'classId');
define('C_WAITLIST_ADDEDAT', 'addedAt');

session_start();

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

$me = $_SESSION['username'] ?? '';
$userId = $_SESSION['user_id'] ?? null;
$membershipType = $_SESSION['membership_type'] ?? '';
