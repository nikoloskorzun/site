<?php



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $authToken = $_COOKIE['auth_token'] ?? null;

    if ($authToken) {
        include '../../db_connect.php';







        $get_username = "SELECT * FROM users WHERE auth_token = :authToken";
        $stmt = $conn->prepare($get_username);
        $stmt->bindParam(':authToken', $authToken);
        $stmt->execute();


        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($result) {
            if ($result["access_rights"] == 1) {
                $sql = "SELECT Cats.Name AS CatName, Breeds.Name AS BreedName, users.Name AS OwnerName, Cats.Date_of_Birth, MedicalRecords.Date_visit, MedicalRecords.Description, MedicalRecords.Weight, Cats.image, Cats.ID FROM Cats JOIN Breeds ON Cats.Breed_ID = Breeds.ID LEFT JOIN users ON Cats.Owner_ID = users.ID LEFT JOIN MedicalRecords ON Cats.ID = MedicalRecords.Cat_ID ORDER BY Cats.Date_of_Birth ASC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Вывод результатов в таблице HTML
                if (!empty($result)) {
                    echo "<table border='1' id='allCats-table-id'>
                        <tr>
                            <th onclick='sortTable(0)'>Cat Name</th>
                            <th onclick='sortTable(1)'>Breed Name</th>
                            <th onclick='sortTable(2)'>Owner Name</th>
                            <th onclick='sortTable(3)'>Date of Birth</th>
                            <th onclick='sortTable(4)'>Date of Visit</th>
                            <th onclick='sortTable(5)'>Description</th>
                            <th onclick='sortTable(6)'>Weight</th>
                            <th>Image</th>
                        </tr>";
                    foreach ($result as $row) {
                        $imageData = $row["image"] !== null ? base64_encode($row["image"]) : '';
                        $src = $imageData ? 'data:image/jpeg;base64,' . $imageData : 'default_images/cat-default.png';    
                        echo "<tr>
                            <td>" . $row["CatName"] . "</td>
                            <td>" . $row["BreedName"] . "</td>
                            <td>" . $row["OwnerName"] . "</td>
                            <td>" . $row["Date_of_Birth"] . "</td>
                            <td>" . $row["Date_visit"] . "</td>
                            <td>" . $row["Description"] . "</td>
                            <td>" . $row["Weight"] . "</td>
                            <td><img src='" . $src . "' alt='Cat Image' width='auto' height='100' style='display: block; margin: auto;'></td>

                          </tr>";
                    }
                    echo "</table>";
                
            } else {
                    echo "У вас пока нет котиков";
                }

            }
            
            elseif ($result["access_rights"] == 2) {
                $sql = "SELECT Cats.Name AS CatName, Breeds.Name AS BreedName, Cats.Date_of_Birth, MedicalRecords.Date_visit, MedicalRecords.Description, MedicalRecords.Weight, Cats.image FROM Cats JOIN Breeds ON Cats.Breed_ID = Breeds.ID LEFT JOIN users ON Cats.Owner_ID = users.ID LEFT JOIN MedicalRecords ON Cats.ID = MedicalRecords.Cat_ID WHERE users.ID = :userId ORDER BY Cats.Date_of_Birth ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':userId', $result['id']);
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($res)) {
                    echo "<h2>".$result['name']."</h2>";
                    echo "<table border='1' id='allCats-table-id'>
                        <tr>
                            <th onclick='sortTable(0)'>Cat Name</th>
                            <th onclick='sortTable(1)'>Breed Name</th>
                            <th onclick='sortTable(2)'>Date of Birth</th>
                            <th onclick='sortTable(3)'>Date of Visit</th>
                            <th onclick='sortTable(4)'>Description</th>
                            <th onclick='sortTable(5)'>Weight</th>
                            <th>Image</th>
                        </tr>";
                    foreach ($res as $row) {

                        $imageData = $row["image"] !== null ? base64_encode($row["image"]) : '';
                        $src = $imageData ? 'data:image/jpeg;base64,' . $imageData : 'default_images/cat-default.png';
                        echo "<tr>
                            <td>" . $row["CatName"] . "</td>
                            <td>" . $row["BreedName"] . "</td>
                            <td>" . $row["Date_of_Birth"] . "</td>
                            <td>" . $row["Date_visit"] . "</td>
                            <td>" . $row["Description"] . "</td>
                            <td>" . $row["Weight"] . "</td>
                            <td><img src='" . $src . "' alt='Cat Image' width='auto' height='100' style='display: block; margin: auto;'></td>
                          </tr>";
                    }
                    echo "</table>";
                }
				else {
                    echo "У вас пока нет котиков";
                }
				}
            
            
            else {
                echo "Не достаточно прав на просмотр этой страницы";
            }
        } else {
            echo "Не действительный auth token";
        }
    }else {

        echo "Вы не вошли";
    }
} else {
    // Если запрос не является POST-запросом, отправляем ошибку
    http_response_code(405);
    echo json_encode(['error' => 'Метод не поддерживается.']);
}
