<?php

session_start();

if (!isset($_SESSION['nurse_id'])) {
    header("Location: src/nurse-login.php");
    exit();
}

$nurse_id = $_SESSION['nurse_id']; // For the variable session

?>