<?php
session_start();

// Ștergem absolut toate variabilele din sesiune
$_SESSION = array();

// Distrugem sesiunea complet
session_destroy();

// Redirect către pagina principală
header('Location: index.php');
exit();
?>