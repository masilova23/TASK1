<?php
    if(isset($_POST["id"]))
    {
        $answer="";
        $db = mysqli_connect("localhost", "u67457", "2966709", "u67457");
        if (!$db) {
            die('Error connecting to database: ' . mysqli_connect_error());
        }
        mysqli_set_charset($db, 'utf8');
        $id=$_POST["id"];
        $db->query("DELETE FROM user_lengs WHERE user_id = '$id'");
        $db->query("DELETE FROM users WHERE id = '$id'");
        $answer="Успешно удалено";
        header("Location: admin.php");
    }
?>
