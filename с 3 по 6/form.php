
<?php
if(!empty($_GET['login'])){
    $user_login = $_GET['login'];}
if(!empty($_GET['pass'])){
    $user_pass = $_GET['pass'];}
if(!empty($_GET['answer'])){
    $answer = $_GET['answer'];}
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $number = $_POST["number"];
        $date = $_POST["date"];
        $gen = $_POST["gen"];
        $lengs = $_POST["leng"];
        $about = $_POST["about"];

        $flag = 0;

        setcookie("email", $email, time()+31536,"/");
        // Валидация имени
        if (!preg_match('/^[а-яёА-ЯЁ]+$/u', $name)){
            setcookie("nameErr", 'error', time()+5000, "/");
            setcookie("name", '', time()-5000,"/");
            $flag = 1;
        }
        else{
            setcookie("nameErr", '', time()-5000, "/");
            setcookie("name", $name, time()+5000, "/");
        }
        // Валидация номера
        if (strlen($number) != 11){
            setcookie("numberErr", 'error', time()+5000, "/");
            setcookie("number", '', time()-5000);
            $flag = 1;
        }
        else{
            setcookie("number", $number, time()+5000, "/");
            setcookie("numberErr", '', time()-3600, "/");
        }
        // Валидация даты
        if(intval($date) < 1924 || intval($date) > 2008){
            setcookie("dateErr", 'error', time()+5000,"/");
            setcookie("date", '', time()-5000,"/");
            $flag = 1;
        }else{
            setcookie("dateErr", '', time()-5000,'/');
            setcookie("date", $date, time()+5000,"/");
        }

        if($flag == 1){
            header("Location: form.php?answer=".$answer);
        }else{
            $db = mysqli_connect("localhost","u67457","2966709","u67457");
            if(!$db){
                die('Error connecting to database: ' . mysqli_connect_error());
            }
            mysqli_set_charset($db, 'utf8');

            $name = mysqli_real_escape_string($db, $name);
            $number = mysqli_real_escape_string($db, $number);
            $email = mysqli_real_escape_string($db, $email);
            $date = mysqli_real_escape_string($db, $date);
            $gen = mysqli_real_escape_string($db, $gen);
            $about = mysqli_real_escape_string($db, $about);
            //$id = mysqli_real_escape_string($db, $_SESSION["id"]);
            $login = urlencode(substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));
            $pass = urlencode(substr(str_shuffle("0123456789"), 0, 5));
            
            $db->query("INSERT INTO users (name,number,mail,date,gen,about,pass,login) VALUES ('$name','$number','$email','$date', '$gen', '$about', '$pass','$login')");
            $result = $db->query("SELECT * FROM users WHERE login = '$login'");
            $row = $result->fetch_assoc();
            $id = $row['id'];

            $stmt = $db->prepare("INSERT INTO user_lengs(user_id, leng_id) SELECT ?, lengs.id FROM lengs WHERE lengs.leng = ?");
            foreach ($lengs as $leng) {
                $stmt->bind_param("is",$id, $leng);
                $stmt->execute();
            }
            $answer = "Данные отправлены!";
            $answer = urlencode($answer);
            header("Location: form.php?answer=".$answer."&login=".$login."&pass=".$pass);
        }
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма для записи</title>
    <link rel="stylesheet" href="formStyle.css">
</head>
<body>
    <h1><?php if(!empty($answer)) echo $answer ?></h1>
    <h1>
        <?php
        if(!empty($answer)){
            if($answer != ""){
                echo "Логин-".$user_login."  Пароль-".$user_pass;
            } 
        }
        ?>
    </h1>
    <form id="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="body">
            <div class="info">
                <div class="input">
                    <!-- Имя -->
                    <input class="<?php if(!empty($_COOKIE['nameErr'])) echo $_COOKIE['nameErr']; ?>" name="name" id="name" type="text" value="<?php if (!empty($_COOKIE['name'])) echo $_COOKIE["name"]; ?>" placeholder="Введите имя" required>
                        <span class="span <?php if(!empty($_COOKIE['nameErr'])) echo $_COOKIE['nameErr']; ?>"> <?php if(isset($_COOKIE['nameErr'])) echo "Неверные символы" ?> </span>
                    <!-- Номер -->
                    <input class="<?php if(!empty($_COOKIE['numberErr'])) echo $_COOKIE['numberErr']; ?>" name="number" id="number" type="number" value="<?php if(!empty($_COOKIE['number'])) echo $_COOKIE["number"]; ?>" placeholder="Введите телефон" required>
                        <span class="span <?php if(!empty($_COOKIE['numberErr'])) echo $_COOKIE['numberErr']; ?>"> <?php if(isset($_COOKIE['numberErr'])) echo "Неправильное количество цифр"; ?> </span>
                    <!-- Почта -->
                    <input name="email" id="email" type="email" value="<?php if(!empty($_COOKIE["email"])) echo $_COOKIE["email"]; ?>" placeholder="Введите почту" required>
                    <!-- Дата -->
                    <input class="<?php if(!empty($_COOKIE['dateErr'])) echo $_COOKIE['dateErr']?>" name="date" id="date" type="date" value="<?php if(!empty($_COOKIE['date'])) echo $_COOKIE['date']?>" placeholder="" required>
                        <span class="span <?php if(!empty($_COOKIE['dateErr'])) echo $_COOKIE['dateErr'] ?>"> <?php if(isset($_COOKIE['dateErr'])) echo "Некорректная дата" ?> </span>
                </div>
                <div class="cheked">
                    <div class="radio_pol">
                        <label>
                            <input name="gen" value="1" type="radio" required>Мужчина
                        </label>
                        <label>
                            <input name="gen" value="2" type="radio" required>Женщина
                        </label>
                    </div>
                    <div class="langvich_section">
                        <h4>Выберите язык программирования</h4>
                        <select multiple name="leng[]" class="langvich" name="langvich">
                            <option value="Pascal">Pasc</option>
                            <option value="C">C</option>
                            <option value="C++">C++</option>
                            <option value="Java Script">JS</option>
                            <option value="php">PHP</option>
                            <option value="python">Py</option>
                            <option value="java">Java</option>
                            <option value="hask">Hask</option>
                            <option value="clojure">Cloj</option>
                            <option value="prolog">Prol</option>
                            <option value="scala">Scarse</option>
                        </select>
                    </div>
                    <div class="textarea">
                        <h4>Напишите о себе</h4>
                        <textarea name="about" cols="30" rows="8" required></textarea>
                    </div>
                    <label>
                        <input class="custom-checkbox" type="checkbox" name="document" id="" required>Я согласен(а) c условиями конфидециальности
                    </label>
                </div>
                <button class="button" type="submit">Отправить</button>
            </div>
        </div>
    </form>
    <a href="index.php"><button class="exit">Назад</button></a>
</body>
</html>
