<?php

session_start();
// Проверяем, что запрос был отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') 
{
    $authToken = $_COOKIE['auth_token'] ?? null;
    $ss = $_SESSION["auth_token"] ?? 1;
    if ($ss == $authToken) 
    {

    
        // Получаем данные из тела запроса
        

        // Преобразуем JSON в массив PHP
        $data = $_GET["bid_cat_id"];
        
        // Проверяем, что данные были успешно преобразованы
        if ($data) 
        {
            

            // Здесь вы можете обработать данные, например, сохранить их в базе данных
            
            include '../../db_connect.php';
           
                    
                $sql = "SELECT Cats.*
                FROM Cats
                JOIN to_vet ON Cats.ID = to_vet.id_cat
                WHERE to_vet.id = :id";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $data);
                
                

                
                $stmt->execute();
                
                $result = $stmt->fetchAll();
            
                //print_r($result);

                if ($result[0])
                {
                    if ($result[0]['image'] === null )
                    {
                        $imageData = base64_encode(file_get_contents('../../default_images/cat-default.png'));
                    }
                    else
                    {
                        $imageData = base64_encode($result[0]["image"]);
                    }

                    echo json_encode(['status' =>"success", 'message' => json_encode(["Name" => $result[0]["Name"], "id" => $result[0]["ID"],"image" => $imageData])]);
                }

            
            
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

