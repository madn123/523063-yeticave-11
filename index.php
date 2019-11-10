<?php
require_once 'functions.php';

$is_auth = rand(0, 1);
$user_name = 'Кирилл';

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
    print($content);
    die();
}

$sql = 'SELECT id, category_name, category_code FROM categories';

$result = mysqli_query($link, $sql);

if (!$result) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
    print($content);
    die();
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql = 'SELECT name, start_price, image, completion_date, category_name FROM items i'
     . 'JOIN categories c ON i.category_id = c.id'
     . 'ORDER BY date_creation ASC DESC LIMIT 9';

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
    print($content);
    die();
}

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

$page_content = include_template('main.php', [
	'categories' => $categories,
	'items' => $items
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => $categories,
	'title' => 'YetiCave - Главная страница',
	'user_name' => $user_name,
	'is_auth' => $is_auth,
	'error' => $error
]);

print($layout_content);

