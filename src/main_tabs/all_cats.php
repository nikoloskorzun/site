<?php



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $authToken = $_COOKIE['auth_token'] ?? null;

    if ($authToken) {
        include '../db_connect.php';

        $get_username = "SELECT * FROM users WHERE auth_token = '$authToken'";
        $result = $conn->query($get_username);

        if ($result->num_rows > 0) {

            $user = $result->fetch_assoc();
            if ($user["access_right_id"] == 1) {


                $sql = "SELECT  
                        Cats.Name AS CatName,
                        Breeds.Name AS BreedName,
                        Owners.Name AS OwnerName,
                        Cats.Date_of_Birth,
                        MedicalRecords.Date_visit,
                        MedicalRecords.Description,
                        MedicalRecords.Weight
                    FROM    
                        Cats
                    JOIN  
                        Breeds ON Cats.Breed_ID = Breeds.ID
                    LEFT JOIN  
                        Owners ON Cats.Owner_ID = Owners.ID
                    LEFT JOIN  
                        MedicalRecords ON Cats.ID = MedicalRecords.Cat_ID
                    ORDER BY  
                        Cats.Date_of_Birth ASC";



                $result = $conn->query($sql);

                // Вывод результатов в таблице HTML
                if ($result->num_rows > 0) {
                    echo "<table border='1'>
            <tr>
                <th>Cat Name</th>
                <th>Breed Name</th>
                <th>Owner Name</th>
                <th>Date of Birth</th>
                <th>Date of Visit</th>
                <th>Description</th>
                <th>Weight</th>
            </tr>";
                    // Вывод каждой строки
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                <td>" . $row["CatName"] . "</td>
                <td>" . $row["BreedName"] . "</td>
                <td>" . $row["OwnerName"] . "</td>
                <td>" . $row["Date_of_Birth"] . "</td>
                <td>" . $row["Date_visit"] . "</td>
                <td>" . $row["Description"] . "</td>
                <td>" . $row["Weight"] . "</td>
              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }
            } elseif ($user["access_right_id"] == 2) {

                $sql = "SELECT  
                        Cats.Name AS CatName,
                        Breeds.Name AS BreedName,
                        
                        Cats.Date_of_Birth,
                        MedicalRecords.Date_visit,
                        MedicalRecords.Description,
                        MedicalRecords.Weight
                    FROM    
                        Cats
                    JOIN  
                        Breeds ON Cats.Breed_ID = Breeds.ID
                    LEFT JOIN  
                        Owners ON Cats.Owner_ID = Owners.ID
                    LEFT JOIN  
                        MedicalRecords ON Cats.ID = MedicalRecords.Cat_ID
                    WHERE   
                        Owners.User_ID = ?
                    ORDER BY  
                        Cats.Date_of_Birth ASC";



                $stmt = $conn->prepare($sql);


                $stmt->bind_param("i", $user['id']);
                $stmt->execute();

                $result = $stmt->get_result();


                // Вывод результатов в таблице HTML
                if ($result->num_rows > 0) {

                    $sql = "SELECT  
                    Owners.Name AS OwnerName
                FROM    
                    Owners
                WHERE   
                    Owners.User_ID = ?";



            $stmt = $conn->prepare($sql);


            $stmt->bind_param("i", $user['id']);
            $stmt->execute();

            $res = $stmt->get_result();
            $res = $res->fetch_assoc();

                    echo "<h2>".$res['OwnerName']."</h2>";
                    
                    
                    
                    echo "<table border='1'>
            <tr>
                <th>Cat Name</th>
                <th>Breed Name</th>
                <th>Date of Birth</th>
                <th>Date of Visit</th>
                <th>Description</th>
                <th>Weight</th>
            </tr>";
                    // Вывод каждой строки
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                <td>" . $row["CatName"] . "</td>
                <td>" . $row["BreedName"] . "</td>
                <td>" . $row["Date_of_Birth"] . "</td>
                <td>" . $row["Date_visit"] . "</td>
                <td>" . $row["Description"] . "</td>
                <td>" . $row["Weight"] . "</td>
              </tr>";
                    }
                    echo "</table>";
                }
            } else {
                echo "Не достаточно прав на просмотр этой страницы";
            }
        } else {
            echo "Не действительный auth token";
        }
    } else {

        echo "Вы не вошли";
    }
} else {
    // Если запрос не является POST-запросом, отправляем ошибку
    http_response_code(405);
    echo json_encode(['error' => 'Метод не поддерживается.']);
}
