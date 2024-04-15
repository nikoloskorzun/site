<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{



    include '../../db_connect.php';
    $data = $_POST['text'];

    $data = json_decode($data, true);

    //verify $data !!!

    $authToken = $_COOKIE['auth_token'] ?? null;
    $ss = $_SESSION["auth_token"] ?? 1;
    if ($ss == $authToken) 
    {
        
        $sql = "UPDATE users SET username = :username, name = :name, email = :email, phone = :phone, cat_visible = :cat_visible WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id' => $_SESSION["id"],
            ':username' => $data["username"],
            ':name' => $data["name"],
            ':email' => $data["email"],
            ':phone' => $data["phone"],
            ':cat_visible' => $data["cat_visible"]
            
        ]);

        include '../../add_session_data.php';
        echo json_encode(array("status" => "success", "message" => json_encode(array("name" => $_SESSION["name"], "username" => $_SESSION["username"], "email" => $_SESSION["email"], "phone" => $_SESSION["phone"], "cat_visible" => $_SESSION["cat_visible"]))));


    }
    else
    {
        echo json_encode(array("status" => "error", "message" => "Not auth"));
    }

}