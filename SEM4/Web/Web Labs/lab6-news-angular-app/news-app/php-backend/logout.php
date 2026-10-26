<?php
session_start();
require 'cors.php';

session_destroy();
header('Content-Type: application/json');
echo json_encode(['success' => true]);
