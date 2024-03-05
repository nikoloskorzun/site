<?php
include '../../db_connect.php';



$sql = "SELECT ID, Name, image FROM Cats";
$stmt = $conn->prepare($sql);
$stmt->execute();


$result = $stmt->fetchAll(PDO::FETCH_ASSOC);



$images = array();
if (!empty($result)) 
{
 // Вывод данных каждой строки
    foreach($result as $row) 
    {
        // Преобразование BLOB в изображение
        if ($row["image"] === null) {
            // Чтение файла default.jpg и преобразование его в строку base64
            $imageData = base64_encode(file_get_contents('../../default_images/cat-default.png'));
        } else {
            // Преобразование BLOB в изображение
            $imageData = base64_encode($row["image"]);
        }        $images[] = array('id' => $row["ID"], 'image_data' => $imageData, 'image_name' => $row["Name"]);
    }
} 
else 
{
    echo "0 results";
}

// Возвращение изображений в формате JSON
header('Content-Type: application/json');
echo json_encode($images);


