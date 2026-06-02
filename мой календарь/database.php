<?php
//Данные для подключения к бд
$host = 'localhost';
$dbname = 'my_calendar_iv31';
$username = 'my_calendar_iv31';
$password = 'W7y41Aah';
//Подлкючение к бд
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    //echo "База данных успешно подключена!"
} catch(PDOException $e) {
    echo "Возникла ошибка подключения к базе данных";
}
?>