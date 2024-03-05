<?php
session_start();

function generate_authtoken($length =  32) 
{
    // Генерируем случайные байты
    $random_bytes = random_bytes($length);
    // Преобразуем байты в шестнадцатеричную строку
    $token = bin2hex($random_bytes);
    return $token;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{



    include '../db_connect.php';
    $data = $_POST['text'];

    $data = json_decode($data, true);


    // Проверка на кооректность данных
    if (empty($data['username']) || empty($data['password'])) 
    {
        echo json_encode(array("status" => "error", "message" => "All fields are required."));
        exit;
    }


    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':username' => $data['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user) 
    {
        
        $auth_token = generate_authtoken();

        if ($data['password'] == $user["password_hash"])
        {
            setcookie('auth_token', $auth_token, [
                'expires' => time() +  3600, // Установка времени истечения срока действия cookie
                'path' => '/', // Доступ к cookie на всем сайте
                'secure' => true, // Только через HTTPS
                'httponly' => true, // Только для HTTP, недоступно для JavaScript
                'samesite' => 'Strict', // Защита от CSRF-атак
            ]);

        
            $sql = "UPDATE users SET auth_token = :auth_token WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':auth_token' => $auth_token,
                ':username' => $user["username"]
            ]);




            if ($stmt->rowCount() > 0) 
            {
                include '../add_session_data.php';

                echo json_encode(array("status" => "success", "message" => "login success"));
            
            } 
            else 
            {
                echo json_encode(array("status" => "error", "message" => "Error: " . $conn->errorInfo()[2]));
            }


        }
        else
        {
            echo json_encode(array("status" => "error", "message" => "invalid password"));

        }

    }
    else
    {
        echo json_encode(array("status" => "error", "message" => "Username not found."));
    }
}