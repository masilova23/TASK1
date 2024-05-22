
<?php
session_start();
session_destroy();
header("Location: index.php"); // перенаправление на главную страницу после выхода
?>
