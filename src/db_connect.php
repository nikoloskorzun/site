<?php
$servername = "mysql";
$username = "user";
$password = "userpassword";
$dbname = "mydatabase";



// Создание подключения с использованием PDO
try 
{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Установка режима ошибок PDO на исключения
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) 
{
    //$e->getMessage();
}