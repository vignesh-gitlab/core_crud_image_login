<?php

$host = "localhost";
$user = "root";
$pass = "";
$database = "core_crud_image_test";

$conn = new mysqli($host, $user, $pass, $database);

if ($conn->connect_error) {
    die("Connection Failed" . $conn->connect_error);
}