<?php
require_once 'include.php';

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
]);

print($layout_content);
