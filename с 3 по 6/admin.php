<?php
    if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    md5($_SERVER['PHP_AUTH_PW']) != md5('123')) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
    }

    print('Вы успешно авторизовались и видите защищенные паролем данные');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="adminStyle.css">
</head>
<body>
    <a href="./statistics.php"><button class="but">Посмотреть статистику</button></a>
    <?php
    $conn = new mysqli("localhost", "u67457", "2966709", "u67457");
    if($conn->connect_error){
        die("Ошибка: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM users";
    if($result = $conn->query($sql)){
        echo "<table><tr><th>Id</th><th>Имя</th><th>Телефон</th><th>Почта</th><th>Дата рождения</th><th>Пол</th><th>Биография</th><th></th><th></th></tr>";
        foreach($result as $row){
            echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["number"] . "</td>";
                echo "<td>" . $row["mail"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
                if($row["gen"]=='2'){echo "<td>" . "Женский" . "</td>";}
                else{echo "<td>" . "Мужской" . "</td>";}
                echo "<td>" . $row["about"] . "</td>";
                echo "<td><form action='./delete.php' method='post'>
                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                        <input type='submit' value='Удалить'>
                </form></td>";
                echo "<td><form action='./adminChange.php' method='post'>
                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                        <input type='submit' value='Изменить'>
                </form></td>";
            echo "</tr>";
        }
        echo "</table>";
        $result->free();
    } else{
        echo "Ошибка: " . $conn->error;
    }
    ?>
    <p class="text"><?php if(!empty($answer)) {echo $answer;} ?></p>
</body>
