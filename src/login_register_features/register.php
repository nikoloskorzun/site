<?php

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
$email = mysqli_real_escape_string($conn, $data['email']);
$username = mysqli_real_escape_string($conn, $data['username']);
$password_hash = mysqli_real_escape_string($conn, $data['password']);

// Проверка на кооректность данных
if (empty($email) || empty($username) || empty($password_hash)) {
    echo json_encode(array("status" => "error", "message" => "All fields are required."));
    exit;
}


$check_username = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($check_username);

if ($result->num_rows > 0) {
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



// Подготовка SQL-запроса
$sql = "INSERT INTO users (email, username, password_hash, auth_token) VALUES ('$email', '$username', '$password_hash', '$auth_token')";

// Выполнение запроса
if ($conn->query($sql) === TRUE) 
{
    session_start();
    
    echo json_encode(array("status" => "success", "message" => "New record created successfully"));
}
else
{
    echo json_encode(array("status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error));
}




// Закрытие подключения
$conn->close();

}