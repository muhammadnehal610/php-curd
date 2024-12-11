<?php
$severName = "localhost";
$userName = "root";
$password = "";
$dbName = "test";

$connectDB = mysqli_connect($severName, $userName, $password, $dbName);

if ($connectDB) {
    echo "hellow world";
} else {
    echo "error";
}
?>