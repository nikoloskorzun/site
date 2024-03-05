<?php

session_start();
// Проверяем, что запрос был отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $authToken = $_COOKIE['auth_token'] ?? null;
    $ss = $_SESSION["auth_token"] ?? 1;
    
    if ($ss == $authToken) 
    {

    
        // Получаем данные из тела запроса
        $json = file_get_contents('php://input');

        // Преобразуем JSON в массив PHP
        $data = json_decode($json, true);
        
        // Проверяем, что данные были успешно преобразованы
        if (is_array($data)) 
        {
            // Здесь вы можете обработать данные, например, сохранить их в базе данных
            
            include '../../db_connect.php';
            $temp = bin2hex(random_bytes(20));
            foreach ($data as $key => $value)
            {
                for ($i=0; $i < intval($value); $i++) 
                { 
                    
                $sql = "INSERT INTO to_vet (id_cat, bid_id) VALUES (:id, :bid_num)";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $key);
                
                $stmt->bindParam(':bid_num', $temp);

                
                $stmt->execute();
                }

            }

            
            echo json_encode(['status' =>"success", 'message' => 'Данные успешно получены и обработаны']);
        } else {
            // Если данные не могут быть преобразованы в массив, возвращаем ошибку
            http_response_code(400);
            echo json_encode(['status' =>"error", 'message' => 'Неверный формат данных']);
        }
    }
} 
else 
{
    // Если запрос не был отправлен методом POST, возвращаем ошибку
    http_response_code(405);
    echo json_encode(['status' =>"error", 'message' => 'Метод не поддерживается']);
}

