<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {



include '../../db_connect.php';


/*
if(isset($_GET['date']))
{
    
}

if(isset($_GET['time']))
{
    
}*/

// Подготавливаем запрос для вставки данных
$stmt = $conn->prepare("SELECT data_time FROM rasp");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Итерация по периодам времени
$datesWithTimes = array();

foreach ($result as $row) {
    
    $dateOnly = date('Y-m-d', strtotime($row["data_time"]));
    $timeOnly = date('H:i:s', strtotime($row["data_time"]));
    
    // Добавление времени в массив, соответствующий дате
    if (!isset($datesWithTimes[$dateOnly])) {
        $datesWithTimes[$dateOnly] = array($timeOnly);
    } else {
        $datesWithTimes[$dateOnly][] = $timeOnly;
    }
}

print_r( $datesWithTimes);

}
