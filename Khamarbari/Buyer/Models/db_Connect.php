<?php
$conn = mysqli_connect("localhost", "root", "", "khamarbaridb");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>