<?php
session_start();
session_destroy();
header('Location: login_step1.php');
exit();
