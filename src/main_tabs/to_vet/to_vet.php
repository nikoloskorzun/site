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
            if ($result["access_rights"] == 2) {
                $sql = "SELECT Cats.ID as CatID, Cats.Name as CatName, Breeds.Name as BreedName, Cats.Date_of_Birth, Cats.image FROM Cats JOIN Breeds ON Cats.Breed_ID = Breeds.ID WHERE Owner_ID = :userId";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':userId', $result['id']);
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "
<style>
#bid {
    position: fixed;
    right: 0;
    top: 50px;
    width: 300px;
    border-left: 1px solid #ccc;
    padding: 10px;
    background-color: #f9f9f9;
}

#bidItems {
    list-style-type: none;
    padding: 0;
}

#bidItems li {
    border-bottom: 1px solid #ccc;
    padding: 5px 0;
}

        </style>";



        echo " <div id='bid'>
        <h2>Заявка</h2>
        <ul id='bidItems'></ul>
        <button id='checkout' onclick='send_bid()'>Оставить заявку</button>
    </div>";





                if (!empty($res)) {
                    echo "<h2 class='page-header'>".$result['name']." - питомцы</h2>";
                    echo "<style>.no-border {border: none;} </style>";
                    echo "<table border='1' id='to_vet-table-id'>
                        <tr>
                            <th onclick='sortTable(0)'>Кличка</th>
                            <th onclick='sortTable(1)'>Порода</th>
                            <th onclick='sortTable(2)'>Дата рождения</th>

                            <th>Изображение</th>
                            <th class='no-border'>Действие</th>

                        </tr>";
                    foreach ($res as $row) {

                        $imageData = $row["image"] !== null ? base64_encode($row["image"]) : '';
                        $src = $imageData ? 'data:image/jpeg;base64,' . $imageData : 'default_images/cat-default.png';
                        echo "<tr>
                            <td>" . $row["CatName"] . "</td>
                            <td>" . $row["BreedName"] . "</td>
                            <td>" . $row["Date_of_Birth"] . "</td>

                            <td><img src='" . $src . "' alt='Cat Image' width='auto' height='100' style='display: block; margin: auto;'></td>
                            <td class='no-border' onclick='to_vet(\"". $row['CatID'].":".$row['CatName']."\")'>Записать кота к врачу</td>
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
