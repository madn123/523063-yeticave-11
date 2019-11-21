<?php
date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");

if (!$link) {
    $error = mysqli_error($link);
    die();
}

$sql = 'SELECT id, category_name, category_code FROM categories ORDER BY category_name ASC';

$result = mysqli_query($link, $sql);

if (!$result) {
    $error = debug_error($link);
    die();
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);