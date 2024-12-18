<?php
session_start();
header("Content-Type: application/json");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Expires: 0");

if (isset($_SESSION['username'])) {
    echo json_encode(['loggedIn' => true, 'username' => $_SESSION['username']]);
} else {
    echo json_encode(['loggedIn' => false]);
}
exit();
?>
