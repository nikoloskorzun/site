
<?php

    $authToken = $_COOKIE['auth_token'] ?? null;

    
    $sql1 = "SELECT * FROM users WHERE auth_token = :auth_token";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute([':auth_token' => $authToken]);
    $user1 = $stmt1->fetch(PDO::FETCH_ASSOC);



    if($user1)    
{
    $_SESSION["id"] = $user1["id"];
    $_SESSION["username"] = $user1["username"];
    $_SESSION["name"] = $user1["name"];
    $_SESSION["email"] = $user1["email"];
    $_SESSION["phone"] = $user1["phone"];
    $_SESSION["auth_token"] = $user1["auth_token"];
    $_SESSION["access_rights"] = $user1["access_rights"];

}