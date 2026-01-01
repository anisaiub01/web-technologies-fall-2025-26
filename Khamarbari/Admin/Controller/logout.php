<?php
session_start();

session_unset();
session_destroy();


// Back to home
header("Location: ../index.php");
exit;
?>