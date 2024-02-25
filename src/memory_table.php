<?php
// Подключение к базе данных
include 'db_connect.php';


// Получение данных из формы
$action = $_POST['action'] ?? '';
$data = bin2hex(random_bytes(10));





if($action == "add")
{
    $sql = "INSERT INTO memory (description) VALUES ('$data')";

}
else
{
    $sql = "DELETE FROM memory";


}



// Выполнение запроса
$result = $conn->query($sql);


$sql = "SELECT * FROM memory";
$result = $conn->query($sql);


if ($result->num_rows >  0) {
    echo "<table><tr><th>ID</th><th>desc</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"]. "</td><td>" . $row["description"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();


