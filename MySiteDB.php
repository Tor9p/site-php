<?php
$host = "localhost";
$db = "d901193x_onedb";
$user = "d901193x_onedb"; 
$password = "Qwerty123!"; 

$link = mysqli_connect($host, $user, $password, $db);

if (!$link) {
    die("Ошибка подключения к БД: " . mysqli_connect_error());
}

mysqli_set_charset($link, "utf8mb4");
?>