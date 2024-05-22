<?php
    $lengCount=array_fill(0,11,0);
    $conn = new mysqli("localhost", "u67457", "2966709", "u67457");
    if($conn->connect_error){
         die("Ошибка: " . $conn->connect_error);
    }
    $countLeng = [];
    for($i = 1; $i <= 11; $i++){
        $result = $conn->query("SELECT count(*) as leng FROM user_lengs WHERE leng_id = '$i'");
        $row = $result->fetch_assoc();
        $countLeng[$i-1] =  $row['leng'];
    }

    $lengs = array(
        "Pascal" => $countLeng[0],
        "C" => $countLeng[1],
        "C++" => $countLeng[2],
        "JavaScript" => $countLeng[3],
        "Php" => $countLeng[4],
        "Python" => $countLeng[5],
        "Java" => $countLeng[6],
        "Haskel" => $countLeng[7],
        "Clojure" => $countLeng[8],
        "Prolog" => $countLeng[9],
        "Scarse" => $countLeng[10],
    );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link rel="stylesheet" href="statStyle.css">
</head>
<body>
    <div class="len">
        <p class="text">Pascal: <?php echo $lengs['Pascal']?></p>
        <p class="text">C: <?php echo $lengs['C']?></p>
        <p class="text">C++: <?php echo $lengs['C++']?></p>
        <p class="text">Java Script: <?php echo $lengs['JavaScript']?></p>
        <p class="text">PHP: <?php echo $lengs['Php']?></p>
        <p class="text">Python: <?php echo $lengs['Python']?></p>
        <p class="text">Java: <?php echo $lengs['Java']?></p>
        <p class="text">Hask: <?php echo $lengs['Haskel']?></p>
        <p class="text">Clojure: <?php echo $lengs['Clojure']?></p>
        <p class="text">Prolog: <?php echo $lengs['Prolog']?></p>
        <p class="text">Scala: <?php echo $lengs['Scarse']?></p>
    </div>
</body>
