<?php
require_once 'functions.php';
require_once 'config.php';
require_once 'uploads/.gitkeep';

if (!$link) {
    $error = mysqli_error($link);
}

$sql = 'SELECT id, category_name, category_code FROM categories ORDER BY category_name ASC';

$result = mysqli_query($link, $sql);

if (!$result) {
    $error = debug_error($link);
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql = 'SELECT i.id, name, start_price, image, completion_date, c.category_name FROM items i '
    . 'JOIN categories c ON i.category_id = c.id '
    . 'ORDER BY date_creation DESC LIMIT 6';

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = debug_error($link);
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
