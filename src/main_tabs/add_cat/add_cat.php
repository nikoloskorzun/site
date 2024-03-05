<?php
session_start();

include '../../db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

    $authToken = $_COOKIE['auth_token'] ?? null;
    $ss = $_SESSION["auth_token"] ?? 1;
    if ($ss == $authToken) 
{
    if($_SESSION["access_rights"] > 2)
    {
        echo json_encode(array("status" => "error", "message" =>"У вас недостаточно прав на просмотр этой страницы"));   
        exit;
    }

    // Получение данных из формы
    $name = $_POST["name"];
    $breed = $_POST["breed"];
    $birthdate = $_POST["birthdate"];
    $gender = $_POST["gender"];
    $image = $_FILES["image"];

    // Проверка изображения
    if ($image["error"] == UPLOAD_ERR_OK) {
        $imageData = file_get_contents($image["tmp_name"]);
    } else {
        echo json_encode(array("status" => "error", "message" =>"image"));
        exit;
    }

    // Подготовка SQL-запроса
    try {
        $stmt = $conn->prepare("INSERT INTO Cats (Name, Breed_ID, Owner_ID, Date_of_Birth, gender, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $breed, $_SESSION["id"], $birthdate, $gender, $imageData]);
        echo json_encode(array("status" => "success", "message" =>"cat added"));

    } catch(PDOException $e) {
        echo json_encode(array("status" => "error", "message" =>$e->getMessage()));

    }

}
}