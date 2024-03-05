<?php
session_start();

include '../../db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

    $authToken = $_COOKIE['auth_token'] ?? null;
    $ss = $_SESSION["auth_token"] ?? 1;
    if ($ss == $authToken) 
{
    if($_SESSION["access_rights"] != 3)
    {
        echo json_encode(array("status" => "error", "message" =>"У вас недостаточно прав на просмотр этой страницы"));   
        exit;
    }

    // Получение данных из формы
    $description = $_POST["description"];
    $weight = $_POST["weight"];
    $id = $_POST["cat_id"];
    $bid_cat_id = $_POST["bid_cat_id"];
    

    
        
    // Подготовка SQL-запроса
    try {
        $stmt = $conn->prepare("INSERT INTO MedicalRecords (Cat_ID,	Vet_ID,	Date_visit, Description, Weight) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id, $_SESSION["id"], date("y-m-d"), $description, floatval($weight)]);


        $stmt = $conn->prepare("DELETE FROM to_vet WHERE id = :id");
        $stmt->execute([":id" => $bid_cat_id]);
        echo json_encode(array("status" => "success", "message" =>"MedicalRecords added"));

    } catch(PDOException $e) {
        echo json_encode(array("status" => "error", "message" =>$e->getMessage()));

    }

}
}