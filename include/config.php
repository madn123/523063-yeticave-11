<?php
session_start();

error_reporting(E_ERROR);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

$link = mysqli_connect("127.0.0.1", "root", "root", "yeticave");
if (!$link){
    echo "Не могу подключиться к MySQL<br>", PHP_EOL;
    echo "Измените файл <b>" . __FILE__,"</b>";
    die();
}
mysqli_set_charset($link, "utf8");
