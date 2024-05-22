<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id=$_POST["id"];
    if(!empty($_SESSION['user_id'])){
        header("Location:change.php?"."&id=".$_SESSION['id']);
    }
    
    // Подключение к базе данных
    $db = mysqli_connect('localhost', 'u67457', '2966709', 'u67457');
    if (!$db) {
        die('Error connecting to database: ' . mysqli_connect_error());
    }
    mysqli_set_charset($db, 'utf8');
    
    //Запрос к базе данных 
    $result = $db->query("SELECT * FROM users WHERE id = '$id'");
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $number = $row['number'];
    $email = $row['mail'];
    $date = $row['date'];
    $about = $row['about'];
    setcookie("nameC", $name, time()+5000,"/");
    setcookie("numberC", $number, time()+5000,"/");
    setcookie("emailC", $email, time()+5000,"/");
    setcookie("dateC", $date, time()+5000,"/");
    setcookie("aboutC", $about, time()+5000,"/");
    // Использование данных из сессии
    $_SESSION['id'] = $id;
    header("Location:change.php?");
    }
?>
