<?php
  $host = 'localhost';
  $user = 'u67303';
  $password = '8187062';
  $database = 'u67303';

  $conn = mysqli_connect($host, $user, $password, $database);

  if (!$conn) {
    die('Ошибка подключения к базе данных: ' . mysqli_connect_error());
  }

  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $surname = mysqli_real_escape_string($conn, $_POST['surname']);
  $number = mysqli_real_escape_string($conn, $_POST['number']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $date = mysqli_real_escape_string($conn, $_POST['date']);
  $gen = mysqli_real_escape_string($conn, $_POST['gen']);
  $about = mysqli_real_escape_string($conn, $_POST['about']);

  $lengs = $_POST['leng'];
  $arr_len = ["Pascal","C","C++","JavaScript","PHP","Python","Java","Haskel","Clijure","Prolog","Scara"];
  $arr_num_len = [0,0,0,0,0,0,0,0,0,0,0];

  foreach($lengs as $leng){
    $index = array_search($leng, $arr_len);
    if ($index !== false) {
      $arr_num_len[$index] = 1;
    }
  }

  $e1 = mysqli_real_escape_string($conn, $arr_num_len[0]);
  $e2 = mysqli_real_escape_string($conn, $arr_num_len[1]);
  $e3 = mysqli_real_escape_string($conn, $arr_num_len[2]);
  $e4 = mysqli_real_escape_string($conn, $arr_num_len[3]);
  $e5 = mysqli_real_escape_string($conn, $arr_num_len[4]);
  $e6 = mysqli_real_escape_string($conn, $arr_num_len[5]);
  $e7 = mysqli_real_escape_string($conn, $arr_num_len[6]);
  $e8 = mysqli_real_escape_string($conn, $arr_num_len[7]);
  $e9 = mysqli_real_escape_string($conn, $arr_num_len[8]);
  $e10 = mysqli_real_escape_string($conn, $arr_num_len[9]);
  $e11 = mysqli_real_escape_string($conn, $arr_num_len[10]);

  $error = "";
  switch (true) {
    case !(preg_match('/^[\p{Cyrillic}\s]*$/u', $name)):
        $error = "Имя содержит неправильные символы, убедитесь что вы все ввели верно<br>";
        break;
    case !(strlen($name) < 100):
        $error = "Имя содержит слишком много символов, убедитесь что вы все ввели верно<br>";
        break;
    case !(preg_match('/^[\p{Cyrillic}\s]*$/u', $surname)):
        $error = "Фамилия содержит неправильные символы, убедитесь что вы все ввели верно<br>";
        break;
    case !(strlen($surname) < 100):
        $error = "Фамилия содержит слишком много символов, убедитесь что вы все ввели верно<br>";
        break;
    case !(strlen($number) == 11):
        $error = "Номер введен не верно, убедитесь что вы все ввели верно<br>";
        break;
    case (intval(substr($date, 0, 4)) > 2016):
        $error = "Похоже вы слишком малы для использования формы, убедитесь что вы все ввели верно<br>";
        break;
    default:
      $query = "INSERT INTO users (person_name,person_surname,number,email,year,gen,about) VALUES ('$name', '$surname','$number','$email','$date', '$gen','$about')";

      mysqli_query($conn, $query);

     $user_id = mysqli_insert_id($conn);

      $query = "INSERT INTO leng (id,pascal,c,cpp,js,php,python,java,haskel,clijure,prolog,scara) VALUES ('$user_id','$e1', '$e2','$e3','$e4','$e5', '$e6','$e7','$e8','$e9','$e10','$e11')";

      if (mysqli_query($conn, $query)) {
        echo 'Данные успешно сохранены' . "<br>";
      } else {
        echo 'Ошибка сохранения данных: ' . mysqli_error($conn);
      }
  }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Моя страница</title>
    <link rel="stylesheet" type="text/css" href="stylePageTwo.css">
</head>
<body>
      <div class="error">
        <?php if ($error !== ""): ?>
          <?php echo $error ?>
          <a class="btn" href="javascript:history.back()"><input type="button" value="Вернутся"></input></a>
        <?php endif;?>
      </div>

      <?php if ($error == ""): ?>
        <div class="tables">
          <?php
          $query = "SELECT users.person_name, users.person_surname, users.number, users.email, users.year, users.gen, users.about, leng.pascal, leng.c, leng.cpp, leng.js, leng.php, leng.python, leng.java, leng.haskel, leng.clijure, leng.prolog, leng.scara
                  FROM users
                  INNER JOIN leng ON users.id = leng.id";

          $result = mysqli_query($conn, $query);
          ?>

          <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <div class="colom">
                  <?php
                  echo "Имя: " . $row["person_name"] . "<br>";
                  echo "Фамилия: " . $row["person_surname"] . "<br>";
                  echo "Номер телефона: " . $row["number"] . "<br>";
                  echo "Электронная почта: " . $row["email"] . "<br>";
                  echo "Год рождения: " . $row["year"] . "<br>";
                  echo "Пол: " . $row["gen"] . "<br>";
                  echo "О себе: " . $row["about"] . "<br>";
                  ?>
                  <div class="leng">
                    Владеет языками:
                    <?php
                    if($row["pascal"] == "1"){echo "Pascal, ";}
                    if($row["c"] == "1"){echo "C, ";}
                    if($row["cpp"] == "1"){echo "C++, ";}
                    if($row["js"] == "1"){echo "JS, ";}
                    if($row["php"] == "1"){echo "php, ";}
                    if($row["python"] == "1"){echo "python, ";}
                    if($row["java"] == "1"){echo "java, ";}
                    if($row["haskel"] == "1"){echo "haskel, ";}
                    if($row["clijure"] == "1"){echo "clijure, ";}
                    if($row["prolog"] == "1"){echo "prolog, ";}
                    if($row["scara"] == "1"){echo "scara ," ;}
                    ?>
                  </div>
              </div>
          <?php endwhile; ?>
        </div>
        <a class="btn" href="javascript:history.back()"><input type="button" value="Вернутся"></input></a>
      <?php endif; ?>
</body>
</html>

<?php mysqli_close($conn);?>
