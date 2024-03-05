<?php
    session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{



    $authToken = $_COOKIE['auth_token'] ?? null;

// Проверяем, что токен аутентификации существует
    if ($authToken) 
    {   
        include '../db_connect.php';
        $get_username = "SELECT * FROM users WHERE auth_token = :authToken";
        $stmt = $conn->prepare($get_username);
        $stmt->bindParam(':authToken', $authToken);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) 
        {
            include '../add_session_data.php';

            echo json_encode(['status' => "success", 'email' => $user['email'], 'username' => $user['username'], 'access_rights' => $user['access_rights']]);
        } 
        else
        {
            http_response_code(401);
            echo json_encode(['status' => "error", 'message' => "Не действительный auth token"]);
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
    http_response_code(405);

    echo json_encode(['status' => "error", 'message' => 'Метод не поддерживается.']);
}

