
<?php
if(!empty($_GET["answer"])){
$answer = $_GET["answer"];}

session_start();
if(isset($_SESSION['id'])) {
    header("Location: change.php"); // перенаправление на страницу личного кабинета
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST["login"];
    $password = $_POST["password"];
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
    $result = $db->query("SELECT * FROM users WHERE login = '$login'");
    $row = $result->fetch_assoc();
    $id = $row['id'];
    $name = $row['name'];
    $number = $row['number'];
    $email = $row['mail'];
    $date = $row['date'];
    $about = $row['about'];
    $pass = $row['pass'];
    
    if($password == $pass){
        setcookie("nameC", $name, time()+5000,"/");
        setcookie("numberC", $number, time()+5000,"/");
        setcookie("emailC", $email, time()+5000,"/");
        setcookie("dateC", $date, time()+5000,"/");
        setcookie("aboutC", $about, time()+5000,"/");
        // Использование данных из сессии
        $_SESSION['id'] = $id;
        header("Location:change.php?");
    }
    else{
        $answer = "Неправильный пароль";
        header("Location:index.php?answer=".$answer);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content">
        <div class="loginForm">
            <h1>Вход</h1>
            <p class="error"><?php if(!empty($answer)) {echo $answer;} ?></p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" >
                <input name="login" type="text" placeholder="Login">
                <input name="password" type="password" placeholder="Password">
                <div class="buttons">
                    <button class="enter">Вход</button>
                    <a class="admin" href="./admin.php">Войти как админ</a>
                </div>
            </form>
            <a href="./form.php"><button class="new">Я новый пользователь</button></a>
        </div>
    </div>
</body>
</html>
