<?php
session_start();
unset($_SESSION['nurse_id']);

header("Location: nurse-login.php");
exit();
?>