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



if ($_SERVER['REQUEST_METHOD'] === 'POST') {



include '../db_connect.php';
$data = $_POST['text'];

$data = json_decode($data, true);
// Получение данных из POST-запроса




$check_username = "SELECT * FROM users WHERE username = :username";
$stmt = $conn->prepare($check_username);
$stmt->execute([':username' => $data['username']]);

if ($stmt->rowCount() > 0) 
{
    echo json_encode(array("status" => "error", "message" => "Username already exists."));
    exit;
}



$auth_token = generate_authtoken();


setcookie('auth_token', $auth_token, [
    'expires' => time() +  3600, // Установка времени истечения срока действия cookie
    'path' => '/', // Доступ к cookie на всем сайте
    'secure' => true, // Только через HTTPS
    'httponly' => true, // Только для HTTP, недоступно для JavaScript
    'samesite' => 'Strict', // Защита от CSRF-атак
]);


$sql = "INSERT INTO users (email, username, password_hash, auth_token) VALUES (:email, :username, :password_hash, :auth_token)";
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':email' => $data['email'],
    ':username' => $data['username'],
    ':password_hash' => $data['password'],
    ':auth_token' => $auth_token
]);

if ($stmt->rowCount() > 0) 
{
    include '../add_session_data.php';


    

    include "../mail_features/send_email.php";

    send_email($data['email'], "Welcome", "Спасибо что зарегистрировались на на нашем портале.", 'from@example.com');



    

    echo json_encode(array("status" => "success", "message" => "New record created successfully"));
} 
else 
{
    echo json_encode(array("status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->errorInfo()[2]));
}






}