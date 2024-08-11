<?php
$mysqli = new mysqli("localhost", "root", "new_password", "scandiweb");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Connected successfully!";
?>
