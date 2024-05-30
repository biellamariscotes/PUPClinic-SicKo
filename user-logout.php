<?php
session_start();
unset($_SESSION['patient_id']);

header("Location: login.php");
exit();
?>