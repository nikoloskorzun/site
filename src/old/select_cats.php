<?php
// Подключение к базе данных
include 'db_connect.php';


// Получение данных из формы
$name = $_POST['name'] ?? '';
$breed_id = $_POST['breed_id'] ?? '';
$owner_id = $_POST['owner_id'] ?? '';
$date_of_birth = $_POST['date_of_birth'] ?? '';
$gender = $_POST['gender'] ?? '';

// Создание SQL-запроса
$sql = "SELECT * FROM Cats WHERE  1=1";

// Добавление условий к запросу
if (!empty($name)) {
    $sql .= " AND Name LIKE '%$name%'";
}
if (!empty($breed_id)) {
    $sql .= " AND Breed_ID = $breed_id";
}
if (!empty($owner_id)) {
    $sql .= " AND Owner_ID = $owner_id";
}
if (!empty($date_of_birth)) {
    $sql .= " AND Date_of_Birth = '$date_of_birth'";
}
if (!empty($gender)) {
    $sql .= " AND gender = '$gender'";
}

// Выполнение запроса
$result = $conn->query($sql);

// Вывод результатов
if ($result->num_rows >  0) {
    echo "<table><tr><th>ID</th><th>Name</th><th>Breed_ID</th><th>Owner_ID</th><th>Date_of_Birth</th><th>Gender</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["ID"]. "</td><td>" . $row["Name"]. "</td><td>" . $row["Breed_ID"]. "</td><td>" . $row["Owner_ID"]. "</td><td>" . $row["Date_of_Birth"]. "</td><td>" . $row["gender"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

// Закрытие соединения
$conn->close();
