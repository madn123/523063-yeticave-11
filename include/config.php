<?php
session_start();

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");
