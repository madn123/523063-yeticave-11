<?php

$is_auth = rand(0, 1);
$user_name = 'Кирилл';

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");
