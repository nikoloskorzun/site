<?php
$servername = "mysql";
$username = "user";
$password = "userpassword";
$dbname = "mydatabase";

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");
// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
