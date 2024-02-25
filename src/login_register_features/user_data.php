<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET')
{



    $authToken = $_COOKIE['auth_token'] ?? null;

// Проверяем, что токен аутентификации существует
if ($authToken) 
{   
    include '../db_connect.php';
    $get_username = "SELECT * FROM users WHERE auth_token = '$authToken'";
    $result = $conn->query($get_username);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        echo json_encode(['status' =>  "success", 'email' => $user['email'], 'username' => $user['username']]);

        

        exit;
    }
       else
       {
        http_response_code(401);
            echo json_encode(['status' => "error", 'message' =>  "Не действительный auth token"]);
       } 
    }
    else 
        {
            
            http_response_code(401);
            echo json_encode(['status' => "error", 'message' => 'Не вошел']);
        }



}
else 
{
    // Если запрос не является GET-запросом, отправляем ошибку
    http_response_code(405);
    echo json_encode(['status' => "error", 'message' => 'Метод не поддерживается.']);
}


