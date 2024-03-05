<?php




if ($_SERVER['REQUEST_METHOD'] === 'GET') 
{



    include '../db_connect.php';


    if (isset($_COOKIE['auth_token'])) 
    {
        $auth_token = $_COOKIE['auth_token'];

        // Подготовка SQL запроса
        $stmt = $conn->prepare("UPDATE users SET auth_token = NULL WHERE auth_token = :auth_token");

        // Привязка параметров
        $stmt->bindParam(':auth_token', $auth_token);

        // Выполнение запроса
        $stmt->execute();

        session_destroy();

    }
}