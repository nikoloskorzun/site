<?php




if ($_SERVER['REQUEST_METHOD'] === 'GET') {



include '../db_connect.php';


if (isset($_COOKIE['auth_token'])) {
    $auth_token = $_COOKIE['auth_token'];

    $auth_token = mysqli_real_escape_string($conn, $auth_token);
    $logout = "UPDATE users SET auth_token = NULL WHERE auth_token = '$auth_token';";
    $result = $conn->query($logout);

}


$conn->close();
}