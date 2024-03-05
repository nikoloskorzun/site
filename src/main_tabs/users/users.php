<?php

session_start();
// Проверяем, что запрос был отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $authToken = $_COOKIE['auth_token'] ?? null;
    $ss = $_SESSION["auth_token"] ?? 1;
    if ($ss == $authToken) 
    {
if($_SESSION["access_rights"]!=1)
{
    echo json_encode(['status' =>"error", 'message' => 'only admin']);
    exit;
}
    
        // Получаем данные из тела запроса
        

        // Преобразуем JSON в массив PHP
        $data = $_POST["search"];
        
        // Проверяем, что данные были успешно преобразованы
        if ($data) 
        {
            

            // Здесь вы можете обработать данные, например, сохранить их в базе данных
            
            include '../../db_connect.php';
           
                    
            $sql = "SELECT * FROM users WHERE username LIKE :s";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':s', $data);
                
                

                
                $stmt->execute();
                
                $result = $stmt->fetchAll();
            
                //print_r($result);
                echo "<table><tr><th>Имя пользователя</th><th>username</th><th>Email</th><th>Права доступа</th></tr>";

                foreach ($result as $row) 
                {

                    echo "<tr>";
                    
                    echo "
                    <form>
    <input type='text' id='username' name='username' required>
    <input type='text' id='name' name='name' required>
    <input type='email' id='email' name='email' required>
    <input type='text' id='access_rights' name='access_rights' required>
    <input type='submit' value='Редактировать'>
</form>
                    
                    
                    
                    
                    
                    ";
                    
                    
                    echo "</tr>";


                } 
                echo "</table>";
                    
                

            
            
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

